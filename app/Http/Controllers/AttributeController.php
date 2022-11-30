<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Group;
use App\Services\CyberInterface\FormComponents\AlertComponent;
use App\Services\CyberInterface\FormComponents\FieldComponent;
use App\Services\CyberInterface\FormComponents\FieldViewComponent;
use App\Services\CyberInterface\FormComponents\SubmitComponent;
use App\Services\CyberInterface\Helpers\StatsEnum;
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
                (new FieldComponent("Name", "name"))->get(),
            ]
        );
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
        foreach ($request->all() as $stat ){
            $id = $stat['Stats'][StatsEnum::Id->value]["Data"];
            switch($stat['Stats'][StatsEnum::Tag->value]["Data"]){
                case 'name':
                    $name = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                case 'group':
                    $group = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                default:
                    break;
            }
        }
        $attribute = new Attribute();
        $attribute->id = $id;
        $attribute->name = $name;
        $attribute->group = $group;
        $attribute->attribute_values = json_encode('');
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
            [
                (new FieldComponent("Name", "name"))->withId($attribute->id)->setOptional($attribute->name)->get(),
                (new AlertComponent("Group", 'group', $attribute->group, ''))->withId($attribute->id)->get()
            ]
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
