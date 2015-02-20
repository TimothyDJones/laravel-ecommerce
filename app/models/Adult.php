<?php //namespace ;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Adult extends \LaravelBook\Ardent\Ardent {
    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'adults';    
    
        public static $rules = array(
            'birth_date'    => 'date',
        );

	protected $fillable = array('person_id', 'birth_date', );
	
	public function person() {
		return $this->belongsTo('Person');
	}

}