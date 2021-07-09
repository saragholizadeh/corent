<?php

namespace App\Http\Requests;

use App\Rules\CheckBadWords;
use Illuminate\Foundation\Http\FormRequest;

class StoreFundamentalRequest extends FormRequest
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
            'currency'=>['required','string' , new CheckBadWords()],
            'event_date'=>['required','date'],
            'description'=>['required','max:400' , new CheckBadWords()]
        ];

        return $rules ;
    }
}
