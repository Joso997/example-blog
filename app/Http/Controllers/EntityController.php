<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Entity::orderBy('created_at', 'asc')->get()->pluck('json'));
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
     * @return Response
     */
    public function store(Request $request): Response
    {
        /*$ttnController = new TTNController();
        $response = $ttnController->store($request);
        return response($response);*/
        $entity = new Entity();
        $entity->json = $request->all();
        $entity->save();
        return response();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        if($id == -1){
            if(Entity::withTrashed()->max('id') === null)
                return response(1);
            return response(Entity::withTrashed()->max('id')+1);
        }
        return response(Entity::findorFail($id)->json);
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
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id): Response
    {
        $entity = Entity::findorFail($id);
        $entity->json = $request->all();
        $entity->save();
        return response($entity);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $entity = Entity::findorFail($id); //searching for object in database using ID
        if($entity->delete()){ //deletes the object
            return response('deleted successfully'); //shows a message when the delete operation was successful.
        }
        return response('failed', 122);
    }
}
