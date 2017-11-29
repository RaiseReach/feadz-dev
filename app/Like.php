<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Like extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'likes';

    public function parentPost() {
    	return $this->belongsTo('App\Post', 'post_id', 'id');
    }

	public function user() { 
	    return $this->hasOne('App\User', 'id', 'user_id'); 
	}
}