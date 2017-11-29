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

class FlipcardsController extends Controller {

	public function handler() {
		if(Input::get('state') !== null) {
			switch (Input::get('state')) {
				case 'preview':
					return $this->preview();
					break;

				case 'save':
					return $this->publish('save');
					break;

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
		$data = Input::only('flipcards');
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
				'author' => Auth::user()->name,
				'title' => $data['flipcards']['data']['flipcards_title'],
				'description' => $data['flipcards']['data']['flipcards_description'],
			];


			// content other formation for preview
	        $themes = [
	        	'default' => '#ff5504',
	        	'#ff5504', '#795446', '#9d9d9d', '#3d4db6',
	        	'#000000', '#ccdd1e', '#ffec16', '#ff9700',
	        	'#9c1ab1', '#6733b8', '#00a6f6', '#009587',
	        	'#ffffff', '#47af4a', '#eb1460', '#363f46'
	        ];
			$content_other = [];
			foreach ($data['flipcards']['cards'] as $key => $value) {
				$card_type_front  = isset($value['card_type_front']) ? $value['card_type_front'] : '';
				$card_type_back   = isset($value['card_type_back']) ? $value['card_type_back'] : '';
	        	$front_card_text  = isset($value['front_card_text']) ? $value['front_card_text'] : '';
	        	$back_card_text   = isset($value['back_card_text']) ? $value['back_card_text'] : '';
				$front_card_image = isset($value['front_card_image']) ? $value['front_card_image'] : '';
				$back_card_image  = isset($value['back_card_image']) ? $value['back_card_image'] : '';
				$front_card_theme = (isset($value['front_card_theme']) && in_array($value['front_card_theme'], $themes)) ? $value['front_card_theme']: $themes['default'];
	        	$back_card_theme  = (isset($value['back_card_theme']) && in_array($value['back_card_theme'], $themes)) ? $value['back_card_theme'] : $themes['default'];

	        	$content_other[] = [
	        		'card_item_title'  => $value['card_item_title'],
	        		'card_type_front'  => $card_type_front,
	        		'card_type_back'   => $card_type_back,
	        		'front_card_image' => $front_card_image,
	        		'back_card_image'  => $back_card_image,
	        		'front_card_theme' => $front_card_theme,
	        		'back_card_theme'  => $back_card_theme,
	        		'front_card_text'  => $front_card_text,
	        		'back_card_text'   => $back_card_text,
	        	];
			}

			return Response::json(['success' => true, 'content' => $content_main, 'cards' => $content_other, 'tags' => $tags]);
    	}
    	return Response::json(['success' => false, 'errorText' => 'Invalid data! Try reload page.']);
	}

	private function publish($state) {
		$data = Input::only('flipcards');
		if(count($data) != 0) {
			// Validation the Main Data
			$validator = Validator::make($data['flipcards']['data'], [
	            'photo_main'            => 'required',
	            'flipcards_title'       => 'required|min:3|max:400',
	            'flipcards_description' => 'required|min:3|max:2000',
	        ]);
			if ($validator->fails())
				return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);

			// Validation the Cards
			foreach ($data['flipcards']['cards'] as $key => $value) {
				$validator = Validator::make($value ,[
					'card_item_title' => 'required|min:3|max:45',
					'card_type_front' => 'required',
					'card_type_back'  => 'required',
				]);
				if ($validator->fails()) 
					return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);
	        }

	        $errors_array = [];
	        // min card 1; max cards = 15; array truncation
	        if(count($data['flipcards']['cards']) == 0)
	        	$errors_array[] = "Minimum cards in post must be 1!";
	        $data['flipcards']['cards'] = array_slice($data['flipcards']['cards'], 0, 15);

	        // Checking if images are loaded 
	        $data['flipcards']['data']['photo_main'] = str_replace('..', '', $data['flipcards']['data']['photo_main']);
	        if(!File::exists(public_path()."/files/temporary/".$data['flipcards']['data']['photo_main']) && !File::exists(public_path()."/files/uploads/".$data['flipcards']['data']['photo_main']))
	        	$errors_array[] = "The main photo not found. Please, upload a new image!";


