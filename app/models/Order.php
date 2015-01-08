<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Order extends \LaravelBook\Ardent\Ardent {
    
        protected $table = 'orders';
        
        protected $fillable = array('customer_id', 'order_status');
        
        protected $guarded = array();
        
        public function customer() {
            return $this->belongsTo('Customer', 'customer_id');
        }
        
        public function products() {
            return $this->hasManyThrough('Product', 'OrderItem');
        }
        
        public function orderItems() {
            return $this->hasMany('OrderItem');
        }
        
        
}