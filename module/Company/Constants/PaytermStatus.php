<?php
namespace Module\Company\Constants;

class PaytermStatus {
    const ACTIVE    = 200801;
    const INACTIVE  = 200802;

    public $types = [
        self::ACTIVE => 'info',
        self::INACTIVE => 'default'
    ];

    public $exclude = false; // set this true to skip adding this constant to the db
}

