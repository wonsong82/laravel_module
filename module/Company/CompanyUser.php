<?php

namespace Module\Company;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Module\Application\Events\SerializedModelCreated;
use Module\Application\Events\SerializedModelCreating;
use Module\Application\Traits\HasConstants;
use Module\Application\Traits\HasActivityLogs;
use Module\Application\Traits\HasModelChanges;
use Module\Application\User;
use Module\Company\Traits\HasCompany;

/**
 * @property null|object|\Module\Application\Constant company
 * @property null|object|\Module\Application\Constant user
 */
class CompanyUser extends Model
{
    use HasConstants, HasModelChanges, HasActivityLogs, HasRoles, HasCompany,
        CrudTrait;


    protected $table = 'company_users';
    protected $fillable = [
        'company_id',
        'user_id',
        'code',
        'code_serial',
        'status_code',
        //status
        'name'
        //email
    ];

    protected $casts = [
    ];



    // ATTRIBUTES

    public function getEmailAttribute()
    {
        return $this->user? $this->user->email : null;
    }




    // STATIC METHODS




    // METHODS




    // SCOPES

    public function scopeOrder($query, $name, $direction)
    {
        switch($name){
            case 'default':
                $query
                    ->orderBy('id', 'desc');
                break;

            case 'status':
                $query
                    ->orderBy('status_code', $direction);
                break;

            case 'company_id':
                $query
                    ->leftJoin('companies', function($join){
                        $join->on('companies.id', '=', 'company_users.company_id');
                    })
                    ->orderBy('companies.name', $direction)
                    ->select('company_users.*');
                break;

            case 'email':
                $query
                    ->leftJoin('users', function($join){
                        $join->on('company_users.user_id', '=', 'users.id');
                    })
                    ->orderBy('users.email', $direction)
                    ->select('company_users.*');
                break;

            default:
                $query
                    ->orderBy($name, $direction);
        }
    }



    public function scopeSearch($query, $name, $term)
    {
        switch($name){
            case 'company_id':
                $query
                    ->orWhere(function($q) use($term){
                        $q->whereHas('company', function($q) use($term){
                            $q->where('name', 'like', "%{$term}%");
                        });
                    });

            case 'email':
                $query
                    ->orWhere(function($q) use($term){
                        $q->whereHas('user', function($q) use($term){
                            $q->where('email', 'like', '%'.$term.'%');
                        });
                    });
                break;

            default:
                $query
                    ->orWhere($name, 'like', "%{$term}%");
        }
    }




    // SERIAL CODE IF ANY

    protected $dispatchesEvents = [
        'creating' => SerializedModelCreating::class,
        'created' => SerializedModelCreated::class
    ];

    public $serialKey = 'code_serial';

    public function assignCode()
    {
        $prefix = 'E';
        $length = 6;

        return $this->company->generateSerialCode($prefix, $length)->code;
    }






    // RELATIONS

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
