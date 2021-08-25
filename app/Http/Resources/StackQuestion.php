<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\StackCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class StackQuestion extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
          //get user name for user_id field in question
          $questionUserId = $this->user_id;
          $user_id = User::where('id' , $questionUserId)->first();
          $user_name = $user_id->name;

           //get category title for category_id field in question
           $questionCategoryID = $this->category_id;
           $category_id = StackCategory::where('id' , $questionCategoryID)->first();
           $category_title = $category_id->title ;


        return [
            'id'=>$this->id,
            'user'=> $user_name,
            'category'=> $category_title,
            'title'=>$this->title,
            'body'=>$this->body,
            'likes'=>$this->likes,
            'dislikes'=>$this->dislikes,
            'created_at'=>$this->created_at,
            'tags'=>StackTag::collection($this->tags)->pluck('tag'),
            'images'=>Image::collection($this->images)->pluck('path'),
            'comments_count'=>StackComment::collection($this->comments)->count(),
        ];
    }
}
