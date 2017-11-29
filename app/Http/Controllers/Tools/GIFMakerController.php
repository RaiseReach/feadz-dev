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
use Image;
use Session;
use File;

class GIFMakerController extends Controller {

	public function handler() {
		if(Input::get('state') !== null) {
			switch (Input::get('state')) {
				case 'save':
					return $this->publish('save');
					break;

				case 'preview':
					return $this->preview();

				case 'publish':
					return $this->publish('publish');
					break;

				default:
					return Response::json(['success' => false, 'errorText' => 'Invalid data! Try reload page.']);
					break;
			}
		}
		return Response::json(['success' => false, 'errorText' => 'Invalid data! Try reload page.']);
	}

	private function preview() {
		$data = Input::only('gifmaker');
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
				'title' => $data['gifmaker']['data']['gifmaker_title'],
				'description' => $data['gifmaker']['data']['gifmaker_description'],
			];


			// content other formation for preview

			$url_gif = $data['gifmaker']['gif'];

			return Response::json(['success' => true, 'content' => $content_main, 'gif' => $url_gif, 'tags' => $tags]);
    	}
    	return Response::json(['success' => false, 'errorText' => 'Invalid data! Try reload page.']);
	}

	private function publish($state) {
		$data = Input::only('gifmaker');
		if(count($data) != 0) {
			// Validation the Main Data
			$validator = Validator::make($data['gifmaker']['data'], [
	            'photo_main'           => 'required',
	            'gifmaker_title'       => 'required|min:3|max:400',
	            'gifmaker_description' => 'required|min:3|max:2000',
	        ]);
			if ($validator->fails())
				return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);

			// Validation the Cards
			$validator = Validator::make($data['gifmaker'], [
				'gif' => 'required',
			]);
			if ($validator->fails()) 
				return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);

			$errors_array = [];

	        // Checking if images are loaded ( main and facebook photo )
	        $data['gifmaker']['data']['photo_main'] = str_replace('..', '', $data['gifmaker']['data']['photo_main']);
	        if(!File::exists(public_path()."/files/temporary/".$data['gifmaker']['data']['photo_main']) && !File::exists(public_path()."/files/uploads/".$data['gifmaker']['data']['photo_main']))
	        	$errors_array[] = "The main photo not found. Please, upload a new image!";

	        $data['gifmaker']['gif'] = str_replace('..', '', $data['gifmaker']['gif']);
	        if(!File::exists(public_path()."/files/temporary/".$data['gifmaker']['gif']) && !File::exists(public_path()."/files/uploads/".$data['gifmaker']['gif']))
	        	$errors_array[] = "The GIF-image not found. Please, create a new GIF-image!";

	        if(count($errors_array) != 0)
	        	return Response::json(['success' => false, 'errorText' => $errors_array]);

	        // Moving main photo/ facebook photo / main gif
	        if(strpos($data['gifmaker']['gif'], '/') !== false) {
	        	 $main_gif  = uniqid('feadz', true).".gif";
		        if(!File::move(public_path()."/files/temporary/".$data['gifmaker']['gif'], public_path()."/files/uploads/".$main_gif))
		        	$errors_array[] = "An error occurred while moving the GIF-image. Please, create a new GIF-image!";
		    } else  $main_gif = $data['gifmaker']['gif'];
		    if(strpos($data['gifmaker']['data']['photo_main'], '/') !== false) {
		    	$main_photo = uniqid('feadz', true).".jpeg";
		        if(!File::move(public_path()."/files/temporary/".$data['gifmaker']['data']['photo_main'], public_path()."/files/uploads/".$main_photo))
		        	$errors_array[] = "An error occurred while moving the main photo. Please, upload a new image!";
		    } else $main_photo = $data['gifmaker']['data']['photo_main'];

	        if(count($errors_array) != 0)
	        	return Response::json(['success' => false, 'errorText' => $errors_array]);

	        $main_video = str_replace('.gif', '.mp4', $main_gif);
			$content[] = [
				'gif' => $main_gif,
				'video' => $main_video,
			];
			$content = serialize($content); 

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
			if($data['gifmaker']['category'] != '') {
				$category = Category::where(['category' => $data['gifmaker']['category']])->first();
				if(!empty($category)) {
					$category = $data['gifmaker']['category'];
				} else {
					$category = null;
				}
			} else {
				$category = null;
			}

			// If there is a postID, then to update post
			$validator = Validator::make($data['gifmaker']['data'], [
				'postID' => 'required|integer|min:1',
			]);

			if (!$validator->fails()) {
				$owner = Post::select('author_name', 'user_id', 'url')->where(['id' => $data['gifmaker']['data']['postID'], 'type' => 'gifmaker'])->first();
				if(count($owner) != 0 && ($owner->user_id == Auth::user()->id || Auth::user()->role == 'admin')) {
					Post::where(['id' => $data['gifmaker']['data']['postID'], 'type' => 'gifmaker'])
						->update(['description_title'  => $data['gifmaker']['data']['gifmaker_title'],  
								  'description_text'   => $data['gifmaker']['data']['gifmaker_description'],
								  'description_image'  => $main_photo, 'category' => $category,
								  'content' => $content, 'permission' => 'public',
								  'options' => $options, 'tags' => $tags, 'state' => $state
					]);
					$link = '/'.$owner->author_name.'/'.$owner->url;
					return Response::json(['success' => true, 'link' => $link]);
				}
				return Response::json(['false' => true, 'errorText' => 'Invalid data(postID)']);
			}

			// Transliteration field title for URL
			$title_url = AdditionController::translit($data['gifmaker']['data']['gifmaker_title']);
			if(strlen($title_url) < 3)
				$title_url = 'gifmaker';
			else if(strlen($title_url) > 180)
				$title_url = substr($title_url, 0, 180); 


			// Insert a new post in DB
			$counter = -1;
			$url  = $title_url;
			while (true) {
				$result = Post::where(['url' => $title_url, 'author_name' => Auth::user()->name])->count();
				if($result == 0) {
					$post = new Post;
					$post->description_title = $data['gifmaker']['data']['gifmaker_title'];
					$post->description_text = $data['gifmaker']['data']['gifmaker_description'];
					$post->description_image = $main_photo;
					$post->content = $content;
					$post->type = 'gifmaker';
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

	public function createGIF() {
		$data = Input::only('gifmaker');
		if(count($data) != 0) {
			$validator = Validator::make($data['gifmaker']['create'], [
				'start_time'  => 'required|integer',
				'end_time'    => 'required|integer|in:1,2,3,4,5',
				'color'       => 'required|integer|in:0,1,2,3,4,5,6,7',
				'variant'     => 'required|integer|in:1,2',
				'font_family' => 'required|integer|in:0,1,2',
				'font_size'   => 'required|integer|in:0,1,2',
				'caption'     => 'max:12'
			]);

			if ($validator->fails())
				return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);

			$data = $data['gifmaker']['create'];
			$set_url = "http://146.185.164.150/handle.php?youtube_url=".$data['video_youtube']."&length=".$data['end_time']."&start_time=".$data['start_time']."&caption=".$data['caption']."&color=".$data['color']."&font_size=".$data['font_size']."&font_family=".$data['font_family']."&key=onlyforpimboo&variant=".$data['variant']."&filename=".$data['filename_blob'];
			$response = file_get_contents($set_url);
			if(json_decode($response, true)) {
				$response = json_decode($response, true);
				if($response['success'] == true) {
					// if folder not found then create
					$main_path = public_path('files/temporary/'.Session::getId());
					if(!File::exists($main_path)) 
						File::makeDirectory($main_path);

					// generation of unique names for files
					$uniq_name  = uniqid('feadz', true);
					$gif = $uniq_name.'.gif';
					$video = $uniq_name.'.mp4';
					$thumbnail_main_photo = uniqid('feadz', true).'.jpeg';

					// saving images from response
					Image::make($response['main_photo'])->save($main_path . '/' . $thumbnail_main_photo);
					copy($response['gif'], $main_path . '/' . $gif);
					copy($response['video'], public_path()."/files/uploads/" . $video);

					$gif = Session::getId() . '/' . $gif;
					$thumbnail_main_photo = Session::getId() . '/' . $thumbnail_main_photo;

					return Response::json(['success' => true, 'thumbnail_main' => $thumbnail_main_photo,  'gif' => $gif]);
				}
				return Response::json(['success' => false, 'errorText' => $response['errorText']]);
			}
			return Response::json(['success' => false, 'errorText' => 'Unknown error. Please reload the page and try again.']);
		}
		return Response::json(['success' => false, 'errorText' => 'Invalid data! Try reload page.']);
	}
}
