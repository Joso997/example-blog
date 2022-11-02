<?php

namespace App\Http\Controllers;

use App\Services\CyberInterface\FormComponents\AlertComponent;
use App\Services\CyberInterface\FormComponents\CheckBoxComponent;
use App\Services\CyberInterface\FormComponents\DataListComponent;
use App\Services\CyberInterface\FormComponents\SelectListComponent;
use App\Services\CyberInterface\FormComponents\FieldComponent;
use App\Services\CyberInterface\FormComponents\RadioComponent;
use App\Services\CyberInterface\FormComponents\SubmitComponent;
use App\Services\CyberInterface\Helpers\ActionsEnum;

class RegionController extends Controller
{
    public function resolveRegion(){
        return response(
            [
                (new FieldComponent("Name", "Title"))->get(),
                (new DataListComponent("List", "List", ["koko", "bil", "test"]))->get(),
                (new RadioComponent("Radio", "Radio", ["ikik", "bil", "test"]))->get(),
                (new SelectListComponent("Select", "Select", ["koko", "bil", "test"]))->get(),
                (new FieldComponent("Description", "Description"))->get(),
                (new FieldComponent('Amount', 'Amount', 'Number'))->changeDefaultAction(ActionsEnum::InsertNumber)->get(),
                (new FieldComponent('Amount', 'Amount', 'Range'))->changeDefaultAction(ActionsEnum::InsertNumber)->get(),
                (new CheckBoxComponent('Checkbox', 'Checkbox', 'checkbox'))->get(),
                (new AlertComponent("Alert", "alert", "I am alert", "alert alert-primary"))->get(),
                (new SubmitComponent("Submit", "SubmitButton", 'btn btn-primary'))->get()


            ]
        );
    }
}
