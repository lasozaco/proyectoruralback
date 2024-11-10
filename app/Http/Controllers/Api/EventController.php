<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $events = Event::paginate();

        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request): Event
    {
        return Event::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): Event
    {
        return $event;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, Event $event): Event
    {
        $event->update($request->validated());

        return $event;
    }

    public function destroy(Event $event): Response
    {
        $event->delete();

        return response()->noContent();
    }
}
