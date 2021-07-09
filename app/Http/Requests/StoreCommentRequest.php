<?php

namespace App\Http\Requests;

use App\Rules\CheckAds;
use App\Rules\CheckBadWords;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'name'=>['required' , 'string' , 'max:30' , new CheckBadWords()],
            'email' => ['required' , 'email'  , new CheckBadWords()],
            'comment'=>['required' , 'string' , 'max:2000',new CheckAds() , new CheckBadWords()],
        ];

        return $rules;
    }
}
