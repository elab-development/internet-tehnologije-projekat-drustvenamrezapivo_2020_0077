<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
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

           'liker'=>new LikerResource($this->resource->liker),
           'post'=>new ParentPostResource($this->resource->post),
           'created_at'=>$this->resource->created_at
        ];
    }
}
