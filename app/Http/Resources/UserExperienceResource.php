<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserExperienceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'organization' => $this->organization,
            'role' => $this->role,
            'start' => $this->start,
            'end' => $this->end,
            'location' => $this->location
        ];
    }
}
