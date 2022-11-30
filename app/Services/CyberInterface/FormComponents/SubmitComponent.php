<?php

namespace App\Services\CyberInterface\FormComponents;

use App\Services\CyberInterface\Helpers\ActionsEnum;
use App\Services\CyberInterface\Helpers\ObjectsEnum;
use App\Services\CyberInterface\Helpers\RegionsEnum;
use App\Services\CyberInterface\Helpers\StatsEnum;
use App\Services\CyberInterface\Helpers\SubObjectsEnum;

class SubmitComponent extends ComponentAbstract
{
    public function __construct(string $label, string $tag, string $design){
        $this->design = $design;
        parent::__construct($label, $tag,RegionsEnum::Form, ObjectsEnum::Button,  SubObjectsEnum::Middle, ActionsEnum::Click);
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
            StatsEnum::Design->value =>["Data" => $this->design],
            StatsEnum::Tag->value =>["Data" => $this->tag],
            StatsEnum::Id->value => ($this->id !== ''? ["Data" => $this->id]:["Data" => null])
        ];
    }
}
