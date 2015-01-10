<?php //namespace ;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Task extends \LaravelBook\Ardent\Ardent {
    
        public static $rules = array(
            'name'          => 'required|min:4',
            'description'   => 'required',
        );
        
        public static $sluggable = array();

	protected $fillable = ['project_id', 'name', 'slug', 'completed', 'description'];
	
	public function project() {
		return $this->belongsTo('Project');
	}

}