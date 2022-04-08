<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        return response()->json($event);
    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(['status' => $id . ' Deleted successfully'], 200);
    }

    public function store(Request $request)
    {
        $event = new Event();
        $event->name = $request->name;
        $event->description = $request->description;
        $event->location = $request->location;
        $event->artist = $request->artist;
        $event->event_date = $request->event_date;
        $event->price = $request->price;
        if ($event->save()) {
            return response()->json(['status' => 'Created', 'result' => $event]);
        } else {
            return response()->json(['status' => 'Error guardant']);
        }
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        if ($event->update($request->all())) {
            return response()->json(['status' => 'Modified successfully', 'result' => $event]);
        } else {
            return response()->json(['status' => 'Error while modifying']);
        }
    }
}
