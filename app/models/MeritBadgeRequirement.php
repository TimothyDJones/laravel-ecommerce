<?php //namespace ;

use Illuminate\Database\Eloquent\Model as Eloquent;

class MeritBadgeRequirement extends \LaravelBook\Ardent\Ardent {
    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'merit_badge_requirements';    
    
        public static $rules = array(
            'requirement_identifier'    => 'required',
            'requirement_description' => 'required',
            'requirement_year' => 'digits:4',
            'sort_order_number' => 'numeric',            
        );

	protected $fillable = array('requirement_identifier', 'requirement_description', 
            'requirement_year', 'sort_order_number', 'merit_badge_id', );
	
	public function meritBadge() {
		return $this->belongsTo('MeritBadge');
	}
        
        public function scouts() {
            return $this->belongsToMany('Scout', 'scout_merit_badge_requirement', 'merit_badge_requirement_id', 'scout_id');
        }

}