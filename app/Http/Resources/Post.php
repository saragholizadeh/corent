<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;


class Post extends JsonResource
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


         //get category title for category_id field in post
         $postCategoryId = $this->category_id;
         $category_id = Category::where('id' , $postCategoryId)->first();
         $category_title = $category_id->title ;


        return [
            'id' => $this->id,
            'user'=>$user_name,
            'category'=> $category_title,
            'title'=>$this->title,
            'body'=>$this->body,
            'study_time'=>$this->study_time,
            'likes'=>$this->likes,
            'dislikes'=>$this->dislikes,
            'created_at'=>$this->created_at,
            'tags'=>Tag::collection($this->tags)->pluck('tag'),
            'images'=>Image::collection($this->images)->pluck('path'),
        ];
    }

}
