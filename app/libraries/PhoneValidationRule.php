<?php

// http://laravelsnippets.com/snippets/custom-validation-rule-for-phone-numbers

// namespace Libraries\Services\Validation;
 
use Illuminate\Validation\Validator as IlluminateValidator;

class PhoneValidationRule extends \Illuminate\Validation\Validator {
 
    public function validatePhone($attribute, $value, $parameters)
    {
        return preg_match("/^([0-9\s\-\+\(\)]*)$/", $value);
    }
 
}
 
// Register your custom validation rule
/*
Validator::resolver(function($translator, $data, $rules, $messages)
{
    return new PhoneValidationRule($translator, $data, $rules, $messages);
});
*/

