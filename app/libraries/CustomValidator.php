<?php

// http://laravelsnippets.com/snippets/custom-validation-rule-for-phone-numbers
// http://culttt.com/2014/01/20/extending-laravel-4-validator/

// namespace Libraries\Services\Validation;

class CustomValidator extends \Illuminate\Validation\Validator {
 
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

