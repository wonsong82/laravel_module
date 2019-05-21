<?php
namespace Module\Company\Constants;

class PriceType {
    const FIXED_PRICE           = 200501;
    const FIXED_SUPPLY_RATE     = 200502;
    const TIER_PRICE            = 200503;
    const TIER_SUPPLY_RATE      = 200504;

    public $types = [
        self::FIXED_PRICE => 'default',
        self::FIXED_SUPPLY_RATE => 'default',
        self::TIER_PRICE => 'default',
        self::TIER_SUPPLY_RATE => 'default'
    ];

    public $exclude = false; // set this true to skip adding this constant to the db
}

