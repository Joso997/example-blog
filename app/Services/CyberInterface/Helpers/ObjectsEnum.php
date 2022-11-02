<?php

namespace App\Services\CyberInterface\Helpers;

enum ObjectsEnum: int
{
    case Row = 0;
    case Field = 1;
    case Button = 2;
    case Text = 3;
    case ShowResolve = 4;
    case ContentToolkitObject = 5;
    case ModularText = 6;
    case Alert = 7;
    case CheckBox = 8;
    case DataList = 9;
    case SelectList = 10;
    case Radio = 11;
}
