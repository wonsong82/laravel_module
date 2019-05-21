<?php
namespace Module\Company\Constants;

class CompanyUserStatus {
    const ACTIVE    = 200201;
    const INACTIVE  = 200202;

    public $types = [
        self::ACTIVE => 'info',
        self::INACTIVE => 'danger'
    ];
}

