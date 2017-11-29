<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Comment extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments';

    public function author() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function parentPost() {
    	return $this->belongsTo('App\Post', 'post_id', 'id');
    }

    public function replies() {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    public function likes() {
        return $this->hasMany('App\CommentLikes', 'comment_id');
    }

    public function allRepliesWithAuthor() {
        return $this->replies()->with(__FUNCTION__, 'author', 'likes');
    }
}