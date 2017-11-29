<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Report extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'reports';
    public $timestamps = false;

    public function post() {
    	return $this->hasOne('App\Post', 'id', 'post_id');
    }

    public function owner() {
    	return $this->hasOne('App\User', 'id', 'user_id');
    }
}