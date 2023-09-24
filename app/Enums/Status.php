<?php

declare(strict_types=1);

namespace App\Enums;

enum Status: string
{
    case TO_DO = 'to_do';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';
}
