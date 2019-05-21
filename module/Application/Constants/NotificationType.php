<?php
namespace Module\Application\Constants;

class NotificationType {
    const INFO          = 100301;
    const WARNING       = 100302;
    const DANGER        = 100303;
    const SUCCESS       = 100304;

    public $types = [
        self::INFO => 'default',
        self::WARNING => 'warning',
        self::DANGER => 'danger',
        self::SUCCESS => 'success'
    ];
}