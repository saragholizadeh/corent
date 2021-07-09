<?php

namespace App\Http\Requests;

use App\Rules\CheckAds;
use App\Rules\CheckBadWords;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreStackQuestionRequest extends FormRequest
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
        $rules=  [
            'title'=>['required' ,new CheckBadWords() ],
            'body'=>['required' ,new CheckAds() , new CheckBadWords()],
            'category_id'=>['required' ],
            'tag'=>['required'],
        ];

        return $rules;
    }
}
