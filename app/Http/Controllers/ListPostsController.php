<?php namespace App\Http\Controllers;

use Auth;
use App\Post;
use Illuminate\Http\Request;

class ListPostsController extends Controller {

    /*
    *--------------------------------------------------------------------------
    * List of Posts Controller
    *--------------------------------------------------------------------------
    *
    * This controller is designed to display a list of posts
    * by search / tag / category, popularity or novelty
    *
    */


    /**
     * Displaying / Search for posts on a given line
     * @param  String $text
     * @param  Request $request
     * @return Response
    */
    public function search($text, Request $request) {
        $current_user = Auth::check() ? Auth::user()->id : null;
        $text = '%' . $text . '%';
        $posts = Post::select('id', 'author_name', 'url', 'description_title', 'description_text', 'description_image', 'created_at')
                    ->where('description_title', 'like', $text)
                    ->orWhere('description_text', 'like', $text)
                    ->withCount('comments', 'likes')
                    ->with([
                      'like' => function ($like) use ($current_user) {
                          $like->whereHas('user', function ($user) use ($current_user) {
                              $user->where('id', $current_user);
                          });
                      },
                      'comment' => function ($comment) use ($current_user) {
                          $comment->whereHas('author', function ($user) use ($current_user) {
                              $user->where('id', $current_user);
                          });
                      }
                    ])
                    ->paginate(10);
        if ($request->ajax())
            return $posts->toJson();
        $hotToday = Post::withCount('views')->orderBy('views_count', 'desc')->take(6)->get();
        return view('posts', ['body_class' => '', 'posts' => $posts, 'hotToday' => $hotToday]);
    }

    /**
     * Displaying the list of posts for a given category
     * @param  String $category
     * @param  Request $request
     * @return Response
    */
    public function category($category, Request $request) {
        $current_user = Auth::check() ? Auth::user()->id : null;
        $posts = Post::select('id', 'author_name', 'url', 'description_title', 'description_text', 'description_image', 'created_at')
                    ->where('category', $category)
                    ->withCount('comments', 'likes')
                    ->with([
                      'like' => function ($like) use ($current_user) {
                          $like->whereHas('user', function ($user) use ($current_user) {
                              $user->where('id', $current_user);
                          });
                      },
                      'comment' => function ($comment) use ($current_user) {
                          $comment->whereHas('author', function ($user) use ($current_user) {
                              $user->where('id', $current_user);
                          });
                      }
                    ])
                    ->paginate(10);
        if ($request->ajax())
            return $posts->toJson();
        $hotToday = Post::withCount('views')->orderBy('views_count', 'desc')->take(6)->get();
        return view('posts', ['body_class' => '', 'posts' => $posts, 'hotToday' => $hotToday]);
    }


    /**
     * Displaying the list of posts for a given tag
     * @param  String $tagName
     * @param  Request $request
     * @return Response
    */
    public function tag($tagName, Request $request) {
        $current_user = Auth::check() ? Auth::user()->id : null;
        $tagName = '%' . $tagName . '%';
        $posts = Post::select('id', 'author_name', 'url', 'description_title', 'description_text', 'description_image', 'created_at')
                    ->where('tags', 'like', $tagName)
                    ->withCount('comments', 'likes')
                    ->with([
                      'like' => function ($like) use ($current_user) {
                          $like->whereHas('user', function ($user) use ($current_user) {
                              $user->where('id', $current_user);
                          });
                      },
                      'comment' => function ($comment) use ($current_user) {
                          $comment->whereHas('author', function ($user) use ($current_user) {
                              $user->where('id', $current_user);
                          });
                      }
                    ])
                    ->paginate(10);
        if ($request->ajax())
            return $posts->toJson();
        $hotToday = Post::withCount('views')->orderBy('views_count', 'desc')->take(6)->get();
        return view('posts', ['body_class' => '', 'posts' => $posts, 'hotToday' => $hotToday]);
    }

    /**
     * Displaying the list of posts by populary / newest
     * @param  String $category
     * @param  Request $request
     * @return Response
    */
    public function posts($type, Request $request) {
        $current_user = Auth::check() ? Auth::user()->id : null;
        switch ($type) {
            case 'newest':
            $posts = Post::select('id', 'author_name', 'url', 'description_title', 'description_text', 'description_image', 'created_at')
                        ->withCount('comments', 'likes')
                        ->with([
                          'like' => function ($like) use ($current_user) {
                              $like->whereHas('user', function ($user) use ($current_user) {
                                  $user->where('id', $current_user);
                              });
                          },
                          'comment' => function ($comment) use ($current_user) {
                              $comment->whereHas('author', function ($user) use ($current_user) {
                                  $user->where('id', $current_user);
                              });
                          }
                        ])
                        ->latest()
                        ->paginate(10);
                break;
            case 'popular':
            $posts = Post::select('id', 'author_name', 'url', 'description_title', 'description_text', 'description_image', 'created_at')
                        ->withCount('comments', 'likes', 'views')
                        ->with([
                          'like' => function ($like) use ($current_user) {
                              $like->whereHas('user', function ($user) use ($current_user) {
                                  $user->where('id', $current_user);
                              });
                          },
                          'comment' => function ($comment) use ($current_user) {
                              $comment->whereHas('author', function ($user) use ($current_user) {
                                  $user->where('id', $current_user);
                              });
                          }
                        ])
                        ->orderBy('views_count', 'desc')
                        ->paginate(10);
                break;
        }
        if ($request->ajax())
            return $posts->toJson();
        $hotToday = Post::withCount('views')->orderBy('views_count', 'desc')->take(6)->get();
        return view('posts', ['body_class' => '', 'posts' => $posts, 'hotToday' => $hotToday]);
    }
}
