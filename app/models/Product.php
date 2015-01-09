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
        
        public function getByPage($page = 1, $limit = 10) {
            $results = new stdClass;
            $results->page = $page;
            $results->limit = $limit;
            $results->totalItems = 0;
            $results->items = array();
            
            $products = $this->model->skip($limit * ($page - 1))->take($limit)->get();
            
            $results->totalItems = $this->model->count();
            $results->items = $products->all();
            
            return $results;
        }
        
}