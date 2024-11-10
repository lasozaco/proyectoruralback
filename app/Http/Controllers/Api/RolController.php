<?php

namespace App\Http\Controllers\Api;

use App\Models\Rol;
use Illuminate\Http\Request;
use App\Http\Requests\RolRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\RolResource;
use Ramsey\Uuid\Type\Integer;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rols = Rol::paginate();

        return RolResource::collection($rols);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RolRequest $request): Rol
    {
        return Rol::create($request->validated());
    }

    /**
     * Display the specified resource.
     * @param Integer $id
     * @return Rol
     */
    public function show(Integer $id): Rol
    {
        $rol = Rol::find($id);
        return $rol;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RolRequest $request, Rol $rol): Rol
    {
        $rol->update($request->validated());

        return $rol;
    }

    public function destroy(Rol $rol): Response
    {
        $rol->delete();

        return response()->noContent();
    }
}
