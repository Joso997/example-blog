<?php

namespace App\Services\CyberInterface\FormComponents;

use App\Services\CyberInterface\Helpers\ActionsEnum;
use App\Services\CyberInterface\Helpers\ObjectsEnum;
use App\Services\CyberInterface\Helpers\RegionsEnum;
use App\Services\CyberInterface\Helpers\SubObjectsEnum;

abstract class ComponentAbstract
{
    protected string $label = "";
    protected string $tag = "";
    protected ?string $value = null;
    protected string $design = "";
    protected string $placeholder = "";
    protected RegionsEnum $region;
    protected ObjectsEnum $objectType;
    protected SubObjectsEnum $subObjectType;
    protected ActionsEnum $action;

    abstract public function setOptional(string $value = null, string $design = "", string $placeholder = ""): static;
    abstract protected function setStats() : array;

    public function __construct(string $label, string $tag, RegionsEnum $region, ObjectsEnum $objectType, SubObjectsEnum $subObjectType, ActionsEnum $action){
        $this->label = $label;
        $this->tag = $tag;
        $this->region = $region;
        $this->objectType = $objectType;
        $this->subObjectType = $subObjectType;
        $this->action = $action;
    }

    public function changeDefaultRegion(RegionsEnum $region): static
    {
        $this->region = $region;
        return $this;
    }

    public function changeDefaultObjectType(ObjectsEnum $objectType): static
    {
        $this->objectType = $objectType;
        return $this;
    }

    public function changeDefaultSubObjectType(SubObjectsEnum $subObjectType): static
    {
        $this->subObjectType = $subObjectType;
        return $this;
    }

    public function changeDefaultAction(ActionsEnum $actionType): static
    {
        $this->action = $actionType;
        return $this;
    }

    public function changeDefaultIndicators(RegionsEnum $region, ObjectsEnum $objectType, SubObjectsEnum $subObjectType, ActionsEnum $actionType): static
    {
        $this->region = $region;
        $this->objectType = $objectType;
        $this->subObjectType = $subObjectType;
        $this->action = $actionType;
        return $this;
    }

    public function get() : array{
        return [
            "Stats" => $this->setStats(),
            "Region" => $this->region,
            "ObjectEnum" => $this->objectType,
            "SubObjectEnum" => $this->subObjectType,
            "ActionEnum" => $this->action
        ];
    }
}
