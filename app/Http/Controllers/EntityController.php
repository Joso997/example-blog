<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Entity;
use App\Models\Group;
use App\Services\CyberInterface\Helpers\StatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Uuid;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Entity::orderBy('created_at', 'asc')->get()->pluck('attribute_values'));
    }
    public function filter(string $title) : Response
    {
        $result = null;
        $temp = [];
        $data = Entity::orderBy('created_at', 'asc')->get()->pluck('json');
        foreach ($data as $stat) {
            array_push($temp, $stat);
        }
        for ($i = 0; $i < count($temp); $i++) {
            for ($j = 0; $j < count($temp[$i]); $j++) {
                for ($k = 0; $k < count($temp[$i][$j]["Stats"]); $k++) {
                    dd($temp[$i][$j]["Stats"][$k]["Data"]);
                }
            }

            if ($stat["Stats"][StatsEnum::Tag]["Data"] === "Title" || $stat["Stats"][StatsEnum::Tag]["Data"] === "Name") {
                if (strtolower($title) === strtolower($stat["Stats"][StatsEnum::Value]["Data"])) {
                    $result = $stat;
                }
            }
            return response($result);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $code = null;
        $division = null;
        $group = null;
        $id = null;
        foreach ($request->all() as $stat ){
            $id = $stat['Stats'][StatsEnum::Id->value]["Data"];
            switch($stat['Stats'][StatsEnum::Tag->value]["Data"]){
                case 'code':
                    $code = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                case 'division':
                    $division = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                case 'group':
                    $group = $stat['Stats'][StatsEnum::Value->value]["Data"];
                    break;
                default:
                    break;
            }
        }
        $entity = new Entity();
        $entity->id = $id;
        $entity->code = $code;
        $entity->divisions = json_encode(Division::where('name', $division)->first()->id);
        $entity->group = Group::where('name', $group)->first()->id;
        $entity->attribute_values = $request->all();
        $entity->save();
        return response()->json(['id'=> $entity->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(string $id): Response
    {
        if($id === '-1'){
            return response(Str::orderedUuid()->toString());
        }
        return response(Entity::find($id)->attribute_values);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function update(Request $request, string $id): Response
    {
        $entity = Entity::findorFail($id);
        $entity->json = $request->all();
        $entity->save();
        return response($entity);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return Response
     */
    public function destroy(string $id): Response
    {
        $entity = Entity::findorFail($id); //searching for object in database using ID
        if($entity->delete()){ //deletes the object
            return response('deleted successfully'); //shows a message when the delete operation was successful.
        }
        return response('failed', 122);
    }
}
