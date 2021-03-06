@extends('page')
@section('content')
   		<div class="left-create">
			@yield('tool_create')
   		</div>
   		<div class="right-create">
			<div class="tool-title">Additional Information</div>
			<div class="choose-category">
				<li class="dropdown">
			        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
			        @if($post->category != NULL)
			        	{{ $post->category }}
			        @else
			        	Choose a category
			        @endif
			        <span class="caret-new"></span></a>
			        <ul class="dropdown-menu">
			          <li><a data-category="Video">Video</a></li>
			          <li><a data-category="GIF">GIF</a></li>
			          <li><a data-category="Funny">Funny</a></li>
			          <li><a data-category="Gamers">Gamers</a></li>
			          <li><a data-category="Sport">Sport</a></li>
			          <li><a data-category="Animals">Animals</a></li>
			          <li><a data-category="Savage">Savage</a></li>
			          <li><a data-category="NSFW">NSFW</a></li>
			          <li><a data-category="WTF">WTF</a></li>
			          <li><a data-category="LOL">LOL</a></li>
			        </ul>
			    </li>
      		</div>
			<div class="add-tags">
				<input type="text" class="add-tags-inp" name="" placeholder="Add tags" autocomplete="off" maxlength="24">
				<button type="button" class=""></button>
			</div>
			<div class="added-tags">
				@foreach(unserialize($post->tags) as $tag)
				<div class="tag" style="">{{ $tag }}<button type="button" class="del-tag"></button></div>
				@endforeach
			</div>
			<div class="add-link">
				<input type="text" class="add-link-inp" name="" placeholder="Add link.." autocomplete="off">
			</div>
			<div class="choose-permission">
				<li class="dropdown">
			        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
			        {{ $post->permission }}
			        <span class="caret-new"></span></a>
			        <ul class="dropdown-menu">
			          <li><a data-permission="Public">Public</a></li>
			          <li><a data-permission="Private">Private</a></li>
			        </ul>
			    </li>
      		</div>
			<div class="down_butts_mobile" style="display: none;">
				<button type="button" id="preview" class="btn-reset" style="display: none;">RESET</button>
				<button type="button" id="preview" class="btn-preview" >PREVIEW</button>
				<button type="button" id="save_draft" class="btn-save" style="display: none;">SAVE</button>
				<button type="button" id="publish" class="btn-publish">PUBLISH</button>
			</div>
   		</div>
		<div class="crop-photo" style="display: none;">
			<div class="text-photo">Add photo</div>
			<div class="modal-upload-column-img">
				<div class="popup__body"><div class="js-img"></div></div>
			</div>
			<input type="text" class="photo-input" placeholder="Title.." autocomplete="off">
			<div class="img-credentials">
				<div class="js-upload btn btn_browse btn_browse_small">DONE</div>
			</div>
			<button type="button" class="close fileapi-modal"></button>
		</div>

		<div class="main-preview" style="display: none;">
			<div class="info">Feadz Story Preview</div>
			<div class="title">What is Lorem Ipsum</div>
			<div class="tags">
				<div class="tag">Marliyn Monroe</div>
			</div>
			@yield('tool_preview')
			<button type="button" id="publish" class="btn-publish">PUBLISH</button>
		</div>

		<div id="modal-alert" class="modal-alert" style="display: none;">
			<button type="button" class="btn-close close fileapi-modal" data-dismiss="modal"> </button>
			<div class="title">Something went wrong</div>
			 <ul>
				 <li>The photo main field is required.</li>
				 <li>The photo facebook field is required.</li>
				 <li>The rankedlist title field is required.</li>
				 <li>The rankedlist footer field is required.</li>
				 <li>The rankedlist description field is required.</li>
			 </ul>
		</div>
@endsection