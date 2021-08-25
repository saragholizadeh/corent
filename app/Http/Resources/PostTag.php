<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostTag extends JsonResource
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
            'title'=>$this->title,
            'body'=>$this->body,
            'images'=>Image::collection($this->images)->pluck('path'),
            'comments_count'=>Comment::collection($this->comments)->count(),
        ];
    }
}
