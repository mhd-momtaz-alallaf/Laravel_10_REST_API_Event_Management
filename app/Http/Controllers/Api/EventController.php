<?php

namespace App\Http\Controllers\Api; // created by typeing:"php artisan make:controller Api/EventController --api"

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Event::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $event = Event::create([
            ...$request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]),
            'user_id' => 1
        ]);

        return $event;
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return $event;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $event->update(       
            $request->validate([
                'name' => 'sometimes|string|max:255', // sometimes: checks the constraints after sometimes only if the value in the input is present.
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ]),
        );

        return $event; // this will return the event itself. 


        // return $event->update(       // $event->update() returns boolean (0, 1) 
        //     $request->validate([
        //         'name' => 'sometimes|string|max:255', // sometimes: checks the constraints after sometimes only if the value in the input is present.
        //         'description' => 'nullable|string',
        //         'start_time' => 'sometimes|date',
        //         'end_time' => 'sometimes|date|after:start_time'
        //     ]),
        // );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response(status: 204);  // dont send any message when delete anything, just return the status 204 (no content).  
    }
}
