<?php //namespace ;

use Illuminate\Database\Eloquent\Model as Eloquent;

class MeritBadge extends \LaravelBook\Ardent\Ardent {
    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'merit_badges';    
    
        public static $rules = array(
            'merit_badge_name'    => 'required',
            'merit_badge_description' => 'required',
            'requirements_last_changed_year' => 'digits:4',
            'eagle_reqd_ind' => 'required',            
        );

	protected $fillable = array('merit_badge_name', 'merit_badge_description', 
            'requirements_last_changed_year', 'eagle_reqd_ind', 'badge_image',
            'merit_badge_org_url', 'primary_counselor_id');
	
	public function primaryCounselor() {
		return $this->belongsTo('Adult');
	}
        
        public function meritBadgeRequirements() {
            return $this->hasMany('MeritBadgeRequirement');
        }
        
        public function scouts() {
            return $this->belongsToMany('Scout', 'scout_merit_badge', 'merit_badge_id', 'scout_id');
        }

}