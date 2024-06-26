<?php

namespace App\Http\Controllers\Api; // created by typeing:"php artisan make:controller Api/EventController --api"

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{

    use CanLoadRelationships; // (Trait) to make the cntroller class use the functions of the Trait without inheritance.


    private $relations = ['user', 'attendees', 'attendees.user']; // attendees.user (each attendee has a user_id and event_id, so attendees.user will get the user model that assosiated with the attendee )

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->middleware('throttle:api') // rate limite this controller methods requests to make the users makes only 60 request per minute, by aplly the throttle middleware with group name 'api'.
            ->only(['store', 'update', 'destroy']);

        $this->authorizeResource(Event::class,'event'); // after we make a policy class, this will make sure that the policy methodes will be performed within each EventController methodes.
                                                            // we just pass the model (Event) and the route parameter ('event').
        // Controller Method    Policy Method
        // index      =>        viewAny
        // show       =>        view
        // create     =>        create
        // store      =>        create
        // edit       =>        update
        // update     =>        update
        // destroy    =>        delete
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return Event::all(); // have toJson() model method to convert the data to json response.  
        
        $query = $this->loadRelationships(Event::query()); // we dont have to pass the global relations array to the loadRelationships() trait function because the function ask for the relations array from inside the CanLoadRelationships Trait.

        return EventResource::collection(
            $query->latest()->paginate()
        ); // convert the data to a json collection that warps the returned data with "data" field and adds some other metedata to the collection.
    
        // the new route will be: api/events?include=user,attendees,attendees.user  (or we can not include a relation or not include any relation at all).
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
            'user_id' => $request->user()->id
        ]);

        // return $event;
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // return $event;

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {

        // if( Gate::denies('update-event', $event))
        //     abort(403,'You are not Authorized to update this event!!');

        // $this->authorize('update-event',$event); // the same as above. // commented after we make a Policy.

        $event->update(       
            $request->validate([
                'name' => 'sometimes|string|max:255', // sometimes: checks the constraints after sometimes only if the value in the input is present.
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ]),
        );

        // return $event; // this will return the event itself.
        
        return new EventResource($this->loadRelationships($event));  


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
