<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class Analysis extends JsonResource
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
        $analysisUserId = $this->user_id;
        $user_id = User::where('id' , $analysisUserId)->first();
        $user_name = $user_id->name;

        return [
            'id'=>$this->id,
            'user'=>$user_name,
            'title'=>$this->title,
            'exchange'=>$this->exchange,
            'pair'=>$this->pair,
            'timeframe'=>$this->timeframe,
            'summary'=>$this->summary,
            'description'=>$this->description,
            'direction'=>$this->direction,
            'likes'=>$this->likes,
            'dislikes'=>$this->dislikes,
            'tags'=>$this->tags,
            'created_at'=>$this->created_at,
        ];
    }
}
