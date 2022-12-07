<?php

namespace App\Services\CyberInterface\Helpers\Form;

enum ElementTypeEnum : string
{
    case hidden = 'hidden';
    case text = 'text';
    case number = 'number';
    case range = 'range';
    case email = 'email';
    case password = 'password';
    case time = 'time';
    case datetimeLocal = 'datetime-local';
    case month = 'month';
    case week = 'week';
    case tel = 'tel';
    case url = 'url';
    case color = 'color';
}
