<?php

namespace App\Services\CyberInterface\Helpers;

enum ActionsEnum: int
{
    case None = 0;
    case Click = 1;
    case Insert = 2;
    case InsertUrl = 3;
    case InsertClick = 4;
    case InsertNumber = 5;
    case Check = 6;
}
