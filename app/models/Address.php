<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Address extends \LaravelBook\Ardent\Ardent {
    
        protected $table = 'addresses';
        
        protected $fillable = array(
            'addr1', 'addr2', 'city', 'state', 'postal_code', 
            'country', 'primary_addr_ind', 'customer_id',
        );
        
        protected $guarded = array();
        
        public static $rules = array(
            'addr1'             => 'required|min:5',
            'city'              => 'required',
            'state'             => 'required',
            'postal_code'       => 'required',
            'country'           => 'alpha|size:3',
        );
        
	public function customer() {
		return $this->belongsTo('Customer');
	}
        
        /**
         * Mutators for proper capitalization of names.
         */
        public function setAddr1Attribute($value) {
            $this->attributes['addr1'] = ucwords($value);
        }
        
        public function setAddr2Attribute($value) {
            $this->attributes['addr2'] = ucwords($value);
        }
        
        public function setStateAttribute($value) {
            $this->attributes['state'] = ucwords($value);
        }
        
        public function setCityAttribute($value) {
            $this->attributes['city'] = ucwords($value);
        }
        
        public function setCountryAttribute($value) {
            $this->attributes['country'] = strtoupper($value);
        }
        
        
}