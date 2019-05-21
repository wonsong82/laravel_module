<?php
namespace Module\Application;

use Module\Application\Constants\UserStatus;
use Module\Application\Traits\HasActivityLogs;
use Module\Application\Traits\HasConstants;
use Backpack\Base\app\Models\BackpackUser;
use Backpack\CRUD\CrudTrait;
use Spatie\Permission\Traits\HasRoles;
use Module\Application\Traits\HasModelChanges;
use Module\Company\CompanyUser;


/**
 * @property null|object|Constant company_user
 * @property null|object|Constant status_code
 * @property null|object|\Module\Application\Constant roles
 */
class User extends BackpackUser
{
    use HasRoles, HasConstants, HasModelChanges, HasActivityLogs,
        CrudTrait;


    protected $table = 'users';
    protected $fillable = [
        'status_code',
        'email',
        'name',
        'password',
        'locale_id',
        'timezone'
        //status,
        //is_active,
        //company
    ];

    protected $casts = [

    ];



    // ATTRIBUTES

    public function getIsActiveAttribute()
    {
        return $this->status_code == UserStatus::ACTIVE;
    }

    public function getCompanyAttribute()
    {
        if(module_loaded('Company'))
            if($this->companyUser && $this->companyUser->company)
                return $this->companyUser->company;

        return null;
    }



    // STATIC METHODS




    // METHODS




    // SCOPES

    public function scopeOrder($query, $name, $direction)
    {
        switch($name){
            case 'default':
                $query
                    ->orderBy('id');
                break;

            case 'example':
                $query
                    ->leftJoin('users', function($join){
                        $join->on('company_members.user_id', '=', 'users.id');
                    })
                    ->orderBy('users.email', $direction)
                    ->select('company_members.*');
                break;

            case 'locale':
                $query
                    ->leftJoin('locales', function($join){
                        $join->on('users.locale_id', '=', 'locales.id');
                    })
                    ->orderBy('locales.locale', $direction)
                    ->select('users.*');
                break;

            case 'language':
                $query
                    ->leftJoin('locales', function($join){
                        $join->on('users.locale_id', '=', 'locales.id');
                    })
                    ->orderBy('locales.language_name', $direction)
                    ->select('users.*');
                break;

            case 'status':
                $query
                    ->orderBy('status_code', $direction);
                break;

            default:
                $query
                    ->orderBy($name, $direction);
        }
    }


    public function scopeSearch($query, $name, $term)
    {
        switch($name){

            case 'example':
                $query
                    ->orWhere(function($q) use($term){
                        $q->whereHas('user', function($q) use($term){
                            $q->where('email', 'like', '%'.$term.'%');
                        });
                    });
                break;

            case 'locale':
                $query
                    ->orWhere(function($q) use($term){
                        $q->whereHas('locale', function($q) use($term){
                            $q->where('locale', 'like', "%{$term}%");
                        });
                    });
                break;

            case 'language':
                $query
                    ->orWhere(function($q) use($term){
                        $q->whereHas('locale', function($q) use($term){
                            $q->where('language_name', 'like', "%{$term}%");
                        });
                    });
                break;

            case 'status':
                break;

            default:
            $query
                ->orWhere($name, 'like', "%{$term}%");
        }
    }




    // RELATIONS

    public function belongsTos()
    {
        return [
            'locale_id' => [Locale::class, 'language_name']
        ];
    }

    public function companyUser()
    {
        return $this->hasOne(CompanyUser::class, 'user_id');
    }

    public function locale()
    {
        return $this->belongsTo(Locale::class, 'locale_id');
    }
}
