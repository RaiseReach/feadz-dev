@extends('tools.editing.common')
@section('title') Editing Story @endsection
@section('tool_create')

		<div class="tool-title">Create your Feadz Story</div>
			<div class="story-create">
				<form action="/create/story/send" method="post" id="create-story">
				<div class="header-block">
				<div class="header-photo" data-type='photo'>
					<button type="button" class="add-file" data-type="main-photo"></button>
					<div class="add-photo holder" style="display: none;">
						<div class="add-text">
							<div class="add-photo-big">DRAG FILE TO UPLOAD</div>
							<div class="add-photo-sm">Please use <br> hight resolution photo</div>
						</div>
					</div>
					<button class="edit-photo" style="display: none;"></button>
					<img class="main-photo" src="{{ url('files/uploads/' .$post->description_image) }}" alt="main photo" style="">
				</div>
				<div class="text_info">
       				<div class="container-tool">
	       				<input type="text" class="tool-variable tool-input-title" name="story[data][story_title]" placeholder="Title..." value="{{ $post->description_title }}" autocomplete="off">
	       				<div class="error-text" style="display: none;">The story title field is required.</div>
       				</div>
       				<div class="container-tool">
       					<textarea class="tool-variable tool-description" name="story[data][story_description]" placeholder="Description..."  autocomplete="off" maxlength="2000">{{ $post->description_text }}</textarea>
       					<div class="textarea-icon"></div>
	       				<div class="error-text" style="display: none;">The story description field is required.</div>
       				</div>
				</div>
   			</div>
			<div class="choose-buttons">
				<button type="button" class="photo active" data-card="1">Photo</button>
			</div>
			<div class="added-tags-form" style="display: none;">
				@foreach(unserialize($post->tags) as $tag)
				<input name="tags[]" value="{{ $tag }}">
				@endforeach
			</div>
			<div class="editor" data-type="card">
				@foreach(unserialize($post->content) as $card)
				<div class="story-photo" style="">
					<div class="delete-story-img"></div>
					<img class="story-img" src="{{ url('/files/uploads/' . $card) }}">
					<input name="story[content][]" value="{{ $card }}" style="display: none;">
				</div>
				@endforeach
	   			<div class="upload-file holder">
	   				<div class="upload-text">
						<div class="upload-text-big">DRAG FILE TO UPLOAD</div>
						<div class="upload-text-sm">Please use hight resolution photo</div>
	   				</div>
					<button type="button" class="add-file" data-type="card"></button>
	   			</div>
   			</div>
			<div class="hidden-inputs" style="display: none;">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input name="state" type="hidden" value="publish" class="state" autocomplete="off">
				<input name="story[data][postID]" type="hidden" value="{{ $post->id}}" class="postID" autocomplete="off">
				<input name="story[data][photo_main]" type="hidden" value="{{ $post->description_image }}" class="photo" autocomplete="off">
				<input name="story[category]" type="hidden" id="category" value="{{ $post->category }}">
			</div>
   			</form>

			<div class="hidden-select-file" style="display: none;">
				<input type="file" name="filedata">
			</div>
			<div class="hidden-create" style="display: none;">
				<div class="story-photo">
					<div class="delete-story-img"></div>
					<img class="story-img" src="">
					<input name="story[content][]" value="" style="display: none;">
				</div>
			</div>

			<div class="hidden-carousel" style="display: none;">
				<div id="myCarousel" class="carousel slide carousel-story">

				  <!-- Wrapper for slides -->
					<div class="carousel-inner">
						<div class="item active">
						  <img src="/source/img/monro.png">
						  <div class="story-footer"></div>
						</div>

						<div class="item">
						  <img src="/source/img/monro.png">
						  <div class="story-footer"></div>
						</div>

						<div class="item">
						  <img src="/source/img/monro.png">
						  <div class="story-footer"></div>
						</div>
					</div>
				

					<div class="story-carousel-control">
						<a class="left-carousel-control" href="#myCarousel" data-slide="prev"></a>
						<a class="right-carousel-control" href="#myCarousel" data-slide="next"></a>
					</div>

					<div class="story-counter">
						<a class="current">1</a><span>/ 17</span>
					</div>

				</div>
			</div>
			<div class="down_butts">
				<button type="button" id="preview" class="btn-preview" >PREVIEW</button>
				<button type="button" id="save_draft" class="btn-save" style="display: none;">SAVE</button>
				<button type="button" id="publish" class="btn-publish">SAVE CHANGES</button>
			</div>
			</div>
@endsection

@section('script')
<script type="text/javascript" src="/source/js/tools/story.js"></script>
@endsection
@section('tool_preview')

		<div class="post-content story">
		    <div class="description">The photos were taken in 1950 with a still unknown Marilyn, by a Life 
			Magazine photographer Ed Clark, at the suggestion of a friend of 20th Century Fox telling of the new hiring of the studios.

			“I sent several rolls to LIFE in New York, but they wired back, ‘Who the hell is Marilyn Monroe?” – Ed Clark.

			No one at that time could have even imagined how iconic Marilyn will</div>
		</div>
@endsection