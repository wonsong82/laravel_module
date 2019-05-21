<?php
namespace Module\Company\Constants;

class UnitStatus {
    const ACTIVE    = 200901;
    const INACTIVE  = 200902;

    public $types = [
        self::ACTIVE => 'info',
        self::INACTIVE => 'default'
    ];

    public $exclude = false; // set this true to skip adding this constant to the db
}

