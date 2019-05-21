<?php
namespace Module\Company;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Module\Application\Traits\HasActivityLogs;
use Module\Company\Traits\Admin\OffdayCrudTrait;

class Offday extends Model
{
    // ModelCrudTrait, HasConstants, HasAddresses, HasPhones, AddressCrudTrait, HasOrder
    use CrudTrait, OffdayCrudTrait,HasActivityLogs;


    protected $table = 'company_offdays';
    protected $fillable = [
        'company_id',
        'year',
        'month',
        'day',
        'date',
        'note'
    ];

    protected $casts = [
        'date' => 'date'
    ];


    // ATTRIBUTES

    public function getMonthTextAttribute()
    {
        return $this->year . '-' . sprintf('%02d', $this->month);
    }

    public function getDateTextAttribute()
    {
        return $this->date->format('Y-m-d');
    }




    // STATIC METHODS




    // METHODS




    // SCOPES

    public function scopeOrder($query, $name, $direction)
    {
        switch($name){
            case 'default':
                $query
                    ->orderBy('year', 'desc')->orderBy('month', 'desc');
                break;

            case 'month_text':
                $query
                    ->orderBy('year', $direction)->orderBy('month', $direction);
                break;

            case 'example':
                $query
                    ->leftJoin('users', function($join){
                        $join->on('company_members.user_id', '=', 'users.id');
                    })
                    ->orderBy('users.email', $direction)
                    ->select('company_members.*');
                break;
        }
    }


    public function scopeSearch($query, $name, $term)
    {
        switch($name) {
            case 'month_text':
                $query
                    ->orWhere(\DB::raw('CONCAT(year, "-", LPAD(month, 2, "0"))'), 'LIKE', "%$term%");
                break;
        }
    }




    // RELATIONS


        public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }



}
