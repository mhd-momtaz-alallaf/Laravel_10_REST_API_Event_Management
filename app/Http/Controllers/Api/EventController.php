<?php

namespace App\Http\Controllers\Api; // created by typeing:"php artisan make:controller Api/EventController --api"

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return Event::all(); // have toJson() model method to convert the data to json response.  

        $query = Event::query();
        $relations = ['user', 'attendees', 'attendees.user']; // attendees.user (each attendee has a user_id and event_id, so attendees.user will get the user model that assosiated with the attendee )

        foreach ($relations as $relation) {
            $query->when( // when the first argument ($this->shouldIncludeRelation($relation)) is true, it will run the second function (fn($q) => $q->with($relation)) to alter the query
                $this->shouldIncludeRelation($relation),
                fn($q) => $q->with($relation)
            );
        }

        return EventResource::collection(
            $query->latest()->paginate()
        ); // convert the data to a json collection that warps the returned data with "data" field and adds some other metedata to the collection.
    
        // the new route will be: api/events?include=user,attendees,attendees.user  (or we can not include a relation or not include any relation at all).
    }


    protected function shouldIncludeRelation(string $relation): bool // this function is for load the relations optionally (not allways load the user relation in the index, will load it just when we need it).
                                                                     // so we will use the include parameter in the route and add the relation/s we want to load.
    {
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include)); // the explode function lets us to convert a string to array using specefic sprator(,-.:).
                                                                // the array_map function will run the php build in function (trim) for every array element returned by explode (trim is a php build in function that remove all the spaces at the start and the end of the element)
        return in_array($relation, $relations); // a php build in function the checks if model relation $relation is exist in the relations array.
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

        // return $event;
        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // return $event;

        $event->load('user' ,'attendees');
        return new EventResource($event);
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

        // return $event; // this will return the event itself.
        
        return new EventResource($event);  


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
