<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserEducationResource extends JsonResource
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
            'school' => $this->school,
            'start' => $this->start,
            'end' => $this->end,
            'course' => $this->course,
            'qualification' => $this->qualification
        ];
    }
}
