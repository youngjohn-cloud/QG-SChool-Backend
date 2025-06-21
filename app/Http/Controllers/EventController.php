<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Qg_Class;
use Illuminate\Http\Request;

class EventController extends Controller
{
    //fetch all events
    public function EventsList()
    {
        $events = Event::with('class')->get();
        return response()->json($events, 200);
    }


    // CRUD PROCESS FOR THE EVENT MODELS
    public function createEvent(Request $request)
    {
        try {
            $event = new Event([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
            ]);
            $event->class_id = $request->input('class_id');
            $event->load('class');
            $event->save();
            return response()->json($event, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'error creating Event', 'error' => $th->getMessage()], 500);
        }
    }
    public function updateEvent($id, Request $request)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => "Event not found"], 404);
        }
        try {
            $event->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
                'class_id' => $request->input('class_id'),
            ]);
            return response()->json(null, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Updating Event failed', 'error' => $th->getMessage()]);
        }
    }
    public function deleteEvent($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $event->delete();
        return response(null, 200);
    }
    public function getEventRelatedData()
    {
        $relatedData = Qg_Class::select('id', 'name')->get();
        return response()->json($relatedData, 200);
    }
}
