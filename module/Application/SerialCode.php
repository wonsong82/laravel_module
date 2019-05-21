<?php

namespace Module\Application;

use Illuminate\Database\Eloquent\Model;

class SerialCode extends Model
{
    protected $table = 'serial_codes';
    protected $fillable = [
        'unique_key',
        'prefix',
        'last_seq',
        'length',
        // code
    ];


    public static function generateCode($key, $prefix, $length)
    {
        $code = self::query()
            ->where('unique_key', $key)
            ->where('prefix', $prefix)
            ->first();

        if($code){
            $code->fill([
                'last_seq' => ++$code->last_seq
            ])->save();
        }
        else {
            $code = self::query()
                ->create([
                    'unique_key' => $key,
                    'prefix' => $prefix,
                    'last_seq' => 1,
                    'length' => $length
                ]);
        }


        return $code;
    }


    public function getCodeAttribute()
    {
        return $this->prefix . sprintf('%0' . $this->length . 'd', $this->last_seq);
    }


    public function updateCodeLength($key, $prefix, $length)
    {
        $code = self::query()
            ->where('unique_key', $key)
            ->where('prefix', $prefix)
            ->first();

        if($code){
            $code->fill([
                'length' => $length
            ])->save();

            return true;
        }

        return false;
    }



}
