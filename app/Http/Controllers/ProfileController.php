<?php namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfileController extends Controller {

    /*
    *--------------------------------------------------------------------------
    * Profile Controller
    *--------------------------------------------------------------------------
    *
	* This controller is designed to display the user's
	* personal page, as well as to display its statistics
    *
    */

    /**
     * Redirect user to his personal page
     * @param  Request $request
     * @return Response
     */
	public function toProfile(Request $request) {
		return redirect(Auth::user()->name);
	}

    /**
     * Displaying a user's personal page with data
     * @param  string $author
     * @param  Request $request
     * @return Response
     */
	public function profile($author, Request $request) {
		$user = User::where('name', $author)->first();
		if(empty($user)) {
			return redirect('/home');
		}
		$page = (int) $request->input('page', 1);
		$current_user = Auth::check() ? Auth::user()->id : null;
		if($request->ajax()) {
			$type = $request->input('type', 'overview');
			switch ($type) {
				case 'comments':
					$comments = $user->load(['comments' => function($query) {
						$query->with(['author', 'parentPost'])->latest()->paginate(10);
					}]);
					return $comments->toJson();
					break;		
				case 'likes':
					$likes = $user->load(['likes' => function($query) {
						$query->with(['user', 'parentPost'])->latest()->paginate(10);
					}]);
					return $likes->toJson();
					break;
				case 'posts':
					$posts = $user->load([
						'posts' => function($query) use ($current_user) {
							$query->withCount('comments', 'likes');
							$query->with(['like' => function ($like) use ($current_user) {
						  		$like->whereHas('user', function ($user) use ($current_user) {
						  			$user->where('id', $current_user);
						  		});
							},
						'comment' => function ($comment) use ($current_user) {
					  		$comment->whereHas('author', function ($user) use ($current_user) {
					  			$user->where('id', $current_user);
					  		});
						}
						])->latest()->paginate(10);
					}]);
					return $posts->toJson();
					break;
				case 'overview':
					$overview = $user->load([
						'posts' => function($query) use ($current_user) {
							$query->withCount('comments', 'likes');
							$query->with(['like' => function ($like) use ($current_user) {
						  		$like->whereHas('user', function ($user) use ($current_user) {
						  			$user->where('id', $current_user);
						  		});
							},
						'comment' => function ($comment) use ($current_user) {
					  		$comment->whereHas('author', function ($user) use ($current_user) {
					  			$user->where('id', $current_user);
					  		});
						}
						])->latest();
					}, 'comments' => function($query) {
						$query->with('author', 'parentPost')->latest();
					}, 'likes' => function($query) {
						$query->with('user', 'parentPost')->latest();
					}]);
					$overview = $overview->posts->merge($overview->comments)->merge($overview->likes)->sortByDesc('created_at');
					return $overview->forPage($page, 10)->values()->toJson();
					break;
				default:
					break;
			}
		}
		$overview = $user->load([
			'posts' => function($query) use ($current_user) {
				$query->withCount('comments', 'likes', 'views');
				$query->with(['like' => function ($like) use ($current_user) {
			  		$like->whereHas('user', function ($user) use ($current_user) {
			  			$user->where('id', $current_user);
			  		});
				},
			'comment' => function ($comment) use ($current_user) {
		  		$comment->whereHas('author', function ($user) use ($current_user) {
		  			$user->where('id', $current_user);
		  		});
			}
			])->latest();
		}, 'comments' => function($query) {
			$query->with('author', 'parentPost')->latest();
		}, 'likes' => function($query) {
			$query->with('user', 'parentPost')->latest();
		}]);
		$views = 0; 
		foreach($overview->posts as $post) {
			$views += $post->views_count;
		}
		$overview = $overview->posts->merge($overview->comments)->merge($overview->likes)->sortByDesc('created_at');
		$overview = $overview->forPage($page, 10)->values();
       	$editButton = (Auth::check() && ($author == Auth::user()->name)) ? 1 : 0;
		$hotToday = Post::withCount('views')->orderBy('views_count', 'DESC')->take(10)->get();
		return view('user.profile', ['body_class' => 'profile', 'user' => $user, 'views' => $views, 'overview' => $overview, 'hotToday' => $hotToday, 'editButton' => $editButton]);
	}

    /**
     * Displaying a user's personal stats
     * @return Response
     */
	public function stats() {
		$user = Auth::user();
		$user->load(['posts' => function($query) {
			$query->withCount('views');
		}]);
		$views_all = $views_today = 0;
		foreach($user->posts as $post) {
			$views_all += $post->views_count;
		}
		$today = Carbon::now()->format('Y-m-d') . '%';
		$user->load(['posts' => function($query) use($today){
			$query->withCount(['views' => function($view) use($today) {
				$view->where('created_at', 'like', $today);
			}]);
		}]);
		foreach($user->posts as $post) {
			$views_today += $post->views_count;
		}
		return view('user.stats', ['body_class' => 'stats', 'views_all' => $views_all, 'views_today' => $views_today]);
	}
}