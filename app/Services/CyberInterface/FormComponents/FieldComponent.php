<?php

namespace App\Services\CyberInterface\FormComponents;

use App\Services\CyberInterface\Helpers\ActionsEnum;
use App\Services\CyberInterface\Helpers\ObjectsEnum;
use App\Services\CyberInterface\Helpers\RegionsEnum;
use App\Services\CyberInterface\Helpers\StatsEnum;
use App\Services\CyberInterface\Helpers\SubObjectsEnum;

class FieldComponent extends ComponentAbstract
{

    private string $elementType = "";

    public function __construct(string $label, string $tag, $elementType = ""){
        $this->elementType = $elementType;
        parent::__construct($label, $tag,RegionsEnum::Form, ObjectsEnum::Field,  SubObjectsEnum::ParentObject, ActionsEnum::Insert);
    }

    public function setOptional(string $value = null, string $design = "", string $placeholder = "", string $elementType = ""): static
    {
        $this->value = $value;
        $this->design = $design;
        $this->placeholder = $placeholder;
        $this->elementType = $elementType;
        return $this;
    }

    protected function setStats() : array{
        return [
            StatsEnum::Label->value =>["Data" => $this->label],
            StatsEnum::Value->value => ["Data" => $this->value],
            StatsEnum::Design->value =>["Data" => $this->design],
            StatsEnum::Tag->value =>["Data" => $this->tag],
            StatsEnum::ElementType->value =>["Data" => $this->elementType],
            StatsEnum::Placeholder->value =>["Data" => $this->placeholder],
            StatsEnum::Id->value => ($this->id !== ''? ["Data" => $this->id]:["Data" => null])
        ];
    }
}
