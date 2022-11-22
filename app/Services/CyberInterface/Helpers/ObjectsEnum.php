<?php

namespace App\Services\CyberInterface\Helpers;

enum ObjectsEnum: int
{
    case Row = 0;
    case Field = 1;
    case Button = 2;
    case Text = 3;
    case ShowResolve = 4;
    case Alert = 5;
    case CheckBox = 6;
    case DataList = 7;
    case SelectList = 8;
    case Radio = 9;
    case Column = 10;
    case ColumnButton = 11;
}
