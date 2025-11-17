<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case SKIPPED = 'skipped';
    case FAILED = 'failed';
    case INCOMPLETE = 'incomplete';
    case OVERDUE = 'overdue';
}
