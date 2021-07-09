<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStackCategoryRequest extends FormRequest
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
        $rules = [];
        if ('PUT' === $this->method()) {
            $rules['title'] = 'required|unique:stack_categories,id,' . $this->route('stack_category_id');
        }
        else{
            $rules['title'] = 'required|unique:stack_categories';
        }

        return $rules;
    }
}
