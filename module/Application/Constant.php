<?php

namespace Module\Application;

use Illuminate\Database\Eloquent\Model;

class Constant extends Model
{
    protected $table = 'constants';
    protected $fillable = [
        'constant_header_id',
        'code',
        'name',
        'display_name',
        'type',
        'order',
        'key'
    ];
    public $timestamps = false;


    public static function find($code)
    {
        $constant = self::query()
            ->with('header')
            ->where('code', $code)
            ->first();

        if(!$constant)
            throw new \Exception('Constant Not Found.');

        return $constant;
    }


    public static function getOptions($name)
    {
        return ConstantHeader::query()
            ->where('name', $name)
            ->first()
            ->constants
            ->pluck('key', 'code')
            ->map(function($e){
                return __($e);
            });
    }



    // RELATIONS

    public function header()
    {
        return $this->belongsTo(ConstantHeader::class, 'constant_header_id', 'id');
    }



}
