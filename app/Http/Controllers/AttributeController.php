<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Group;
use App\Services\CyberInterface\FormComponents\AlertComponent;
use App\Services\CyberInterface\FormComponents\CheckBoxComponent;
use App\Services\CyberInterface\FormComponents\DataListComponent;
use App\Services\CyberInterface\FormComponents\FieldComponent;
use App\Services\CyberInterface\FormComponents\FieldViewComponent;
use App\Services\CyberInterface\FormComponents\RadioComponent;
use App\Services\CyberInterface\FormComponents\SelectListComponent;
use App\Services\CyberInterface\FormComponents\SubmitComponent;
use App\Services\CyberInterface\Helpers\ActionsEnum;
use App\Services\CyberInterface\Helpers\Form\ElementTypeEnum;
use App\Services\CyberInterface\Helpers\Form\FormObjects;
use App\Services\CyberInterface\Helpers\ObjectsEnum;
use App\Services\CyberInterface\Helpers\RegionsEnum;
use App\Services\CyberInterface\Helpers\StatsEnum;
use App\Services\CyberInterface\Helpers\SubObjectsEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $response = [];
        $groups = Attribute::orderBy('created_at', 'asc')->get();
        foreach ($groups as $group){
            $response[] = [
                (new FieldViewComponent("Name", "name", $group->id))->setOptional($group->name)->get(),
            ];
        }

        return response()->json($response);
    }

    public function filterIndex(string $parentId): JsonResponse{
        $response = [];
        $groups = Attribute::where('group', $parentId)->orderBy('created_at', 'asc')->get();
        foreach ($groups as $group){
            $response[] = [
                (new FieldViewComponent("Name", "name", $group->id))->setOptional($group->name)->get(),
            ];
        }

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function resolveRegionForm(): Response
    {
        return response(
            [
                (new FieldComponent("Name", "name"))->setOptional(null,"","Attribute name", "Name of an attribute.")->get(),
                (new SelectListComponent('Field Type', 'fieldType', FormObjects::getObjectsForAttributes()))
                    ->changeDefaultSubObjectType(SubObjectsEnum::Middle)->changeDefaultAction(ActionsEnum::InsertClick)->setOptional(null,"","","Field type of an attribute.")->get()
            ]
        );
    }

    public function resolveUserChoiceForm(int $option) : Response
    {
        $arr = null;
        $arr[] = (new FieldComponent('Label Name', 'label'))->setOptional(null, '','Label name',"Label name of an attribute.")->get();
        $arr[] = (new FieldComponent('Bootstrap Class', 'design'))->setOptional(null,'', 'test', "Bootstrap class of an attribute.")->get();
        switch ($option){
            case ObjectsEnum::Field->value:
                $arr[] = (new SelectListComponent('Field Type', 'elementType', array_map(
                    fn (ElementTypeEnum $term) => ['id' => $term->name, 'name' => $term->value],
                    ElementTypeEnum::cases()
                )))->get();
                $arr[] = (new FieldComponent('Placeholder', 'placeholder'))->get();
                break;
            case ObjectsEnum::SelectList->value:
                $arr[] = (new SubmitComponent('Add Value', 'add', 'btn btn-outline-success mb-2'))->get();
                break;
        }
        return response($arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $name = null;
        $group = null;
        $id = null;
        $attributeType = null;
        $label = null;
        $elementType = null;
        $design = null;
        $placeholder = null;
        $values = null;
        $attributeFinalize = null;
        foreach ($request->all() as $stat ){
            if(array_key_exists(StatsEnum::Id->value, $stat['Stats'])){
                if(!is_null($stat['Stats'][StatsEnum::Id->value]))
                    $id = $stat['Stats'][StatsEnum::Id->value]["Data"];
            }
            if(array_key_exists(StatsEnum::Label->value, $stat['Stats'])){
                if(!is_null($stat['Stats'][StatsEnum::Label->value]))
                    if($stat['Stats'][StatsEnum::Label->value]["Data"] == "Value")
                        $values[] = $stat['Stats'][StatsEnum::Value->value]["Data"];
            }
            switch($stat['Stats'][StatsEnum::Tag->value]["Data"]){
                case 'name':
                    $name = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                case 'group':
                    $group = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                case 'attributeType':
                    $attributeType = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                case 'label':
                    $label = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                case 'elementType':
                    $elementType = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    breaK;
                case 'design':
                    $design = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    breaK;
                case 'placeholder':
                    $placeholder = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    breaK;
                default:
                    break;
            }

        }
        switch ($attributeType){
            case ObjectsEnum::Field->value:
                $attributeFinalize = (new FieldComponent($label,$label))->setOptional(null,$design,$placeholder,$elementType)->get();
                break;
            case ObjectsEnum::CheckBox->value:
                $attributeFinalize = (new CheckBoxComponent($label,$label))->setOptional(null,$design,$placeholder)->get();
                break;
            case ObjectsEnum::Radio->value:
                $attributeFinalize = (new RadioComponent($label,$label,$values))->setOptional(null,$design,$placeholder,$values)->get();
                break;
            case ObjectsEnum::SelectList->value:
                $attributeFinalize = (new SelectListComponent($label,$label,$values))->setOptional(null,$design,$placeholder,$values)->get();
                break;
        }
        $attribute = new Attribute();
        $attribute->id = $id;
        $attribute->name = $name;
        $attribute->group = $group;
        $attribute->attribute_finalize = $attributeFinalize;
        $attribute->attribute_values = $request->all();
        $attribute->save();
        return response()->json(['id'=> $attribute->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attribute  $attributes
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        if($id === '-1'){
            return response(Str::orderedUuid()->toString());
        }
        $attribute = Attribute::find($id);
        return response(
            $attribute->attribute_values
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attribute  $attributes
     * @return \Illuminate\Http\Response
     */
    public function edit(Attribute $attributes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attribute  $attributes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attribute $attributes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attribute  $attributes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attributes)
    {
        //
    }
}
