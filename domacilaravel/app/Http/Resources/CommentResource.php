<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'commentator'=>new CommentatorResource($this->resource->commentator),
            'post'=>new ParentPostResource($this->resource->post),
            'content'=>$this->resource->content,
            'created_at'=>$this->resource->created_at,

        ];
    }
}
