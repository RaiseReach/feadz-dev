<?php 
namespace App\Http\Controllers\Tools;
use App\Http\Controllers\Controller;

use DOMDocument;
use Validator;
use Response;
use App\Post;
use App\User;
use App\Category;
use Input;
use Auth;

class SnipController extends Controller {

    public function handler() {
        $data = Input::only('snip');
        if(count($data) != 0) {
            $validator = Validator::make($data['snip']['data'], [
                'url' => 'required|url',
            ]);
            if ($validator->fails())
                return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);

            // checking headers of the site
            $headers = @get_headers($data['snip']['data']['url'], 1);
            $headers = array_change_key_case($headers);

            if(!$headers || !stripos($headers[0], '200 OK ') === false)
                return Response::json(['success' => false, 'errorText' => 'This domain has opted out of the service. Please try another domain.']);
            elseif (isset($headers['x-frame-options']) && (stripos($headers['x-frame-options'], 'SAMEORIGIN') !== false || stripos($headers['x-frame-options'], 'deny') !== false) || isset($headers['content-security-policy'])) {
                return Response::json(['success' => false, 'errorText' => 'This domain has opted out of the service. Please try another domain.']);
            }

            // taking the title of the page
            $title = AdditionController::get_title_site($data['snip']['data']['url']);
            if($title == '') $title = 'Snip of '.$data['snip']['data']['url'];

            $title_url = AdditionController::translit($title);
            if(strlen($title_url) < 3)
                $title_url = 'snip';
            else if(strlen($title_url) > 180)
                $title_url = substr($title_url, 0, 180); 
            
            // content creation
            $content = [
                'iframe_url' => $data['snip']['data']['url'],
                'title'      => $title,
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
            if($data['snip']['category'] != '') {
                $category = Category::where(['category' => $data['snip']['category']])->first();
                if(!empty($category)) {
                    $category = $data['snip']['category'];
                } else {
                    $category = null;
                }
            } else {
                $category = null;
            }

            // If there is a postID, then to update post
            $validator = Validator::make($data['snip']['data'], [
                'postID' => 'required|integer|min:1',
            ]);


            if (!$validator->fails()) {
                $owner = Post::select('author_name', 'user_id', 'url')->where(['id' => $data['snip']['data']['postID'], 'type' => 'snip'])->first();
                if(count($owner) != 0 && ($owner->user_id == Auth::user()->id || Auth::user()->role == 'admin')) {
                    Post::where(['id' => $data['snip']['data']['postID'], 'type' => 'snip'])
                        ->update([ 'description_title' => $title, 'description_text' => $title, 'content' => $content, 'tags' => $tags, 'options' => $options,
                                   'category' => $category
                    ]);
                    $link = '/'.$owner->author_name.'/'.$owner->url;
                    return Response::json(['success' => true, 'link' => $link]);
                }
                return Response::json(['false' => true, 'errorText' => 'Invalid data(postID)']);
            }

            // Insert a new post in DB
            $counter = -1;
            $url  = $title_url;
            while (true) {
                $result = Post::where(['url' => $title_url, 'author_name' => Auth::user()->name])->count();
                if($result == 0) {
                    $post = new Post;
                    $post->description_title = $title;
                    $post->description_text = $title;
                    $post->description_image = '../../source/img/snip.png';
                    $post->content = $content;
                    $post->type = 'snip';
                    $post->permission = 'public';
                    $post->category = $category;
                    $post->options = $options;
                    $post->tags = $tags;
                    $post->state = 'publish';
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
    }
}
