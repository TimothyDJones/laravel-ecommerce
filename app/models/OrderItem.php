<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class OrderItem extends \LaravelBook\Ardent\Ardent {
    
        protected $table = 'order_items';
        
        public $timestamps = FALSE;
        
        protected $fillable = array('product_id', 'order_id', 'qty');
        
        protected $guarded = array();
        
        public function product() {
            return $this->hasOne('Product', 'id', 'product_id');
        }
        
        public function order() {
            return $this->belongsTo('Order', 'order_id');
        }
        
        
}