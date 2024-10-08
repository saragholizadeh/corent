<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Reply extends JsonResource
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
            'comment_id'=> $this->parent_id,
            'name'=>$this->name,
            'email'=>$this->email,
            'comment'=>$this->comment,
            'created_at'=>$this->created_at,
        ];
    }
}
