<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Post extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */	
    protected $table = 'posts';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
    protected $hidden = ['content', 'tags', 'permission', 'state', 'type', 'options'];

    public function author() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    
	public function comments() {
		return $this->HasMany('App\Comment');
	}

    public function parentComments() {
        return $this->comments()->where('parent_id', 0);
    }

	public function likes() {
		return $this->hasMany('App\Like');
	}

	public function views() {
		return $this->hasMany('App\View');
	}

	public function feadr() {
		return $this->hasMany('App\Feadr');
	}


	// check is post is liked by current user
	public function like() { 
	    return $this->HasMany('App\Like'); 
	}

	public function comment() { 
	    return $this->HasMany('App\Comment'); 
	}

	public function deleteAllRelations() {
		// delete all related comments, likes, views 
		$this->comments()->delete();
		$this->likes()->delete();
		$this->views()->delete();
        // delete the post
        return parent::delete();
	}
}