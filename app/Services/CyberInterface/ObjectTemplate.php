<?php

namespace App\Services\CyberInterface;

use App\Services\CyberInterface\Helpers\ActionsEnum;
use App\Services\CyberInterface\Helpers\ObjectsEnum;
use App\Services\CyberInterface\Helpers\RegionsEnum;
use App\Services\CyberInterface\Helpers\StatsEnum;
use App\Services\CyberInterface\Helpers\SubObjectsEnum;

class ObjectTemplate
{
    public RegionsEnum $Region;
    public ObjectsEnum $ObjectEnum;
    public SubObjectsEnum $SubObjectEnum;
    public ActionsEnum $ActionEnum;
    public array $Stats;

    public function __construct(RegionsEnum $region, ObjectsEnum $objectEnum, SubObjectsEnum $subObjectEnum, ActionsEnum $actionEnum, array $stats){
        $this->Region = $region;
        $this->ObjectEnum = $objectEnum;
        $this->SubObjectEnum = $subObjectEnum;
        $this->ActionEnum = $actionEnum;
        $this->Stats = $stats;
    }

}
