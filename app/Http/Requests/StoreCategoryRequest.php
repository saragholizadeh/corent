<?php

namespace App\Http\Requests;

use App\Rules\CheckAds;
use App\Rules\CheckBadWords;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;


class StoreCategoryRequest extends FormRequest
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
            'description'=>['required','max:500' ,new CheckAds() , new CheckBadWords()],
        ];

        if ('PUT' === $this->method()) {
            $rules['title'] = 'required|unique:categories,id,' . $this->route('category_id');
        }
        else{
            $rules['title'] = 'required|unique:categories|';
        }

            return $rules;
    }

}
