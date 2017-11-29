<?php

namespace App\Http\Controllers\Tools;
use App\Http\Controllers\Controller;

use Input;
use Response;
use Validator;
use File;
use Image;
use App\Post;
use App\Category;
use Auth;

class MemeController extends Controller {
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

	private function publish($state) {
		$data = Input::only('meme');
		if(count($data) != 0) {
			$validator = Validator::make($data['meme'], [
				'top_text' => 'max:24',
				'bottom_text' => 'max:24',
				'color_top' => 'required',
				'color_bottom' => 'required',
				'type' => 'required',
				'size_top' => 'required|integer',
				'size_bottom' => 'required|integer',
				'main_meme'  => 'required|min:1',
				'meme_title' => 'required|min:3|max:400',
				'meme_description' => 'required|min:3|max:2000',
			]);

			if ($validator->fails()) 
				return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);

			$errors_array = [];
			if($data['meme']['type'] == 'popular') {
				$path = public_path('/');
			} elseif($data['meme']['type'] == 'new') {
				if (strripos($data['meme']['main_meme'], '/') === false) {
					$path = public_path('files/uploads/');
				} else {
					$path = public_path('files/temporary/');
				}
				
			} else {
				return Response::json(['success' => false, 'errorText' => 'Invalid data! Try reload page.']);
			}

	        $data['meme']['main_meme'] = str_replace('..', '', $data['meme']['main_meme']);
	        if(!File::exists($path.$data['meme']['main_meme']) && !File::exists(public_path()."/files/uploads/".$data['meme']['main_meme'])) {
	        	$errors_array[] = "The meme photo not found. Please, upload a new image!";
	        }

	        if(count($errors_array) != 0)
	        	return Response::json(['success' => false, 'errorText' => $errors_array]);

	        $memeFileName = uniqid('feadz' , true) . '.png';

	        $meme = Image::make($path . $data['meme']['main_meme'])->resize(674, 380);
			$meme->text($data['meme']['top_text'], 337, 95, function($font) use($data) {
				$font->file(public_path('/source/fonts/Prompt-Regular.ttf'));
			    $font->size($data['meme']['size_top'] * 12);
			    $font->color($data['meme']['color_top']);
			    $font->align('center');
			    $font->valign('center');
			});
			$meme->text($data['meme']['bottom_text'], 337, 285, function($font) use($data) {
				$font->file(public_path('/source/fonts/Prompt-Regular.ttf'));
			    $font->size($data['meme']['size_bottom'] * 12);
			    $font->color($data['meme']['color_bottom']);
			    $font->align('center');
			    $font->valign('center');
			});
			$meme->save(public_path('/files/uploads/' . $memeFileName));


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
			if($data['meme']['category'] != '') {
				$category = Category::where(['category' => $data['meme']['category']])->first();
				if(!empty($category)) {
					$category = $data['meme']['category'];
				} else {
					$category = null;
				}
			} else {
				$category = null;
			}


			// If there is a postID, then to update post
			$validator = Validator::make($data['meme']['data'], [
				'postID' => 'required|integer|min:1',
			]);

			if (!$validator->fails()) {
				$owner = Post::select('author_name', 'user_id', 'url')->where(['id' => $data['meme']['data']['postID'], 'type' => 'meme'])->first();
				if(count($owner) != 0 && ($owner->user_id == Auth::user()->id || Auth::user()->role == 'admin')) {
					Post::where(['id' => $data['meme']['data']['postID'], 'type' => 'meme'])
						->update(['description_title'  => $data['meme']['meme_title'],  
								  'description_text'  => $data['meme']['meme_description'],
								  'description_image' => $memeFileName, 'category' => $category,
								  'content' => $memeFileName, 'permission' => 'public',
								  'options' => $options, 'tags' => $tags, 'state' => $state
					]);
					$link = '/'.$owner->author_name.'/'.$owner->url;
					return Response::json(['success' => true, 'link' => $link]);
				}
				return Response::json(['false' => true, 'errorText' => 'Invalid data(postID)']);
			}

			// Transliteration field title for URL
			$title_url = AdditionController::translit($data['meme']['meme_title']);
			if(strlen($title_url) < 3)
				$title_url = 'meme';
			else if(strlen($title_url) > 180)
				$title_url = substr($title_url, 0, 180); 


			// Insert a new post in DB
			$counter = -1;
			$url  = $title_url;
			while (true) {
				$result = Post::where(['url' => $title_url, 'author_name' => Auth::user()->name])->count();
				if($result == 0) {
                    $post = new Post;
                    $post->description_title = $data['meme']['meme_title'];
                    $post->description_text = $data['meme']['meme_description'];
                    $post->description_image = $memeFileName;
                    $post->content = $memeFileName;
                    $post->type = 'meme';
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