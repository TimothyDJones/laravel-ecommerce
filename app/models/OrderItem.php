<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class OrderItem extends \LaravelBook\Ardent\Ardent {
    
        protected $table = 'order_items';
        
        protected $fillable = array('product_id', 'order_id', 'qty');
        
        protected $guarded = array();
        
        public function product() {
            return $this->hasOne('Product');
        }
        
        public function order() {
            return $this->belongsTo('Order');
        }
        
        
}