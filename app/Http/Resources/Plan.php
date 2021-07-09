<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class Plan extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

         //get product name for priduct_id field in post
         $planProductId = $this->product_id;
         $product_id = Product::where('id' , $planProductId)->first();
         $product_title = $product_id->title;

        return [
            'id'=>$this->id,
            'product_id'=> $product_title,
            'title'=>$this->title,
            'description'=>$this->description,
            'price'=>$this->price,
            'created_at'=>$this->created_at,
        ];
    }
}
