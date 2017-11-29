@extends('page')
@section('title')
Successfully 
@endsection
@section('content')
		<img src="/source/img/succ.png">
		<div class="row"><span>Well done!</span></br> You've successfully posted to Feadz!</div>
		<div class="row2">Now <span>Share It</span> With The World & <span>Profit</span>!</div>
		<div class="share-butts feadz-social-net">
            <button data-title="{{ $post->description_title }}" data-url="{{ url($post->author_name . '/' . $post->url) }}" data-type="fb" class="fb-social-icon" ></button>
            <button data-title="{{ $post->description_title }}" data-url="{{ url($post->author_name . '/' . $post->url) }}" data-type="pt" class="p-social-icon"></button>
            <button data-title="{{ $post->description_title }}" data-url="{{ url($post->author_name . '/' . $post->url) }}" data-type="tw" class="tw-social-icon"></button>
            <button data-title="{{ $post->description_title }}" data-url="{{ url($post->author_name . '/' . $post->url) }}" data-type="li" class="in-social-icon"></button>
		</div>
		<input type="text" value="{{ url($url) }}" />
		<a href="{{ url($url) }}" class="view-item" >VIEW ITEM</a>
@endsection
