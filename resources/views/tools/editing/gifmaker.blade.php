@extends('tools.editing.common')
@section('title')Editing GIF @endsection
@section('tool_create')
   			<div class="tool-title">Create your Feadz GIF</div>
   			<form action="/create/gifmaker/send" method="post" id="create-gifmaker">
   			<div class="gifmaker-create">
   				<div class="header-block">
					<div class="text_info">
	       				<div class="container-tool">
		       				<input type="text" class="tool-variable tool-input-title" name="gifmaker[data][gifmaker_title]" placeholder="Title..." autocomplete="off" value="{{ $post->description_title }}">
		       				<div class="error-text" style="display: none;">The GIF title field is required.</div>
	       				</div>
	       				<div class="container-tool">
	       					<textarea class="tool-variable tool-description" name="gifmaker[data][gifmaker_description]" placeholder="Description..." autocomplete="off" maxlength="2000">{{ $post->description_text }}</textarea>
	       					<div class="textarea-icon"></div>
		       				<div class="error-text" style="display: none;">The GIF description field is required.</div>
	       				</div>
					</div>
       			</div>
				<div class="added-tags-form" style="display: none;">
					@foreach(unserialize($post->tags) as $tag)
					<input name="tags[]" value="{{ $tag }}">
					@endforeach
				</div>
				@php 
				$gif = unserialize($post->content)[0]['gif'];
				@endphp
       			<div class="hidden-inputs" style="display: none;">
       				<input name="_token" type="hidden" value="{{ csrf_token() }}">
					<input name="state" type="hidden" value="publish" class="state" autocomplete="off">
					<input name="gifmaker[data][postID]" type="hidden" value="{{ $post->id }}" class="postID" autocomplete="off">
					<input name="gifmaker[data][photo_main]" type="hidden" value="{{ $post->description_image }}" class="input-form-photo" autocomplete="off">
					<input name="gifmaker[gif]" type="hidden" value="{{ $gif }}" class="gif-input" autocomplete="off">
					<input name="gifmaker[category]" type="hidden" id="category" value="{{ $post->category }}">
       			</div>
       			</form>

   				<div class="editor">
	   				<div class="create-gif">
	   					<div class="block-video-duration">
	   						<div class="iframe-youtube">
	   							<img class="picture-gif" src="{{ url('/files/uploads/' . $gif)}}">
	   						</div>
	   					</div>
	   				</div>
	   			</div>
	   			<input type="file" name="video" id="input-video" accept="video/mp4" style="display: none;" />
				<div class="down_butts">
					<button type="button" id="preview" class="btn-preview">PREVIEW</button>
					<button type="button" id="publish" class="btn-publish">SAVE CHANGES</button>
				</div>
			</div>
@endsection
@section('tool_preview')
		<div class="post-content gif">
		    <div class="description">The photos were taken in 1950 with a still unknown Marilyn, by a Life 
			Magazine photographer Ed Clark, at the suggestion of a friend of 20th Century Fox telling of the new hiring of the studios.

			“I sent several rolls to LIFE in New York, but they wired back, ‘Who the hell is Marilyn Monroe?” – Ed Clark.

			No one at that time could have even imagined how iconic Marilyn will</div>
			<div class="gif-img"><img src="/source/img/gif.gif"></div>
			<div class="gif-footer"></div>
		</div>
@endsection
@section('script')
<script type="text/javascript" src="/source/js/tools/gifmaker.js"></script>
<script> 
$('.choose-category .dropdown-toggle').text('{{ $post->category }}');
</script>
@endsection