<?php

namespace App\Services\CyberInterface\Helpers;

enum RegionsEnum: int
{
    case Form = 0;
    case Table = 1;
    case TableColumn = 2;
    case TableRow = 3;
    case Show = 4;
    case Footer = 5;
}
