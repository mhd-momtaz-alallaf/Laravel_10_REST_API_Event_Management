<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource // apiResources allow us to have more control on returned json data.
{                                        // EventResource have no relation with Event model its just a name.
                                         // each model can use multiple apiResources to handel data differently.
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [ // we can customize the returned json data as we want, by the ("key" => "value").
                 // the returned key dont have to match the actual model field name.
                 // we cant hide some fields from by just not mintion them here.

                 'id' => $this->id,
                 'name' => $this->name,
                 'description' => $this->description,
                 'start_time' => $this->start_time,
                 'end_time' => $this->end_time,

                 'user' => new UserResource($this->whenLoaded('user')), // this "user" property will only be prisent no the response if this user relation of the Event model is loaded (for queries efficiently).
                        // the fields of the user will be reprisented by UserResource.

                 'attendees' => AttendeeResource::collection(
                     $this->whenLoaded('attendees')
                 )
        ];
    }
}
