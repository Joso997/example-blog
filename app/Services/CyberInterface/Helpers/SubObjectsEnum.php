<?php

namespace App\Services\CyberInterface\Helpers;

enum SubObjectsEnum: int
{
    case ParentObject = 0;
    case Middle = 1;
    case Left = 2;
    case Right = 3;
}
