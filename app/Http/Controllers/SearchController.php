<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Entity;
use App\Models\Group;
use App\Services\CyberInterface\FormComponents\DataListComponent;
use App\Services\CyberInterface\FormComponents\FieldComponent;
use App\Services\CyberInterface\FormComponents\FieldViewComponent;
use App\Services\CyberInterface\FormComponents\SubmitComponent;
use App\Services\CyberInterface\Helpers\StatsEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function group_division(string $title): JsonResponse
    {
        $responseGroup = [];
        $responseDivision = [];
        $responseDevice = [];
        $responseFinal = [];
        $groups = null;
        $divisions = null;
        $devices = null;
        $titles = explode('"', $title);
        $deviceTitle = "";
        foreach ($titles as $value){

            if(str_contains($value, "group:")){
                $temp = explode(':', $value);
                $groups = Group::where('name', 'LIKE', last($temp))->orderBy('created_at', 'asc')->get()->pluck('name', 'id');
                //var_dump($groups);
            }
            if(str_contains($value, "division:")){
                $divisions = Division::where('name','LIKE', $value)->orderBy('created_at','asc')->get()->pluck('name', 'id');
            }
        }
        foreach ($titles as $value){
            if(str_contains($value, "device:")){
                $devices=Entity::where('group','')->where('divisions', '')->where('code','LIKE', $deviceTitle/*mozda pretvorit u base64*/)->orderBy('created_at','asc')->get()->pluck('code', 'id');
                dd($devices);
            }
        }
        foreach($groups as $key => $value){
            $responseGroup[] = (new FieldViewComponent('Group', 'name', $key))->setOptional($value)->get();
        }

        /*foreach ($titles as  $key => $value){
            if(str_contains($value, "group")){
                $responseGroup[] = (new FieldViewComponent('Device', 'name', $key))->setOptional($value)->get();
            }
            if(str_contains($value, "division")){
                $responseDivision[] = (new FieldViewComponent('Device', 'name', $key))->setOptional($value)->get();
            }
            if(str_contains($value, "device")){
                $responseDevice[] = (new FieldViewComponent('Device', 'name', $key))->setOptional($value)->get();
            }
        }*/

        if(str_contains($title, 'group:')){
            $responseFinal[] = $responseGroup;
        }
        if(str_contains($title, 'division:')){
            $responseFinal[]=$responseDivision;
        }
        if(str_contains($title, 'device:')){
            $responseFinal[]=$responseDevice;
        }
        return response()->json($responseFinal);
    }


    public function index(string $title): JsonResponse
    {
        $response = null;
        $entity=Entity::orderBy('created_at','asc')->get()->pluck('attribute_values');
        foreach ($entity as $entities){
            $inResponse = null;
            foreach ($entities as $item){
                $item = (object)$item;
                if($item->Stats[StatsEnum::Value->value]['Data'] === $title){
                    $inResponse[] = $item;
                }
            }
            $response[]=$inResponse;
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $name = null;
        $id = null;
        foreach ($request->all() as $stat ){
            $id = $stat['Stats'][StatsEnum::Id->value]["Data"];
            switch($stat['Stats'][StatsEnum::Tag->value]["Data"]){
                case 'name':
                    $name = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                default:
                    break;
            }
        }
        $group = new Group();
        $group->id = $id;
        $group->name = $name;
        $group->save();
        return response()->json(['id'=> $group->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(string $id): Response
    {
        if($id === '-1'){
            return response(Str::orderedUuid()->toString());
        }
        $group = Group::find($id);
        return response(
            [
                (new FieldComponent("Name", "name"))->withId($group->id)->setOptional($group->name)->get(),
                (new SubmitComponent("Add Attribute", "SubmitButton", 'btn btn-primary mb-3'))->withId($group->id)->get()
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id) : Response
    {
        $group = Group::findorFail($id); //searching for object in database using ID
        if($group->delete()){ //deletes the object
            return response('deleted successfully'); //shows a message when the delete operation was successful.
        }
        return response('failed', 122);
    }
}
