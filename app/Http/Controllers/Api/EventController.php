<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Models\Institution;
use App\Models\Multimedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Js;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $institution = (new \App\Models\Institution)->where('user_id', Auth::user()->id)->first();

        $events = (new \App\Models\Event)->where('institution_id', $institution->id)->get();

        foreach ($events as $event) {
            $event->multimedia = Multimedia::where('event_id', $event->id)->get();
        }

        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        return response()->json([
            Event::create($request->validated())
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $institution = (new \App\Models\Institution)->where('id', $id)->first();

        $events = (new \App\Models\Event)->where('institution_id', $institution->id)->get();
        $events->institution = $institution;
        foreach ($events as $event) {
            $event->institution = $institution;
            unset($event->institution_id);
            unset($event->created_at);
            unset($event->updated_at);
            foreach ($event as $ev) {
                $event->multimedia = Multimedia::where('event_id', $event->id)->get();
                unset($event->multimedia->event_id);
            }
        }

        return response()->json($events);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, Event $event): Event
    {
        $event->update($request->validated());

        return $event;
    }

    public function destroy(Int $id)
    {
        return response()->json([
            Event::findOrFail($id)->delete()
        ], Response::HTTP_OK);
    }
}
