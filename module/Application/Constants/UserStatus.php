<?php
namespace Module\Application\Constants;

class UserStatus {
    const ACTIVE    = 100401;
    const INACTIVE  = 100402;

    public $types = [
        self::ACTIVE => 'info',
        self::INACTIVE => 'default'
    ];
}



