<?php

namespace App\Services\CyberInterface\Helpers\Form;

use App\Services\CyberInterface\Helpers\ObjectsEnum;

class FormObjects
{
    public static function getObjectsForAttributes (): array
    {
        return [
            ['id' => ObjectsEnum::Field->value, 'name' => ObjectsEnum::Field->name],
            ['id' => ObjectsEnum::SelectList->value, 'name' => ObjectsEnum::SelectList->name],
            ['id' => ObjectsEnum::Radio->value, 'name' => ObjectsEnum::Radio->name],
            ['id' => ObjectsEnum::CheckBox->value, 'name' => ObjectsEnum::CheckBox->name],
            ['id' => ObjectsEnum::Text->value, 'name' => ObjectsEnum::Text->name],
        ];
    }
}
