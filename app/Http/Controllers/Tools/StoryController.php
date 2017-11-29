<?php
namespace App\Http\Controllers\Tools;
use App\Http\Controllers\Controller;

use Validator;
use Response;
use App\Post;
use App\User;
use App\Category;
use Input;
use Auth;
use File;

class StoryController extends Controller {

	public function handler() {
		if(Input::get('state') !== null) {
			switch (Input::get('state')) {
				case 'save':
					return $this->publish('save');
					break;

				case 'publish':
					return $this->publish('publish');
					break;

				case 'preview':
					return $this->preview();
					break;

				default:
					return Response::json(['success' => false, 'errorText' => 'Invalid data! Try reload page.']);
					break;
			}
		}
		return Response::json(['success' => false, 'errorText' => 'Invalid data! Try reload page.']);
	}

	private function preview() {
		$data = Input::only('story');
		if(count($data) != 0) {
			// tags recording | max count tags : 22
			$tags = [];
			if(Input::has('tags'))
				$get_tags = Input::get('tags');
			if(isset($get_tags) && count($get_tags > 0)) {
				$get_tags = array_slice($get_tags, 0, 22);
				foreach ($get_tags as $key => $value) {
					$tags[] = $value;
				}
			}

			// main content formation for preview
			$content_main = [
				'title' => $data['story']['data']['story_title'],
				'description' => $data['story']['data']['story_description'],
			];


			// content other formation for preview

			$content_other = [];
			if(isset($data['story']['content'])) {
				foreach($data['story']['content'] as $key => $value) {
					$content_other[] = $value;
				}
			}

			return Response::json(['success' => true, 'content' => $content_main, 'cards' => $content_other, 'tags' => $tags]);
    	}
    	return Response::json(['success' => false, 'errorText' => 'Invalid data! Try reload page.']);
	}

	private function publish($state) {
		$data = Input::only('story');
		if(count($data) != 0) {
			// Validation the Main Data
			$validator = Validator::make($data['story']['data'], [
	            'photo_main'        => 'required',
	            'story_title'       => 'required|min:3|max:400',
	            'story_description' => 'required|min:3|max:2000',
	        ]);
			if ($validator->fails())
				return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);

			if ($validator->fails()) 
				return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);


			$errors_array = [];

	        // Checking if images are loaded ( main and facebook photo )
	        $data['story']['data']['photo_main'] = str_replace('..', '', $data['story']['data']['photo_main']);
	        if(!File::exists(public_path()."/files/temporary/".$data['story']['data']['photo_main']) && !File::exists(public_path()."/files/uploads/".$data['story']['data']['photo_main']))
	        	$errors_array[] = "The main photo not found. Please, upload a new image!";

			foreach ($data['story']['content'] as $key => $value) {
				$value = str_replace('..', '', $value);
		        if(!File::exists(public_path()."/files/temporary/".$value) && !File::exists(public_path()."/files/uploads/".$value))
		        	$errors_array[] = "The story content images not found. Please, upload a new image!</br>";
			}

	        if(count($errors_array) != 0)
	        	return Response::json(['success' => false, 'errorText' => $errors_array]);

	        // Moving main photo/ facebook photo
	        if(strpos($data['story']['data']['photo_main'], '/') !== false) {
	        	$main_photo = uniqid('feadz', true).".jpeg";
		        if(!File::move(public_path()."/files/temporary/".$data['story']['data']['photo_main'], public_path()."/files/uploads/".$main_photo))
		        	$errors_array[] = "An error occurred while moving the main photo. Please, upload a new image!";
		    } else $main_photo = $data['story']['data']['photo_main'];

		    $content = [];

			foreach ($data['story']['content'] as $key => $value) {
				if(strpos($value, '/') !== false) {
					$image = uniqid('feadz', true).".jpeg";
					File::move(public_path()."/files/temporary/".$value, public_path()."/files/uploads/".$image);
					$content[] = $image;
				} else $content[] = $value;
			}

			$content = serialize($content);

	        if(count($errors_array) != 0)
	        	return Response::json(['success' => false, 'errorText' => $errors_array]);

			// tags recording | max count tags : 22
			$tags = [];
			if(Input::has('tags'))
				$get_tags = Input::get('tags');
			if(isset($get_tags) && count($get_tags > 0)) {
				$get_tags = array_slice($get_tags, 0, 22);
				foreach ($get_tags as $key => $value) {
					$tags[] = $value;
				}
			}
			$tags = serialize($tags);

			// options recording
			$options = [];
			$options = serialize($options);

			// category
			if($data['story']['category'] != '') {
				$category = Category::where(['category' => $data['story']['category']])->first();
				if(!empty($category)) {
					$category = $data['story']['category'];
				} else {
					$category = null;
				}
			} else {
				$category = null;
			}

			// If there is a postID, then to update post
			$validator = Validator::make($data['story']['data'], [
				'postID' => 'required|integer|min:1',
			]);

			if (!$validator->fails()) {
				$owner = Post::select('author_name', 'user_id', 'url')->where(['id' => $data['story']['data']['postID'], 'type' => 'story'])->first();
				if(count($owner) != 0 && ($owner->user_id == Auth::user()->id || Auth::user()->role == 'admin')) {
					Post::where(['id' => $data['story']['data']['postID'], 'type' => 'story'])
						->update(['description_title'  => $data['story']['data']['story_title'],  
								  'description_text'   => $data['story']['data']['story_description'],
								  'description_image'  => $main_photo,
								  'content' => $content, 'permission' => 'public',
								  'options' => $options, 'tags' => $tags, 'state' => $state, 'category' => $category
					]);
					$link = '/'.$owner->author_name.'/'.$owner->url;
					return Response::json(['success' => true, 'link' => $link]);
				}
				return Response::json(['false' => true, 'errorText' => 'Invalid data(postID)']);
			}

			// Transliteration field title for URL
			$title_url = AdditionController::translit($data['story']['data']['story_title']);
			if(strlen($title_url) < 3)
				$title_url = 'story';
			else if(strlen($title_url) > 180)
				$title_url = substr($title_url, 0, 180); 


			// Insert a new post in DB
			$counter = -1;
			$url  = $title_url;
			while (true) {
				$result = Post::where(['url' => $title_url, 'author_name' => Auth::user()->name])->count();
				if($result == 0) {
					$post = new Post;
					$post->description_title = $data['story']['data']['story_title'];
					$post->description_text = $data['story']['data']['story_description'];
					$post->description_image = $main_photo;
					$post->content = $content;
					$post->type = 'story';
					$post->permission = 'public';
					$post->category = $category;
					$post->options = $options;
					$post->tags = $tags;
					$post->state = $state;
					$post->url = $title_url;
					$post->author_name = Auth::user()->name;
					$post->user_id = Auth::user()->id;
					$post->save();					
					$link = '/'.Auth::user()->name.'/'.$title_url;
					return Response::json(['success' => true, 'link' => $link]);
				}
				$title_url = $url.$counter;
				$counter--;
			}
		}
		return Response::json(['success' => false, 'errorText' => 'Invalid data! Try reload page.']);
	}
}