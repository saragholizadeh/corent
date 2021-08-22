<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\StackQuestion as StackQuestionModel;
use Illuminate\Http\Resources\Json\JsonResource;

class StackAnswer extends JsonResource
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
        $answerUserId = $this->user_id;
        $user_id = User::where('id' , $answerUserId)->first();
        $user = $user_id->name;


        //get question title for question_id field in post
        $answerQuestionId = $this->question_id;
        $question_id = StackQuestionModel::where('id' , $answerQuestionId)->first();
        $question_title = $question_id->title ;
        return [
            'id'=>$this->id,
            'answer'=>$this->answer,
            'user'=>$user,
            'question'=>$question_title,
            'created_at'=>$this->created_at,
            'likes'=>$this->likes,
            'dislikes'=>$this->dislikes,
            'images'=>Image::collection($this->images)->pluck('path'),
        ];
    }
}
