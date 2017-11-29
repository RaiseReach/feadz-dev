<?php namespace App\Http\Controllers;

use DB;
use Auth;
use Validator;
use Response;
use Notification;
use App\Report;
use App\Like;
use App\User;
use App\Post;
use App\Category;
use App\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller {

    /*
    *--------------------------------------------------------------------------
    * Admin Controller
    *--------------------------------------------------------------------------
    *
    * This controller is designed to handle requests from the administrator
    *
    */

    /**
     * Displaying the index page for admin panel with something data
     * @return Response
    */
    public function index() {
    	$graphics = [
    		'count_users'       => User::count(),
    		'count_posts'       => Post::count(),
            'count_users_today' => User::where('created_at', '>=', Carbon::today())->count(),
            'latest_members'    => User::latest()->limit(8)->get(),
            'latest_posts'      => Post::latest()->limit(10)->get(),
            'count_likes'       => Like::count(),
            'count_comments'    => Comment::count(),
        ];
        $countRecords = $this->countRecords();
    	return view('admin.adminLTE.index', ['body_class' => 'admin', 'graphics' => $graphics, 'countRecords' => $countRecords]);
    }


    /**
     * Send notifications to users
     * @param  Request $request
     * @return Response
    */
    public function sendNotify(Request $request) {
        $validator = Validator::make($request->all(), [
            'message' => 'required|min:3|max:255'
        ]);

        if(!$validator->fails()) {
            if($request->type == 'all') {
                Notification::send(User::all(), new \App\Notifications\notifyDB($request->message));
            } else {
                $user = User::where('name', $request->name)->first();
                if(!empty($user)) {
                    $user->notify(new \App\Notifications\notifyDB($request->message));
                } else {
                    return response()->json(['success' => false, 'errorText' => ['User not found!']]);
                }
            }
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'errorText' => $validator->errors()]);
    }


    /**
     * setting rights for users
     * @param  Request $request
     * @return Response
    */
    public function setRights(Request $request) {
        if(Auth::user()->id != $request->user_id) {
            $user = User::find($request->user_id);
            $user->role = $request->rights;
            $user->save();
        }
        Response::json(['success' => true]);
    }


    /**
     * Displaying a page with notifications data
     * @return Response
    */
    public function notifications() {
        $countRecords = $this->countRecords();
        return view('admin.adminLTE.pages.notifications', ['body_class' => 'admin', 'countRecords' => $countRecords]);
    }


    /**
     * Displaying a page with categories data
     * @return Response
    */
    public function categories() {
        $categories = Category::all();
        $countRecords = $this->countRecords();
        return view('admin.adminLTE.pages.categories', ['body_class' => 'admin', 'countRecords' => $countRecords, 'categories' => $categories]);
    }


    /**
     * Displaying a page with users data
     * @return Response
    */
    public function users() {
    	$users = User::withCount('posts')->get();
        $countRecords = $this->countRecords();
    	return view('admin.adminLTE.pages.users', ['body_class' => 'admin', 'users' => $users, 'countRecords' => $countRecords]);
    }


    /**
     * Displaying a page with posts data
     * @return Response
    */
    public function posts() {
    	$posts = Post::withCount('comments', 'likes')->get();
        $countRecords = $this->countRecords();
    	return view('admin.adminLTE.pages.posts', ['body_class' => 'admin', 'posts' => $posts, 'countRecords' => $countRecords]);
    }


    /**
     * Displaying a page with reports data
     * @return Response
    */
    public function reports() {
        $reports = Report::with('post', 'post.author')->get();
        $total = Report::count();
        $countRecords = $this->countRecords();
        return view('admin.adminLTE.pages.reports', ['body_class' => 'admin', 'reports' => $reports, 'countRecords' => $countRecords]);
    }


    /**
     * Store a category
     * @param  Request $request
     * @return Response
    */
    public function saveCategory(Request $request) {
        $validator = Validator::make($request->all(), [
            'category' => 'required|min:3|max:20|alpha_num'
        ]);

        if(!$validator->fails()) {
            if($request->category_id == null) {
                $category = new Category;
                $category->category = $request->category;
                $category->save();
            } else {
                $category = Category::find($request->category_id);
                $category->category = $request->category;
                $category->save();
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['errorText' => $validator->errors()]);
    }

    /**
     * Removing a category.
     * @return Response
    */
    public function deleteCategory(Request $request) {
        Category::find($request->category_id)->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Return array with values for chart of users
     * @return array
    */
    public function chartUsers() {
        $countRecords = $this->countRecords();
        $chart = $this->registrationsMonth();
        return view('admin.adminLTE.pages.chartUsers', ['body_class' => 'admin', 'countRecords' => $countRecords, 'chart' => $chart]);
    }

    /**
     * Return array with values for chart of posts
     * @return array
    */
    public function chartPosts() {
        $countRecords = $this->countRecords();
        $chart = $this->postsMonth();
        return view('admin.adminLTE.pages.chartPosts', ['body_class' => 'admin', 'countRecords' => $countRecords, 'chart' => $chart]);
    }


    /**
     * Report processing
     * @param  Request $request
     * @return Response
    */
    private function countRecords() {
        return [
            'reports' => Report::count(),
            'users'   => User::count(),
            'posts'   => Post::count()
        ];
    }

    /**
     * Report processing
     * @param  Request $request
     * @return Response
    */
    public function updateReport(Request $request) {
     
        switch($request->action) {
            case 'delete_user':
                Post::where(['user_id' => $request->user_id])->update(['user_id' => Auth::user()->id, 'author_name' => Auth::user()->name]);
                Report::find($request->report_id)->delete();
                Report::where(['user_id' => $request->user_id])->delete();
                User::find($request->user_id)->deleteAllRelations();
                break;
            case 'delete_post':
                Report::find($request->report_id)->delete();
                Post::find($request->post_id)->deleteAllRelations();
                break;
            case 'delete_report':
                Report::find($request->report_id)->delete();
                break;
            case 'delete_posts_and_user':
                User::find($request->user_id)->deleteAllRelations();
                Report::find($request->report_id)->delete();
                Report::where(['user_id' => $request->user_id])->delete();
                break;
        }
        return redirect('/admin/reports');
    }

    /**
     * Editing a user
     * @param  String  $author
     * @return Response
    */
    public function editUser($author) {
        $user = User::where('name', $author)->first();
        if(!empty($user)) {
            return view('admin.editing.user', ['body_class' => 'settings', 'user' => $user]);
        }
        return redirect('/admin');
    }

    /**
     * Update a user information
     * @param  String  $author
     * @param  Request $request
     * @return Response
    */
    public function updateUser($author, Request $request) {
        $user = User::where('name', $author)->first();
        if(!empty($user)) {
            $user->real_name      = $request->input('real_name', '');
            $user->description    = $request->input('description', '');
            // Validation email
            $validator_email = Validator::make($request->input(), ['email_for_news' => 'required|email|max:255']);
            $user->email_for_news = !$validator_email->fails() ? $request->input('email_for_news') : '';
            // Validation user name
            $validator_name = Validator::make($request->input(), [
                'name' => 'required|min:3|max:255|unique:users|alpha_num|regex:/^[a-zA-Z0-9]+$/u|not_in:admin,create,upload,success,report,auth,user,ref,referrals,home,logout,login,disclaimer,channel,edit,category,search',
            ]);
            if(!$validator_name->fails()) {
                $user->name = $request->input('name');
                $user->posts()->update(['author_name' => $request->input('name')]);
            }
            $user->save();
            return view('admin.editing.user', ['body_class' => 'settings', 'user' => $user]);
        }
        redirect('/admin');
    }


    /**
     * Editing a user post
     * @param  Request  $request
     * @return Response
    */
    public function editPost($author, $url) {
        $post = Post::where(['author_name' => $author, 'url' => $url])->first();
        if(!empty($post)) {
            return view('tools.editing.'.$post->type, ['body_class' => 'create '.$post->type, 'post' => $post]);
        }
        return redirect('/admin');
    }

    /**
     * Removing a user.
     * @param  Request  $request
     * @return Response
    */
    public function deleteUser(Request $request) {
        $user = User::where(['id' => $request->user_id])->first();
        $user->deleteAllRelations();
        return Response::json(['success' => true]); 
    }

    /**
     * Removing a user post.
     * @param  Request  $request
     * @return Response
    */
    public function deletePost(Request $request) {
        Post::find($request->post_id)->deleteAllRelations();
        return Response::json(['success' => true]);
    }

    /**
     * Removing a user photo.
     * @param  Request  $request
     * @return Response
    */
    public function deletePhoto(Request $request) {
        $user = User::where(['name' => $request->name])->first();
        $user->photo = '';
        $user->save();
        return Response::json(['success' => true]);
    }

    /**
     * returns array of values number of user registrations / month
     * @return array
    */
    private function registrationsMonth() {
		$results = DB::table('users')
		    ->selectRaw('count(*) as users, extract(month from users.created_at) as month')
		    ->groupBy('month')
		    ->pluck('users', 'month');

		return array_replace(array_fill_keys(range(1, 12), 0), $results->all());
    }

    /**
     * returns array of values number of created posts / month
     * @return array
    */
    private function postsMonth() {
    	$currentMonth = date('m');
		$results = DB::table('posts')
			->whereRaw('MONTH(created_at) = ?',[$currentMonth])
		    ->selectRaw('count(*) as posts, extract(month from posts.created_at) as month')
		    ->groupBy('month')
		    ->pluck('posts', 'month');

		return array_replace(array_fill_keys(range(1, date('t')), 0), $results->all());
    }
}
