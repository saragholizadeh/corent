<?php

namespace App\Http\Requests;

use App\Rules\CheckBadWords;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubCategoryRequest extends FormRequest
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
        $rules =  [
            'parent_id' => ['required' , 'numeric'],
            'description'=>['required' , 'max:500' ,new CheckBadWords()],
        ];


        if ('PUT' === $this->method()) {
            $rules['title'] = 'required|unique:categories,id,' . $this->route('category_id');
        }
        else{
            $rules['title'] = 'required|unique:categories';
        }


           return $rules;
    }

    // public function messages()
    // {
    //     $message=[
    //         'parent_id.required'=>'لطفا مجموعه مورد نظر را انتخاب کنید',

    //         'title.unique'=>'عنوان نباید تکراری باشد',
    //         'title.required'=>'لطفا عنوان را وارد کنید',
    //         'title.max'=>'تعداد حروف عنوان نباید بیشتر از ۲۰ باشد',

    //         'description.required'=>'لطفا توضیحات را وارد کنید',
    //         'description.max'=>'تعداد حروف توضیحات نباید بیشتر از 500 باشد',

    //         'status.required'=>'لطفا وضعیت مورد نظر خود را انتخاب کنید' ,
    //     ];

    //     return array_merge(parent::messages(), $message);

    // }
}
