<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckAds implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value){
        $ads = array(
            'www' , '.com' , '.ir' , '@gmail' , '@yahoo' , '.org' , '@' ,
        );

        foreach ($ads as $ad) {
            if (stripos($value, $ad) !== false) {
                return false;
            }
        }

        return true;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'ads used!';
    }
}
