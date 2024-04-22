<?php

namespace App\Http\Controllers\Api; // created by typeing:"php artisan make:controller Api/AttendeeController --api"

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{

    use CanLoadRelationships;

    private $relations = ['user','event'];

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'destroy']);
        $this->authorizeResource(Attendee::class,'attendee'); // after we make a policy class, this will make sure that the policy methodes will be performed within each EventController methodes.
                                                            // we just pass the model (Attendee) and the route parameter ('attendee').
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
    public function index(Event $event) // the Route Model Binding is Event not Attendee because the Atendee is scoped by the Event so attendee never be exist alone.
    {
        $attendees = $this->loadRelationships($event->attendees()->latest());

        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $attendee = $event->attendees()->create([
            'user_id' => 1
        ]);

        return New AttendeeResource($this->loadRelationships($attendee));

        // or

        // $attendee = $this->loadRelationships( 
        //     $event->attendees()->create([
        //     'user_id' => 1
        // ]) );

        // return New AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource($this->loadRelationships($attendee));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        //$this->authorize('delete-attendee',[$event, $attendee]); // commented after we make a Policy.
        $attendee->delete();

        return response(status: 204);
    }
}