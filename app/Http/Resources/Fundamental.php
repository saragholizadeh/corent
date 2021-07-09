<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class Fundamental extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

         //get user name for user_id field in post
         $postUserId = $this->user_id;
         $user_id = User::where('id' , $postUserId)->first();
         $user_name = $user_id->name;

        return [
            'id'=>$this->id,
            'user'=>$user_name,
            'currency'=>$this->currency,
            'event_date'=>$this->event_date,
            'description'=>$this->description,
            'type'=>$this->type,
            'likes'=>$this->likes,
            'dislikes'=>$this->dislikes,
            'created_at'=>$this->created_at,
        ];
    }
}
