<?php

namespace App\Enum;

enum StatusEnum : string
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in_progress';
    case READY_FOR_REVIEW = 'ready_for_review';
    case DONE = 'done';

    public function label(): string
    {
        return match($this) {
            self::TODO => 'ToDo',
            self::IN_PROGRESS => 'In Progress',
            self::READY_FOR_REVIEW => 'Ready For Review',
            self::DONE => 'Done',
        };
    }

}
