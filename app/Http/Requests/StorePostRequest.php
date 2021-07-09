<?php

namespace App\Http\Requests;

use App\Rules\CheckBadWords;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'category_id'=>['required' , 'integer'] ,
            'body'=>['required','string','min:300' ,new CheckBadWords()],
            'study_time'=>['required' ,'numeric'],
            'tags'=>['required','string' ,new CheckBadWords()],
            'image'=>['required'],
        ];

        if ('PUT' === $this->method()) {
            $rules['title'] = 'required|unique:posts,id'. $this->route('post_id');
        }
        else{
            $rules['title'] = 'required|unique:posts';
        }
           return $rules;
    }

}
