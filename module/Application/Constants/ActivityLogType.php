<?php
namespace Module\Application\Constants;

class ActivityLogType {
    const INFO          = 100101;
    const WARNING       = 100102;
    const DANGER        = 100103;
    const SUCCESS       = 100104;
    const DEFAULT       = 100105;

    public $types = [
        self::INFO      => 'info',
        self::WARNING   => 'warning',
        self::DANGER    => 'danger',
        self::SUCCESS   => 'success',
        self::DEFAULT   => 'default'
    ];
}