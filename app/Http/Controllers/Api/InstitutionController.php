<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateInstitutionRequest;
use App\Models\Institution;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\InstitutionRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\InstitutionResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $institutions = Institution::paginate();
        foreach ($institutions as $institution) {
            $institution->user = User::find($institution->user_id);
            $institution->user->rol = Rol::find($institution->user->role_id)->name;
            unset($institution->user->role_id);
            unset($institution->user->id);
            unset($institution->user_id);
        }

        return InstitutionResource::collection($institutions);
    }

    public function indexPublic()
    {
        $institutions = Institution::paginate();
        foreach ($institutions as $institution) {
            unset($institution->user_id);
        }

        return InstitutionResource::collection($institutions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InstitutionRequest $request): JsonResponse
    {
        return response()->json([
            Institution::create($request->validated())
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $institution = Institution::findOrFail($id);
        $institution->user = User::find($institution->user_id);
        $institution->user->rol = Rol::find($institution->user->role_id)->name;
        unset($institution->user->role_id);
        unset($institution->user->id);
        unset($institution->user_id);

        return response()->json($institution, ResponseAlias::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstitutionRequest $request, Institution $institution): Institution
    {
        $institution->update($request->validated());
        $institution->user = User::find($institution->user_id);
        $institution->user->rol = Rol::find($institution->user->role_id)->name;
        unset($institution->user->role_id);
        unset($institution->user->id);
        unset($institution->user_id);

        return $institution;
    }

}
