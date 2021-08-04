<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class StackComment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user_id = $this->user_id;
        $user = User::where('id' , $user_id)->first();
        $userName = $user->name;
        $userEmail = $user->email;

        return [
            'user_name' =>$userName,
            'user_email'=>$userEmail,
            'comment'=>$this->comment,
            'created_at'=>$this->created_at,
            'replies'=>StackComment::collection($this->replies),
        ];
    }
}
