<?php

// http://laravelsnippets.com/snippets/custom-validation-rule-for-phone-numbers
// http://culttt.com/2014/01/20/extending-laravel-4-validator/

namespace Libraries\Services\Validation;

class CustomValidatorServiceProvider extends \Illuminate\Support\ServiceProvider {
    public function register() { }
    
    public function boot() {
        $this->app->validator->resolver( function($translator, $data, $rules, $messages) {
            return new CustomValidator($translator, $data, $rules, $messages);
        });
    }
}

