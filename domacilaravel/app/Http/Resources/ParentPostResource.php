<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParentPostResource extends JsonResource
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
           // 'title'=>$this->resource->title,
           'location'=>$this->resource->location,
            'content'=>$this->resource->content,
            'image_path'=>$this->resource->image_path,
        ];
    }
}
