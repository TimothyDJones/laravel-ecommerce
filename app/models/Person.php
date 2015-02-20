<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Person extends \LaravelBook\Ardent\Ardent 
        implements UserInterface, RemindableInterface {

	//use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'persons';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
        
        protected $fillable = array('last_name', 'first_name', 'home_phone', 'cell_phone', 'email_address', 'password', 'password_confirmation');
        
        protected $guarded = array();
        
        public static $validation_rules = array(
            'last_name'         => 'required',
            'first_name'        => 'required',
            'home_phone'        => 'phone',
            'cell_phone'        => 'phone',
            'email_address'     => 'required|email|min:5|unique:customers,email',
            'password'          => 'required|different:email',
            'password_confirmation' => 'required',
        );
        
        public $autoPurgeRedundantAttributes = true;
        
        /**
         * Mutators for proper capitalization of names.
         */
        public function setFirstNameAttribute($value) {
            $this->attributes['first_name'] = ucwords($value);
        }
        
        public function setLastNameAttribute($value) {
            $this->attributes['last_name'] = ucwords($value);
        }
        
        public function setEmailAddress($value) {
            $this->attributes['email_address'] = strtolower($value);
        }
        
        public function address() {
            return $this->BelongsTo('Address');
        }
        
        public function adults() {
            return $this->hasMany('Adult');
        }
        
        public function scouts() {
            return $this->hasMany('Scout');
        }        
        
        public function getAuthIdentifier() {
            return $this->getKey();
        }
        
        public function getAuthPassword() {
            return $this->password;
        }
        
        public function getRememberToken() {
            return $this->remember_token;
        }
        
        public function setRememberToken($value) {
            $this->remember_token = $value;
        }
        
        public function getRememberTokenName() {
            return "remember_token";
        }
        
        public function getReminderEmail() {
            return $this->email;
        }

}
