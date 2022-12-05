<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {

        return response()->json(Permission::orderBy("lft","ASC")->get());
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
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //var_dump($request->all());
        var_dump($request->get('1'));
        /*$tempUuid = null;
        $tempParentUuid = null;
        foreach($request->all() as $temp){
            $id = $temp['id'];
            $tempUuid[] = Str::orderedUuid()->toString();
            if($temp['parent_id'] === $id){
                $tempParentUuid[] = $tempUuid[$tempUuid.length];
            }
        }*/
        $i = 0;

        foreach($request->all() as $temp){
            $permission = new Permission();
            $permission->id = $temp['id'];
            $permission->name = $temp['name'];
            if(array_key_exists('parent_id', $temp)){
                $permission->parent_id = $temp['parent_id'];
            }
            $permission->lft = $temp['lft'];
            $permission->rgt = $temp['rgt'];
            $permission->save();
        }
        return response(['success',
            'Save was success',
            'Data successfuly saved '
        ],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return Response
     */
    public function show(string $tree_id)
    {
        return response(Permission::where('tree_id', $tree_id)->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return Response
     */
    public function update(Request $request, Permission $permission)
    {

        $permission->name = $request->name;
        $permission->lft = $request->lft;
        $permission->rgt = $request->rgt;
        $permission->update();
        return $request->name;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return Response
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
