<?php

namespace Module\Application\Traits;


trait HasOrder
{
    public $hasOrder = true;

    public static function getMaxOrder($field = 'rgt')
    {
        return self::max($field) ?? 0;
    }

}
