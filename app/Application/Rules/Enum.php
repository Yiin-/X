<?php

namespace App\Application\Rules;

use Illuminate\Contracts\Validation\Rule;

class Enum extends Rule
{
    public function passes($attribute, $value, $parameters)
    {
        return in_array($value, $parameters);
    }

    public function message()
    {
        return 'Please select valid :attribute.';
    }
}