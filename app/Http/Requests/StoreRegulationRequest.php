<?php

namespace App\Http\Requests;

use App\Rules\CheckBadWords;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegulationRequest extends FormRequest
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
            'description'=>['required',new CheckBadWords()],
            'short_description'=>['required','max:70' , new CheckBadWords()],
            'population'=>'required',
            'area'=>'required',
            'internet_penetration'=>'required',
            'national_currency'=>'required',
            'goverment'=>'required',
            'language'=>'required',
            'economic_growth'=>'required',
            'dgtl_curr_lgs'=>'required',
            'dgtl_curr_tax'=>'required',
            'dgtl_curr_pymt'=>'required',
            'dgtl_curr_ntiol'=>'required',
            'ICO'=>'required',
            'crpto_antimon_rules'=>'required',
        ];
        if ('PUT' === $this->method()) {
            $rules['country'] = 'required|unique:regulations,id,' . $this->route('regulation_id');
        }
        else{
            $rules['country'] = 'required|unique:regulations';
        }

        if ('PUT' === $this->method()) {
            $rules['president'] = 'required|unique:regulations,id,' . $this->route('regulation_id');
        }
        else{
            $rules['president'] = 'required|unique:regulations';
        }

        if ('PUT' === $this->method()) {
            $rules['capital'] = 'required|unique:regulations,id,' . $this->route('regulation_id');
        }
        else{
            $rules['capital'] = 'required|unique:regulations';
        }

        return $rules;
    }

    // public function messages()
    // {
    //     $message=[

    //         'user_id.required'=>'لطفا نام کاربری نویسنده را وارد کنید',

    //         'country.required'=>'لطفا نام کشور را وارد کنید',
    //         'country.unique'=>'نام کشور نباید تکراری باشد',

    //         'photo.required'=>'لطفا عکس مورد نظر خود را وارد کنید',

    //         'description.required'=>'لطفا توضیحات مربوطه را وارد کنید',

    //         'short_description.required'=>'لطفا توضیحات مربوطه را وارد کنید',

    //         'population.required'=>'لطفا تعداد جمعیت را وارد کنید',

    //         'area.required'=>'لطفا مساحت را وارد کنید',

    //         'internet_penetration.required'=>'لطفا ضریب نغوذ اینترنت را وارد کنید',

    //         'national_currency.required'=>'لطفا ارز ملی را وارد کنید',

    //         'goverment.required'=>'لطفا دولت مورد نظر را وارد کنید',

    //         'president.required'=>'لطفا رئیس جمهور مورد نظر را وارد کنید',
    //         'president.unique'=>'نام کشور نباید تکراری باشد',


    //         'capital.required'=>'لطفا پایتخت مورد نظر را وارد کنید',
    //         'capital.unique'=>'نام پایتخت نباید تکراری باشد',


    //         'language.required'=>'لطفا زبان مورد نظر را وارد کنید',

    //         'economic_growth.required'=>'لطفا میزان رشد اقتصاد مورد نظر را وارد کنید',

    //         'dgtl_curr_lgs.required'=>'لطفا وضعیت قانون گذاری ارزهای دیجیتال را وارد کنید',

    //         'dgtl_curr_tax.required'=>'لطفا وضعیت مالیات بر ارزهای دیجیتال را وارد کنید',

    //         'dgtl_curr_pymt.required'=>'لطفا وضعیت پرداخت با ارزدیجیتال مورد نظر را وارد کنید',

    //         'dgtl_curr_ntiol.required'=>'لطفا وضعیت ارزدیجیتال ملی را وارد کنید',

    //         'ICO.required'=>'لطفا وضعیت ICO مورد نظر را وارد کنید',

    //         'crpto_antimon_rules.required'=>'لطفا وضعیت قوانین ضد پولشویی برای کریپتو را وارد کنید',

    //     ];

    //     return array_merge(parent::messages(), $message);

    // }
}
