<?php

namespace App\Http\Controllers\Api;

use App\Models\Multimedia;
use Illuminate\Http\Request;
use App\Http\Requests\MultimediaRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\MultimediaResource;

class MultimediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $multimedia = Multimedia::paginate();

        return MultimediaResource::collection($multimedia);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MultimediaRequest $request): Multimedia
    {
        return Multimedia::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Multimedia $multimedia): Multimedia
    {
        return $multimedia;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MultimediaRequest $request, Multimedia $multimedia): Multimedia
    {
        $multimedia->update($request->validated());

        return $multimedia;
    }

    public function destroy(Multimedia $multimedia): Response
    {
        $multimedia->delete();

        return response()->noContent();
    }
}
