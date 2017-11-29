@extends('tools.editing.common')
@section('title')Editing Meme @endsection
@section('tool_create')

		<div class="tool-title">Create Meme</div>
			<div class="meme-create">
				<form action="/create/meme/send" method="post" id="create-meme">
   				<div class="header-block">
					<div class="text_info">
	       				<div class="container-tool">
		       				<input type="text" class="tool-variable tool-input-title" name="meme[meme_title]" placeholder="Title..." autocomplete="off" value="{{ $post->description_title }}">
	       				</div>
	       				<div class="container-tool">
	       					<textarea class="tool-variable tool-description" name="meme[meme_description]" placeholder="Description..." autocomplete="off" maxlength="2000">{{ $post->description_text }}</textarea>
	       					<div class="textarea-icon"></div>
	       				</div>
					</div>
       			</div>
				<div class="added-tags-form" style="display: none;">
					@foreach(unserialize($post->tags) as $tag)
					<input name="tags[]" value="{{ $tag }}">
					@endforeach
				</div>
			<div class="editor">
	   			<div class="upload-file holder" style="display: none;">
	   				<div class="upload-text">
						<div class="upload-text-big">UPLOAD YOUR OWN IMAGE</div>
						<div class="upload-text-sm">Please use hight resolution photo</div>
	   				</div>
					<button type="button" class="add-file" data-type="card"></button>
	   			</div>
				<div class="main-meme">
					<img src="{{ url('/files/uploads/' . $post->description_image )}}">
					<div class="top-text"></div>
					<div class="bottom-text"></div>
				</div>
   			</div>
			<div class="hidden-inputs" style="display: none;">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input name="state" type="hidden" value="publish" class="state" autocomplete="off">
				<input name="meme[data][postID]" type="hidden" class="postID" autocomplete="off" value="{{ $post->id}}">
				<input name="meme[main_meme]" type="hidden" class="main-meme" autocomplete="off" value="{{ $post->description_image }}">
				<input name="meme[type]" type="hidden" value="new" class="type" autocomplete="off">
				<input name="meme[color_top]" type="hidden" value="#000000" class="color-top" autocomplete="off">
				<input name="meme[color_bottom]" type="hidden" value="#000000" class="color-bottom" autocomplete="off">
				<input name="meme[size_top]" type="hidden" value="2" class="size-top" autocomplete="off">
				<input name="meme[size_bottom]" type="hidden" value="2" class="size-bottom" autocomplete="off">
				<input name="meme[category]" type="hidden" id="category" value="{{ $post->category }}">
				<input name="meme[top_text]" type="hidden" value="">
				<input name="meme[bottom_text]" type="hidden" value="">
			</div>
   			</form>

			<div class="hidden-select-file" style="display: none;">
				<input type="file" name="filedata">
			</div>

			<div class="down_butts">
				<button type="button" id="reset" class="btn-preview">RESET</button>
				<button type="button" id="save_draft" class="btn-save" style="display: none;">SAVE</button>
				<button type="button" id="publish" class="btn-publish">SAVE CHANGES</button>
			</div>
			</div>
@endsection

@section('script')
<script type="text/javascript" src="/source/js/tools/meme.js"></script>
@endsection