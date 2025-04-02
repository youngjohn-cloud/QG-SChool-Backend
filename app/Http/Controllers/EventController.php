<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    //fetch all events
    public function EventsList()
    {
        $events = Event::with('class')->get();
        return response()->json($events, 200);
    }
}
