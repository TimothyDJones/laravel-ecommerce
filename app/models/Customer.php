<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Customer extends \LaravelBook\Ardent\Ardent 
        implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customers';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
        
        protected $fillable = array('last_name', 'first_name', 'telephone1', 'telephone2', 'email', 'password', 'password_confirmation');
        
        protected $guarded = array();
        
        public static $rules = array(
            'last_name'         => 'required',
            'first_name'        => 'required',
            'telephone1'        => 'required|phone',
            'telephone2'        => 'phone',
            'email'             => 'required|email|min:5|unique:customers',
            'password'          => 'required|different:email|confirmed',
            'password_confirmation' => 'required|same:password',
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
        
        public function setEmail($value) {
            $this->attributes['email'] = strtolower($value);
        }
        
        public function addresses() {
            return $this->hasOne('Address');
        }
        
        public function orders() {
            return $this->hasMany('Order');
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
