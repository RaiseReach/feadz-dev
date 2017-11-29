<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Auth (and logout)
Auth::routes();


// reset password
Route::post('/password/email', 'Auth\ForgotPasswordController@getResetToken');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/login/{provider?}',[
    'uses' => 'AuthController@getSocialAuth',
    'as'   => 'auth.getSocialAuth'
]);
Route::get('/login/callback/{provider?}',[
    'uses' => 'AuthController@getSocialAuthCallback',
    'as'   => 'auth.getSocialAuthCallback'
]);

// Home page
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('privacy-policy', function() {
	return view('landings.privacy', ['body_class' => 'privacy']);
});
Route::get('terms-of-service', function() {
	return view('landings.terms', ['body_class' => 'terms']);
});
Route::get('disclaimer', function() {
	return view('landings.disclamer', ['body_class' => 'disclaimer']);
});

// CRON
Route::get('/cron/update-tags', 'CronController@tags');

// Register
Route::get('/register', 'HomeController@index');


// Newsletter (add a new email)
Route::post('/addition/newsletter', 'NewsletterController@store');

Route::group(['middleware' => 'auth'], function() {
	// Successfully create tool
	Route::get('/success/{author}/{url}', 'Tools\AdditionController@successPage');

	// editing tools
	Route::get('/edit/{url}', 'Tools\AdditionController@editPage');
	// Group - Creating tools
	Route::group(['prefix' => 'create'], function() {
		// GET
		Route::get('rankedlist', function() {
			return view('tools.creating.rankedlist', ['body_class' => 'create rankedlist']);
		});
		Route::post('rankedlist/vote', 'Tools\RankedlistController@vote');

		Route::get('flipcards', function() {
			return view('tools.creating.flipcards', ['body_class' => 'create flipcards']);
		});
		Route::get('gifmaker', function() {
			return view('tools.creating.gifmaker', ['body_class' => 'create gifmaker']);
		});
		Route::get('story', function() {
			return view('tools.creating.story', ['body_class' => 'create story']);
		});
		Route::get('snip', function() {
			return view('tools.creating.snip', ['body_class' => 'create snip']);
		});
		Route::get('meme', function() {
			return view('tools.creating.meme', ['body_class' => 'create meme']);
		});

		// saving tools
		Route::post('/rankedlist/send', 'Tools\RankedlistController@handler');
		Route::post('/flipcards/send', 'Tools\FlipcardsController@handler');
		Route::post('/gifmaker/send', 'Tools\GIFMakerController@handler');
		Route::post('/gifmaker/create', 'Tools\GIFMakerController@createGIF');
		Route::post('/story/send', 'Tools\StoryController@handler');
		Route::post('/meme/send', 'Tools\MemeController@handler');
		Route::post('/snip/send', 'Tools\SnipController@handler');
	});

	Route::group(['prefix' => 'addition'], function() {
		Route::post('save-image', 'Tools\AdditionController@saveImage');
		Route::post('youtube-info', 'Tools\AdditionController@getInfoYoutube');
		Route::post('delete-post', 'Tools\AdditionController@deletePost');
		Route::post('add-comment', 'Tools\CommentsController@add');
		Route::post('set-like', 'Tools\LikesController@post');
		Route::post('set-feadr', 'FeadrController@store');
		Route::post('comment-like', 'Tools\LikesController@comment');
		Route::post('add-report', 'ReportController@store');
		Route::post('read-notifications', 'UserController@notifications');
	});
	// Group - Editing profile of user
	Route::group(['prefix' => 'user'], function() {
		Route::get('settings', function() {
			return view('user.settings', ['body_class' => 'settings', 'user' => Auth::user()]);
		});
		Route::get('stats', 'ProfileController@stats');
		Route::get('profile', 'ProfileController@toProfile');

		// User photo
		Route::post('set-photo', 'UserController@storePhoto');
		Route::post('random-photo', 'UserController@randomPhoto');
		Route::post('settings', 'UserController@storeSettings');
	});


	Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function() {
		Route::get('/', 'AdminController@index');
		Route::get('/users', 'AdminController@users');
		Route::get('/posts', 'AdminController@posts');
		Route::get('/reports', 'AdminController@reports');
		Route::get('/notifications', 'AdminController@notifications');
		Route::get('/categories', 'AdminController@categories');
		Route::get('/stats', 'AdminController@userStats');

		Route::group(['prefix' => 'chart'], function() {
			Route::get('users', 'AdminController@chartUsers');
			Route::get('posts', 'AdminController@chartPosts');
			// Route::get('reports', 'AdminController@chartReports');
			// Route::get('likes', 'AdminController@chartLikes');
			// Route::get('comments', 'AdminController@chartComments');
			// Route::get('feadr', 'AdminController@chartFeadr');
		});

		Route::group(['prefix' => 'edit'], function() {
			// Editing user
			Route::get('{author}', 'AdminController@editUser');
			Route::post('{author}', 'AdminController@updateUser');
			// Editing posts
			Route::get('{author}/{url}', 'AdminController@editPost');
		});
		Route::group(['prefix' => 'action'], function() {
			Route::post('/delete-user', 'AdminController@deleteUser');
			Route::post('/delete-post', 'AdminController@deletePost');
			Route::post('/delete-photo', 'AdminController@deletePhoto');
			Route::post('/set-rights', 'AdminController@setRights');
			Route::get('/update-report', 'AdminController@updateReport');
			Route::post('/save-category', 'AdminController@saveCategory');
			Route::post('/delete-category', 'AdminController@deleteCategory');
			Route::post('/send-notify', 'AdminController@sendNotify');
		});
	});
});

Route::get('/posts/{type}', 'ListPostsController@posts');

Route::group(['prefix' => 'search'], function() {
	Route::get('{text}', 'ListPostsController@search');
	Route::get('category/{category}', 'ListPostsController@category');
});

Route::get('/tag/{tagName}', 'ListPostsController@tag');
Route::get('/ref/{name}', 'ReferralController@referral');

Route::get('/{name}', 'ProfileController@profile');
Route::get('/{name}/{title}', 'Tools\DisplayController@display');