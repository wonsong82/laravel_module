<?php
namespace Module\Company\Constants;

class CompanyStatus {
    const ACTIVE    = 200101;
    const INACTIVE  = 200102;

    public $types = [
        self::ACTIVE => 'info',
        self::INACTIVE => 'danger'
    ];
}

