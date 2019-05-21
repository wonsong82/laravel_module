<?php
namespace Module\Company\Constants;

class CustomizationType {
    const SERIAL_CODE       = 200401;
    const REPORT            = 200402;

    public $types = [
        self::SERIAL_CODE => 'default',
        self::SERIAL_CODE => 'default'
    ];

    public $exclude = false; // set this true to skip adding this constant to the db
}

