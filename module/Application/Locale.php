<?php
namespace Module\Application;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Module\Application\Traits\HasActivityLogs;
use Module\Application\Traits\HasModelChanges;


class Locale extends Model
{
    use HasModelChanges, HasActivityLogs,
        CrudTrait;


    protected $table = 'locales';
    protected $fillable = [
        'code', // en
        'locale', // en-US
        'country_code', // US [ISO_3166]
        'language_code', // en [ISO_639]
        'country_name', // United States
        'language_name', // English
        'encoding' // UTF-8
    ];

    protected $casts = [
    ];



    // ATTRIBUTES

    public function getFlagHtmlAttribute()
    {
        return '<span class="flag-icon flag-icon-' . strtolower($this->attributes['country_code']) . '"></span>';
    }




    // STATIC METHODS

    public static function findByLocale($code)
    {
        return self::where('locale', $code)->first();
    }




    // METHODS




    // SCOPES

    public function scopeOrder($query, $name, $direction)
    {
        switch($name){

            case 'default':
                $query
                    ->orderBy('id');
                break;

            default:
                $query
                    ->orderBy($name, $direction);
        }
    }


    public function scopeSearch($query, $name, $term)
    {
        switch($name){

            default:
                $query
                    ->orWhere($name, 'like', "%{$term}%");
        }
    }




    // RELATIONS




}