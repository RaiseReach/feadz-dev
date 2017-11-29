<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentLikes extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = "comment_likes";
    public $timestamps = false;
}
