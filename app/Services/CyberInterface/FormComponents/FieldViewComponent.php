<?php

namespace App\Services\CyberInterface\FormComponents;

use App\Services\CyberInterface\Helpers\ActionsEnum;
use App\Services\CyberInterface\Helpers\ObjectsEnum;
use App\Services\CyberInterface\Helpers\RegionsEnum;
use App\Services\CyberInterface\Helpers\StatsEnum;
use App\Services\CyberInterface\Helpers\SubObjectsEnum;

class FieldViewComponent extends ComponentAbstract
{
    public function __construct(string $label, string $tag, string $id){
        $this->id = $id;
        parent::__construct($label, $tag,RegionsEnum::Table, ObjectsEnum::Field,  SubObjectsEnum::ParentObject, ActionsEnum::None);
    }

    public function setOptional(string $value = null, string $design = "", string $placeholder = ""): static
    {
        $this->value = $value;
        $this->design = $design;
        $this->placeholder = $placeholder;
        return $this;
    }

    protected function setStats() : array{
        return [
            StatsEnum::Label->value =>["Data" => $this->label],
            StatsEnum::Value->value => ["Data" => $this->value],
            StatsEnum::Tag->value =>["Data" => $this->tag],
            StatsEnum::Id->value =>["Data" => $this->id]
        ];
    }
}
