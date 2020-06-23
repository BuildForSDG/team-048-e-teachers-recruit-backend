<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBiodataResource extends JsonResource
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
            'about' => $this->about,
            'state' => $this->state->name,
            'lga' => $this->lga,
            'resume' => $this->resume,
            'photo' => $this->photo_url
        ];
    }
}
