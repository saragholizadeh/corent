<?php

namespace App\Http\Resources;

use App\Models\Category as CategoryModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         //get category title for paretn_id field in subcategories
         $categoryParentId = $this->parent_id;
         $categoryId = $this->id;

         if($categoryParentId==NULL){
            $parent_name = "دسته بندی اصلی";
         }else{
            $parent_id = CategoryModel::where('parent_id' , NULL)->find($categoryParentId);
            $parent_name = $parent_id->title;
         }

        return [
            'id'=>$this->id,
            'parent_category'=>$parent_name,
            'title'=>$this->title,
            'created_at'=>$this->created_at,
            'description'=>$this->description
        ];
    }
}
