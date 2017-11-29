<?php namespace App\Http\Controllers;

use File;
use App\Post;

class CronController extends Controller {

    /*
    *--------------------------------------------------------------------------
    * Cron Controller
    *--------------------------------------------------------------------------
    *
    * This controller is designed for cron tasks
    *
    */

    /**
    * Updating tags in the site header
    * @return void 
    */
    public function tags() {
        $tags = [];
        $popular_posts = Post::withCount('views')->orderBy('views_count', 'desc')->take(100)->get();
        foreach ($popular_posts as $post) {
            $post_tags = unserialize($post->tags);
            if(empty($post_tags)) continue;
            foreach ($post_tags as $tag) {
                $tags[] = $tag;
            }
        }
        $html = '';
        foreach ($tags as $tag) {
            $html .= "<a href='" . url('/tag', $tag) ."'>". $tag ."</a>";
        }
        File::put(resource_path('views/tags.blade.php'), $html);
    }
}
