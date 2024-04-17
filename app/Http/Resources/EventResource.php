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
        return parent::toArray($request);
    }
}
