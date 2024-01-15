<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            //'vlasnik posta'=>new OwnerResource($this->resource->user),
            'owner' => $this->resource->user,
            'location' => $this->resource->location,
            'content' => $this->resource->content,
            'image_path' => $this->resource->image_path,
            'created_at' => $this->resource->created_at,
            'likes' => LikeResource::collection($this->resource->likesOfPost),
            'comments' => CommentResource::collection($this->resource->commentsOfPost),
        ];
    }
}
