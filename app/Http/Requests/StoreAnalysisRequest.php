<?php

namespace App\Http\Requests;

use App\Rules\CheckBadWords;
use Illuminate\Foundation\Http\FormRequest;

class StoreAnalysisRequest extends FormRequest
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
            'title'=>['required','string' , new CheckBadWords() ],
            'exchange' => ['required' , 'string' , 'max:40'  ],
            'pair' => ['required' , 'string' ],
            'timeframe' => ['required' , 'integer'],
            'summary' => ['required' , 'max:200' , new CheckBadWords()],
            'description' => ['required' , 'string' , new CheckBadWords() ],
            'tags'=> ['nullable' , 'string' , new CheckBadWords()],
            'image'=>['required'],
        ];

        return $rules;
    }
}
