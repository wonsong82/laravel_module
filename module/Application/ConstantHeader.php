<?php

namespace Module\Application;

use Illuminate\Database\Eloquent\Model;

class ConstantHeader extends Model
{
    protected $table = 'constant_headers';
    protected $fillable = [
        'name',
        'display_name'
    ];
    public $timestamps = false;




    // RELATIONS

    public function constants()
    {
        return $this->hasMany(Constant::class);
    }

}