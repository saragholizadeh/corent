<?php

namespace App\Http\Requests;

use App\Rules\CheckBadWords;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'description' => ['required',new CheckBadWords()],
            'image' => ['required'],
        ];
        if ('PUT' === $this->method()) {
            $rules['title'] = 'required|unique:products,id,' . $this->route('product_id');
        }
        else{
            $rules['title'] = 'required|unique:products';
        }

        return $rules;
    }
}
