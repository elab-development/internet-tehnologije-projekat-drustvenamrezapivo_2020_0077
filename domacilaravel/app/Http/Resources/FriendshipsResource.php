<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FriendshipsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'prvi user'=>new FriendResource($this->resource->user1),
            'drugi user'=>new FriendResource($this->resource->user2),
        ];
    }
}
