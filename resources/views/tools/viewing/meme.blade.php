@extends('tools.viewing.view')
@section('tool_content')
		<div class="post-content meme">
		    <div class="description">{{ $post->description_text }}</div>
			<div class="meme-img"><img src="{{ url('/files/uploads/' . $content)}}"></div>
			<div class="meme-footer"></div>
		</div>
@endsection