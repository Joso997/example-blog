<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Group;
use App\Models\Permission;
use App\Services\CyberInterface\FormComponents\ButtonWithDataComponent;
use App\Services\CyberInterface\FormComponents\DataListComponent;
use App\Services\CyberInterface\FormComponents\FieldComponent;
use App\Services\CyberInterface\FormComponents\FieldViewComponent;
use App\Services\CyberInterface\FormComponents\SelectButtonComponent;
use App\Services\CyberInterface\FormComponents\SubmitComponent;
use App\Services\CyberInterface\Helpers\StatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $response = [];
        $entities = Division::orderBy('created_at', 'asc')->get();
        foreach ($entities as $entity){
            $response[] = [
                (new FieldViewComponent("Name", "name", $entity->id))->setOptional($entity->name)->get(),
                // (new FieldViewComponent("Belong", "belong", $entity->id))->setOptional($entity->belongings)->get(),
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
                (new ButtonWithDataComponent('Add Permission', 'add', 'btn btn-outline-success mb-2', array_map(
                    fn (array $term) => ['id' => $term['id'], 'name' => $term['name']],
                    Permission::all()->toArray()
                )))->get()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $name = null;
        $id = null;
        $permissionId = null;
        foreach ($request->all() as $stat ){
            if(array_key_exists(StatsEnum::Id->value, $stat['Stats']))
                if(!is_null($stat['Stats'][StatsEnum::Id->value]))
                    $id = $stat['Stats'][StatsEnum::Id->value]["Data"];
            switch($stat['Stats'][StatsEnum::Tag->value]["Data"]){
                case 'name':
                    $name = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                default:
                    break;
            }
            switch($stat['Stats'][StatsEnum::Label->value]["Data"]){
                case 'Permission':
                    $permissionId[] = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                default:
                    break;
            }
        }
        $division = new Division();
        $division->id = $id;
        $division->name = $name;
        $division->belongings = $permissionId;
        $division->save();
        return response()->json(['id'=> $division->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        if($id === '-1'){
            return response(Str::orderedUuid()->toString());
        }
        $division = Division::find($id);
        $response = null;
        $response[] = (new FieldComponent("Name", "name"))->withId($division->id)->setOptional($division->name)->get();
        $response[] = (new ButtonWithDataComponent('Add Permission', 'add', 'btn btn-outline-success mb-2', array_map(
            fn (array $term) => ['id' => $term['id'], 'name' => $term['name']],
            Permission::all()->toArray()
        )))->withId($division->id)->get();
        if(!is_null($division->belongings)){
            foreach ($division->belongings as $belonging){
                $response[] = (new SelectButtonComponent('Permission', Str::random(6), $belonging, array_map(
                    fn (array $term) => ['id' => $term['id'], 'name' => $term['name']],
                    Permission::all()->toArray()
                )))->withId($division->id)->get();
            }
        }
        return response($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function edit(Division $division)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        $name = null;
        $permissionId = null;
        foreach ($request->all() as $stat ){
            switch($stat['Stats'][StatsEnum::Tag->value]["Data"]){
                case 'name':
                    $name = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                default:
                    break;
            }
            switch($stat['Stats'][StatsEnum::Label->value]["Data"]){
                case 'Permission':
                    if(!is_null($stat['Stats'][StatsEnum::Value->value]["Data"]))
                        $permissionId[] = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                default:
                    break;
            }
        }
        $division = Division::findorFail($id);
        $division->name = $name;
        $division->belongings = $permissionId;
        $division->save();
        return response($division);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function destroy(Division $division)
    {
        //
    }
}
