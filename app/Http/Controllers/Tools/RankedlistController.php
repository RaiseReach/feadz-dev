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
use DB;

class RankedlistController extends Controller {
	
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
		$data = Input::only('rankedlist');
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
				'title' => $data['rankedlist']['data']['rankedlist_title'],
				'description' => $data['rankedlist']['data']['rankedlist_description'],
			];

			$content_other = [];
			foreach ($data['rankedlist']['cards'] as $key => $value) {
				if(!in_array($value['type_card'], ['image', 'video']))
					$value['type_card'] = 'image';

				if(!isset($value['image_card']) && !isset($value['youtube_clip'])) {
					$value['image_card'] = '';
					$value['type_card'] = 'image';
				}

				if($value['type_card'] == 'video') { 
					$youtube_content = $value['youtube_clip'];
					$youtube_content = file_get_contents('https://www.youtube.com/oembed?url='.$youtube_content.'&format=json');
					$youtube_content = json_decode($youtube_content, true);
					if(!is_array($youtube_content)) {
						$youtube_content = "123123";
					} else {
						$youtube_content = $youtube_content['html'];
					}
					$image = "";
				} else {
					$youtube_content = "";
					$image = $value['image_card'];
				}

				// card formation for preview
				$content_other[] = [
					'item_title'   => $value['item_title'],
					'type_card'    => $value['type_card'],
					'youtube_clip' => $youtube_content,
					'caption_card' => $value['caption_card'],
					'image_card'   => $image,
				];

			}
			return Response::json(['success' => true, 'content' => $content_main, 'cards' => $content_other, 'tags' => $tags]);
    	}
    	return Response::json(['success' => false, 'errorText' => 'Invalid data! Try reload page.']);
	}
	
	private function publish($state) {
		$data = Input::only('rankedlist');
		if(count($data) != 0) {
			// Validation the Main Data
			$validator = Validator::make($data['rankedlist']['data'], [
	            'photo_main'            => 'required',
	            'rankedlist_title'      => 'required|min:3|max:400',
	            'rankedlist_description' => 'required|min:3|max:2000',
	        ]);
			if ($validator->fails())
				return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);


			$errors_array = [];
			// Validation the Cards
			foreach ($data['rankedlist']['cards'] as $key => $value) {
				$validator = Validator::make($value ,[
					'item_title'   => 'required|min:3',
					'caption_card' => 'required|min:3',
					'type_card'    => 'required',
				]);
				if ($validator->fails()) 
					return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);

					if(!isset($value['image_card']) && !isset($value['youtube_clip'])) {
						return Response::json(['success' => false, 'errorText' => ['Card must be filled. Upload a video or image!']]);
					}
	        }

	        // min card 1; max cards = 15; array truncation
	        if(count($data['rankedlist']['cards']) == 0)
	        	$errors_array[] = "Minimum cards in post must be 1!";
	        $data['rankedlist']['cards'] = array_slice($data['rankedlist']['cards'], 0, 15);

	        // Checking if images are loaded 
	        $data['rankedlist']['data']['photo_main'] = str_replace('..', '', $data['rankedlist']['data']['photo_main']);
	        if(!File::exists(public_path()."/files/temporary/".$data['rankedlist']['data']['photo_main']) && !File::exists(public_path()."/files/uploads/".$data['rankedlist']['data']['photo_main'])) 
	        	$errors_array[] = "The main photo not found. Please, upload a new image!";

	        if(count($errors_array) != 0)
	        	return Response::json(['success' => false, 'errorText' => $errors_array]);

	        // Checking if images are loaded or exist youtube video ( card post )
	        $available_types = ['image', 'video'];
	        foreach ($data['rankedlist']['cards'] as $key => $value) {
	        	if(!in_array($value['type_card'], $available_types))
	        		return Response::json(['success' => false, 'errorText' => ['Unknown card type. Please, try reload page!']]);

	        	if($value['type_card'] == 'image') {
	        		$value['image_card'] = str_replace('..', '', $value['image_card']);
	        		if($value['image_card'] != "") {
	        			if(!File::exists(public_path()."/files/temporary/".$value['image_card']) && !File::exists(public_path()."/files/uploads/".$value['image_card'])) {
	        				$errors_array[] = "The card image not found. Please, upload a new image!";
	        			}
	        		} else {
	        			$errors_array[] = "The photo or video must be filled!";
	        		}
	        	} else {
	        		$validator = Validator::make($value, [
	        			'youtube_clip' => 'url',
	        		]);
					if ($validator->fails()) 
						return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);
        			$youtube_content = file_get_contents('https://www.youtube.com/oembed?url='.$value['youtube_clip'].'&format=json');
        			$youtube_content = json_decode($youtube_content, true);
        			if(!is_array($youtube_content))
        				$errors_array[] = "The Youtube video does not exist. Try use another video!";
	        	} 
	        	if(count($errors_array) != 0)
	        		return Response::json(['success' => false, 'errorText' => $errors_array]);
	        }

	        // Moving main photo
	        if(strpos($data['rankedlist']['data']['photo_main'], '/') !== false) {
	        	$main_photo     = uniqid('feadz', true).".jpeg";
	        	if(!File::move(public_path()."/files/temporary/".$data['rankedlist']['data']['photo_main'], public_path()."/files/uploads/".$main_photo))
	        		$errors_array[] = "An error occurred while moving the main photo. Please, upload a new image!";
	        } else $main_photo = $data['rankedlist']['data']['photo_main'];

	        if(count($errors_array) != 0)
	        	return Response::json(['success' => false, 'errorText' => $errors_array]);

	        // Content cards creation for rankedlist 
	        $content_rankedlist = [];
	        foreach ($data['rankedlist']['cards'] as $key => $value) {
	        	if($value['type_card'] == 'video') {
					$youtube_content = $value['youtube_clip'];
					$youtube_content = file_get_contents('https://www.youtube.com/oembed?url='.$youtube_content.'&format=json');
					$youtube_content = json_decode($youtube_content, true);
					$youtube_content = $youtube_content['html'];
					$content_rankedlist[] = [
						'item_title'   => $value['item_title'],
						'type_card'    => $value['type_card'],
						'caption_card' => $value['caption_card'],
						'youtube_clip' => $youtube_content,
					];
	        	} else {
	        		if(strpos($value['image_card'], '/') !== false) {
	        			$image_card = uniqid('feadz', true).".jpeg";
						if(!File::move(public_path()."/files/temporary/".$value['image_card'], public_path()."/files/uploads/".$image_card))
							$errors_array[] = "An error occurred while moving the image card. Please, try reload page!";
					} else $image_card = $value['image_card'];
			        if(count($errors_array) != 0)
			        	return Response::json(['success' => false, 'errorText' => $errors_array]);

					$content_rankedlist[] = [
						'item_title'   => $value['item_title'],
						'type_card'    => $value['type_card'],
						'image_card'   => $image_card,
						'caption_card' => $value['caption_card'], 
					];
	        	}
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
			if($data['rankedlist']['category'] != '') {
				$category = Category::where(['category' => $data['rankedlist']['category']])->first();
				if(!empty($category)) {
					$category = $data['rankedlist']['category'];
				} else {
					$category = null;
				}
			} else {
				$category = null;
			}

			// If there is a postID, then to update post
			$validator = Validator::make($data['rankedlist']['data'], [
				'postID' => 'required|integer|min:1',
			]);

			if (!$validator->fails()) {
				$owner = Post::select('author_name', 'user_id', 'url')->where(['id' => $data['rankedlist']['data']['postID'], 'type' => 'rankedlist'])->first();
				if(count($owner) != 0 && ($owner->user_id == Auth::user()->id || Auth::user()->role == 'admin')) {
					Post::where(['id' => $data['rankedlist']['data']['postID'], 'type' => 'rankedlist'])
						->update(['description_title'  => $data['rankedlist']['data']['rankedlist_title'],  
								  'description_text'  => $data['rankedlist']['data']['rankedlist_description'],
								  'description_image' => $main_photo,
								  'content' => serialize($content_rankedlist), 'permission' => 'public',
								  'options' => $options, 'tags' => $tags, 'state' => $state, 'category' => $category
					]);
					$link = '/'.$owner->author_name.'/'.$owner->url;
					return Response::json(['success' => true, 'link' => $link]);
				}
				return Response::json(['false' => true, 'errorText' => 'Invalid data(postID)']);
			}

			// Transliteration field title for URL
			$title_url = AdditionController::translit($data['rankedlist']['data']['rankedlist_title']);
			if(strlen($title_url) < 3)
				$title_url = 'rankedlist';
			else if(strlen($title_url) > 180)
				$title_url = substr($title_url, 0, 180); 


			// Insert a new post in DB
			$counter = -1;
			$url  = $title_url;
			while (true) {
				$result = Post::where(['url' => $title_url, 'author_name' => Auth::user()->name])->count();
				if($result == 0) {
					$post = new Post;
					$post->description_title = $data['rankedlist']['data']['rankedlist_title'];
					$post->description_text = $data['rankedlist']['data']['rankedlist_description'];
					$post->description_image = $main_photo;
					$post->content = serialize($content_rankedlist);
					$post->type = 'rankedlist';
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

	public function vote() {

		if(!Input::has("pid") || !Input::has('cid'))
			 return Response::json(['success' => false, 'errorText' => 'Invalid data!']);

		$validator = Validator::make(Input::get(), [
			'pid'  => 'required|integer|min:1',
			'cid' => 'required|integer|min:1'
		]);

		if (!$validator->fails()) {
			if(DB::table('votes')->where(['user_id' => Auth::user()->id, 'post_id' => Input::get('pid'), 'card_id' => Input::get('cid')])->count() != 0)
				return Response::json(['success' => false, 'errorText' => 'You already voted!']);
			if(Post::where(['id' => Input::get('pid'), 'type' => 'rankedlist', 'state' => 'publish'])->count() == 0)
				return Response::json(['success' => false, 'errorText' => 'Invalid data!!']);
			DB::table('votes')->insert(['user_id' => Auth::user()->id, 'post_id' => Input::get('pid'), 'card_id' => Input::get('cid')]);
			$all_votes = DB::table('votes')->where(['post_id' => Input::get('pid'), 'card_id' => Input::get('cid')])->count();
			return Response::json(['success' => true, 'votes' => $all_votes]);
		}
		return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);
	}
}