	        if(count($errors_array) != 0)
	        	return Response::json(['success' => false, 'errorText' => $errors_array]);

	        // Checking if images are loaded or exist youtube video ( card post )
	        $available_types = ['image', 'text'];
	        foreach ($data['flipcards']['cards'] as $key => $value) {
	        	if(!in_array($value['card_type_front'], $available_types))
	        		return Response::json(['success' => false, 'errorText' => ['Unknown card type. Please, try reload page!']]);
	        	if(!in_array($value['card_type_back'], $available_types))
	        		return Response::json(['success' => false, 'errorText' => ['Unknown card type. Please, try reload page!']]);

	        	// check front card
	        	if($value['card_type_front'] == "image") {
	        		$value['front_card_image'] = str_replace('..', '', $value['front_card_image']);
	        		if($value['front_card_image'] != "") {
	        			if(!File::exists(public_path()."/files/temporary/".$value['front_card_image']) && !File::exists(public_path()."/files/uploads/".$value['front_card_image'])) {
	        				$errors_array[] = "The front card image not found. Please, upload a new image!";
	        			}
	        		} else {
	        			$errors_array[] = "The photo or text on front card must be filled!";
	        		}
	        	} else {
	        		$validator = Validator::make($value, [
	        			'front_card_text' => 'required|min:3|max:100'
	        		]);
					if ($validator->fails()) 
						return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);
	        	}

