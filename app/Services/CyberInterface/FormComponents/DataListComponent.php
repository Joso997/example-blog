<?php

namespace App\Services\CyberInterface\FormComponents;

use App\Services\CyberInterface\Helpers\ActionsEnum;
use App\Services\CyberInterface\Helpers\ObjectsEnum;
use App\Services\CyberInterface\Helpers\RegionsEnum;
use App\Services\CyberInterface\Helpers\StatsEnum;
use App\Services\CyberInterface\Helpers\SubObjectsEnum;

class DataListComponent extends ComponentAbstract
{
    private array $itemList = [];

    public function __construct(string $label, string $tag, array $itemList){
        $this->itemList = $itemList;
        parent::__construct($label, $tag, RegionsEnum::Form, ObjectsEnum::DataList, SubObjectsEnum::ParentObject, ActionsEnum::Insert);
    }

    public function setOptional(string $value = null, string $design = "", string $placeholder = "", string $tooltip = "", array $itemList = []): static
    {
        $this->value = $value;
        $this->design = $design;
        $this->placeholder = $placeholder;
        $this->tooltip = $tooltip;
        $this->itemList = $itemList;
        return $this;
    }

    protected function setStats() : array{
        return [
            StatsEnum::Label->value =>["Data" => $this->label],
            StatsEnum::Value->value => ["Data" => $this->value],
            StatsEnum::Design->value =>["Data" => $this->design],
            StatsEnum::Tag->value =>["Data" => $this->tag],
            StatsEnum::Placeholder->value =>["Data" => $this->placeholder],
            StatsEnum::Tooltip->value =>["Data" => $this->tooltip],
            StatsEnum::ItemList->value =>["Data" => json_encode($this->itemList)]
        ];
    }
}
