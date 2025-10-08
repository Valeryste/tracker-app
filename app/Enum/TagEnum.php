<?php

namespace App\Enum;

enum TagEnum : string
{
    case TECH_ISSUE = 'tech_issue';
    case TECH_QUESTION = 'tech_question';
    case FATAL_ERROR = 'fatal_error';
    case SALES_QUESTION = 'sales_question';
    case FEATURE_REQUEST = 'feature_request';

    public function label(): string
    {
        return match($this) {
            self::TECH_ISSUE => 'Tech Issue',
            self::TECH_QUESTION => 'Tech Question',
            self::FATAL_ERROR => 'Fatal Error',
            self::SALES_QUESTION => 'Sales Question',
            self::FEATURE_REQUEST => 'Feature Request',
        };
    }
}
