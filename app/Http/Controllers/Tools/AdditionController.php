<?php 
namespace App\Http\Controllers\Tools;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DOMDocument;
use Validator;
use App\Post;
use Response;
use Session;
use Input;
use File;
use Auth;

class AdditionController extends Controller {

    // Upload image, function for tools
    public function saveImage() {
        if(Input::hasFile('filedata')) {
            if(Input::file('filedata')->isValid()) {
                $validator = Validator::make(Input::file(), [
                    'filedata' => 'dimensions:max_width=4000,max_height=4000|image',
                ]);
                if (!$validator->fails()) {
                    $destinationPath = public_path('files/temporary/' . Session::getId(). '/');
                    $this->makeDirectory($destinationPath);
                    $fileName = uniqid('feadz' , true) . '.png';
                    Input::file('filedata')->move($destinationPath, $fileName);
                    return Response::json(['success' => true, 'file' => Session::getId() . '/' . $fileName]);
                }
                return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);
            }
            return Response::json(['success' => false, 'errorText' => ['The file failed validation'] ]);
        }
        return Response::json(['success' => false, 'errorText' => ['It must be a file!'] ]);
    }

    public function getInfoYoutube() {
        $validator = Validator::make(Input::get(), [
            'video_url' => 'required|url'
        ]);
        if (!$validator->fails()) {
            $array_information = @file_get_contents('https://www.youtube.com/oembed?url='.Input::get('video_url').'&format=json');
            $array_information = json_decode($array_information, true);
            if(is_array($array_information))
                return Response::json(['success' => true, 'thumbnail_url' => $array_information['thumbnail_url'], 'html' => $array_information['html'] ]);
            else
                return Response::json(['success' => false, 'errorText' => ['Youtube video on this link was not found!'] ]);
        }
        return Response::json(['success' => false, 'errorText' => $validator->errors()->all()]);
    }

    public function editPage($url) {
        $post = Post::where(['author_name' => Auth::user()->name, 'url' => $url])->first();
        if(!empty($post)) {
            return view('tools.editing.'.$post->type, ['body_class' => 'create '. $post->type, 'post' => $post]);
        }
        return redirect('/');
    }

    public function deletePost(Request $request) {
        $post = Post::where(['id' => $request->post_id, 'user_id' => Auth::user()->id])->first();
        if(!empty($post)) {
            $post->deleteAllRelations();
            return Response::json(['success' => true]);
        }
        return Response::json(['success' => false]);
    }

    public function successPage($author, $url) {
        $data = ['author' => $author, 'url' => $url];
        $validator = Validator::make($data, [
            'author' => 'required|min:3|max:255',
            'url'    => 'required|min:3|max:200',
        ]);
        if (!$validator->fails()) {
            $post = Post::where(['user_id' => Auth::user()->id, 'author_name' => $author, 'url' => $url])->first();
            if($post) {
                return view('success', ['url' => url($author . '/' . $url), 'body_class' => 'success', 'post' => $post]);
            }
        }
        return redirect('/home');
    }

    protected function makeDirectory($path) {
        if(!File::exists($path)) 
           return File::makeDirectory($path);
    }

    public static function get_title_site($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // some websites like Facebook need a user agent to be set.
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.94 Safari/537.36');
        $html = curl_exec($ch);
        curl_close($ch);
        $dom  = new DOMDocument;
        @$dom->loadHTML($html);
        $title = $dom->getElementsByTagName('title')->item('0')->nodeValue;
        return $title;
    }

    public static function translit($string) {
        $string = (string) $string;
        $string = strip_tags($string);
        $string = str_replace(array("\n", "\r"), " ", $string);
        $string = trim($string);
        $string = function_exists('mb_strtolower') ? mb_strtolower($string) : strtolower($string);
        $string = strtr($string, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $string = preg_replace("/[^0-9a-z-_ ]/i", "", $string);
        $string = preg_replace("/\s+/", ' ', $string);
        $string = str_replace(" ", "-", $string);
        return $string;
    }
}
