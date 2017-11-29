@extends('tools.viewing.view')
@section('tool_content')
		<div class="post-content gif">
		    <div class="description">{{ $post->description_text }}</div>
			<div class="gif-img"><img src="/files/uploads/{{ $content[0]['gif'] }}"></div>
			<div class="gif-footer"></div>
		</div>
@endsection