<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class StatusColor
{
    const JOB_STATUS_PENDING = 'pending';
    const JOB_STATUS_RUNNING = 'running';
    const JOB_STATUS_FINISHED = 'finished';
    const JOB_STATUS_FINISHED_WITH_ERRORS = 'finished_with_errors';
    const JOB_STATUS_FAILED = 'failed';

    private static $colorsByStatuses = [
        self::JOB_STATUS_FAILED => '#ffabaf',
        self::JOB_STATUS_FINISHED => '#68de7c',
        self::JOB_STATUS_RUNNING => '#c5d9ed',
        self::JOB_STATUS_PENDING => '#f5e6ab',
        self::JOB_STATUS_FINISHED_WITH_ERRORS => '#b8e6bf'
    ];

    public static function getColorByStatusName($status)
    {
        if (!empty(self::$colorsByStatuses[$status])) {
            return self::$colorsByStatuses[$status];
        }

        return '#f0f0f1';
    }
}