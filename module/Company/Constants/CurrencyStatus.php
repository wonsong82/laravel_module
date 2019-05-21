<?php
namespace Module\Company\Constants;

class CurrencyStatus {
    const ACTIVE    = 200701;
    const INACTIVE  = 200702;

    public $types = [
        self::ACTIVE => 'info',
        self::INACTIVE => 'default'
    ];

    public $exclude = false; // set this true to skip adding this constant to the db
}

