<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Support\Js;

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
    public function show(int $id): JsonResponse
    {
        $event = Event::where('institution_id', $id)->first();
        $event->institution=$event->institution;
        unset($event->institution_id);
        unset($event->created_at);
        unset($event->updated_at);
        unset($event->institution->user_id);

        return response()->json($event);
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