	        	// check back card
	        	if($value['card_type_back'] == "image") {
	        		$value['back_card_image'] = str_replace('..', '', $value['back_card_image']);
	        		if($value['back_card_image'] != "") {
	        			if(!File::exists(public_path()."/files/temporary/".$value['back_card_image']) && !File::exists(public_path()."/files/uploads/".$value['back_card_image'])) {
	        				$errors_array[] = "The back card image not found. Please, upload a new image!";
	        			}
	        		} else {
	        			$errors_array[] = "The photo or text on back card must be filled!";
	        		}
	        	} else {
	        		$validator = Validator::make($value, [
	        			'back_card_text' => 'required|min:3|max:100'
	        		]);
					if ($validator->fails()) 
						return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);
	        	}

	        	if(count($errors_array) != 0)
	        		return Response::json(['success' => false, 'errorText' => $errors_array]);
	        }

	        // Moving main photo
	        if(strpos($data['flipcards']['data']['photo_main'], '/') !== false) {
	        	$main_photo = uniqid('feadz', true).".jpeg";
		        if(!File::move(public_path()."/files/temporary/".$data['flipcards']['data']['photo_main'], public_path()."/files/uploads/".$main_photo))
		        	$errors_array[] = "An error occurred while moving the main photo. Please, upload a new image!";
	    	} else $main_photo = $data['flipcards']['data']['photo_main'];

	        if(count($errors_array) != 0)
	        	return Response::json(['success' => false, 'errorText' => $errors_array]);

	        $themes = [
	        	'default' => '#ff5504',
	        	'#ff5504', '#795446', '#9d9d9d', '#3d4db6',
	        	'#000000', '#ccdd1e', '#ffec16', '#ff9700',
	        	'#9c1ab1', '#6733b8', '#00a6f6', '#009587',
	        	'#ffffff', '#47af4a', '#eb1460', '#363f46'
	        ];
	        // Content cards creation for flipcards
	        $content_flipcards = [];
	        foreach ($data['flipcards']['cards'] as $key => $value) {
	        	if($value['card_type_front'] == "image") {
	        		if(strpos($value['front_card_image'], '/') !== false) {
	        			$front_card_image = uniqid('feadz', true).".jpeg";
						if(!File::move(public_path()."/files/temporary/".$value['front_card_image'], public_path()."/files/uploads/".$front_card_image))
							$errors_array[] = "An error occurred while moving the image card. Please, try reload page!";
					} else $front_card_image = $value['front_card_image'];
				}

	        	if($value['card_type_back'] == "image") {
	        		if(strpos($value['back_card_image'], '/') !== false) {
		        		$back_card_image = uniqid('feadz', true).".jpeg";
						if(!File::move(public_path()."/files/temporary/".$value['back_card_image'], public_path()."/files/uploads/".$back_card_image))
							$errors_array[] = "An error occurred while moving the image card. Please, try reload page!";
					} else $back_card_image = $value['back_card_image']; 
				}

	        	$front_card_text  = isset($value['front_card_text']) ? $value['front_card_text'] : '';
	        	$back_card_text   = isset($value['back_card_text']) ? $value['back_card_text'] : '';
				$front_card_image = isset($value['front_card_image']) ? $front_card_image : '';
				$back_card_image  = isset($value['back_card_image']) ? $back_card_image : '';
				$front_card_theme = (isset($value['front_card_theme']) && in_array($value['front_card_theme'], $themes)) ? $value['front_card_theme']: $themes['default'];
	        	$back_card_theme  = (isset($value['back_card_theme']) && in_array($value['back_card_theme'], $themes)) ? $value['back_card_theme'] : $themes['default'];

	        	$content_flipcards[] = [
	        		'card_item_title'  => $value['card_item_title'],
	        		'card_type_front'  => $value['card_type_front'],
	        		'card_type_back'   => $value['card_type_back'],
	        		'front_card_image' => $front_card_image,
	        		'back_card_image'  => $back_card_image,
	        		'front_card_theme' => $front_card_theme,
	        		'back_card_theme'  => $back_card_theme,
	        		'front_card_text'  => $front_card_text,
	        		'back_card_text'   => $back_card_text,
	        	];


	        }

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
			if($data['flipcards']['category'] != '') {
				$category = Category::where(['category' => $data['flipcards']['category']])->first();
				if(!empty($category)) {
					$category = $data['flipcards']['category'];
				} else {
					$category = null;
				}
			} else {
				$category = null;
			}

			// If there is a postID, then to update post
			$validator = Validator::make($data['flipcards']['data'], [
				'postID' => 'required|integer|min:1',
			]);

			if (!$validator->fails()) {
				$owner = Post::select('author_name', 'user_id', 'url')->where(['id' => $data['flipcards']['data']['postID'], 'type' => 'flipcards'])->first();
				if(count($owner) != 0 && ($owner->user_id == Auth::user()->id || Auth::user()->role == 'admin')) {
					Post::where(['id' => $data['flipcards']['data']['postID'], 'type' => 'flipcards'])
						->update(['description_title'  => $data['flipcards']['data']['flipcards_title'],  
								  'description_text'  => $data['flipcards']['data']['flipcards_description'],
								  'description_image' => $main_photo, 'category' => $category,
								  'content' => serialize($content_flipcards), 'permission' => 'public',
								  'options' => $options, 'tags' => $tags, 'state' => $state
					]);
					$link = '/'.$owner->author_name.'/'.$owner->url;
					return Response::json(['success' => true, 'link' => $link]);
				}
				return Response::json(['false' => true, 'errorText' => 'Invalid data(postID)']);
			}

			// Transliteration field title for URL
			$title_url = AdditionController::translit($data['flipcards']['data']['flipcards_title']);
			if(strlen($title_url) < 3)
				$title_url = 'flipcards';
			else if(strlen($title_url) > 180)
				$title_url = substr($title_url, 0, 180); 


			// Insert a new post in DB
			$counter = -1;
			$url  = $title_url;
			while (true) {
				$result = Post::where(['url' => $title_url, 'author_name' => Auth::user()->name])->count();
				if($result == 0) {
                    $post = new Post;
                    $post->description_title = $data['flipcards']['data']['flipcards_title'];
                    $post->description_text = $data['flipcards']['data']['flipcards_description'];
                    $post->description_image = $main_photo;
                    $post->content = serialize($content_flipcards);
                    $post->type = 'flipcards';
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