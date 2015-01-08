<?php //namespace ;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Project extends \LaravelBook\Ardent\Ardent {
    
        public static $rules = array(
            'name'  => 'required|min:4',
        );
        
        public static $sluggable = array();

	protected $fillable = ['name', 'slug'];
	
	public function tasks() {
		return $this->hasMany('Task');
	}

}