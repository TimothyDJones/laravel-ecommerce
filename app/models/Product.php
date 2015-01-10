<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Product extends \LaravelBook\Ardent\Ardent {
    
        protected $table = 'products';
        
        protected $guarded = array();
        
        public function scopeCurrentYear($query) {
            return $query->where('workshop_year', '=', date('Y'));
        }
        
        public function orderItems() {
            return $this->belongsToMany('OrderItem');
        }
        
        
}