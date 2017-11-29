@extends('tools.creating.common')
@section('title')Creating Rankedlist @endsection
@section('tool_create')

		<div class="tool-title">Create your Feadz Ranked list</div>
		<div class="rankedlist-create">
		<form action="/create/rankedlist/send" id="create-rankedlist" method="POST">
		<input name="_token" value="{{ csrf_token() }}" style="display: none;">
			<div class="header-block" data-type="photo">
			<div class="header-photo holder">
				<button type="button" class="add-file" data-type="main-photo"></button>
				<div class="add-photo">
					<div class="add-text">
						<div class="add-photo-big">DRAG FILE TO UPLOAD</div>
						<div class="add-photo-sm">Please use <br> hight resolution photo</div>
					</div>
				</div>
				<button class="edit-photo" style="display: none;"></button>
			</div>
			<div class="text_info">
   				<div class="container-tool">
       				<input type="text" class="tool-variable tool-input-title" name="rankedlist[data][rankedlist_title]" placeholder="Title..." autocomplete="off">
       				<div class="error-text" style="display: none;">The Ranked list title field is required.</div>
   				</div>
   				<div class="container-tool">
   					<textarea class="tool-variable tool-description" name="rankedlist[data][rankedlist_description]" placeholder="Description..." autocomplete="off" maxlength="2000"></textarea>
   					<div class="textarea-icon"></div>
       				<div class="error-text" style="display: none;">The Ranked list description field is required.</div>
   				</div>
			</div>
			</div>
			<div class="added-tags-form" style="display: none;">
				
			</div>
			<input name="rankedlist[category]" type="hidden" id="category" value="">
			<div class="editor">
				<div class="card" data-card="1">
<!--    					<div class="choose-buttons">
   						<button type="button" class="active">Ranked list card</button>
   					</div> -->
   					<div class="container-tool">
   						<input class="item-title-input" type="text" placeholder="Enter item title (45 symbols max)" maxlength="45" autocomplete="off" name="rankedlist[cards][1][item_title]">
   						<input name="rankedlist[cards][1][type_card]" type="hidden" value="image" class="type_card" autocomplete="off">
   						<div class="error-text" style="display: none;">The Ranked list item title field is required.</div>
   					</div>
					<div class="create-rankedlist" data-type="card">
		   				<div class="upload-file holder">
		   					<div class="upload-text">
								<div class="upload-text-big">DRAG FILE TO UPLOAD</div>
								<div class="upload-text-sm">Please use hight resolution photo</div>
		   					</div>
		   					<button type="button" class="add-file" data-type="card"></button>
		   				</div>
		   				<div class="or">OR</div>
		   				<div class="send-card-video">
		   					<input class="input-card-video" placeholder="http://youtube.com/video" type="text" name="" >
		   					<button type="button"></button>
		   				</div>
					</div>
					<div class="container-tool">
						<input class="caption-input" type="text" placeholder="Add Caption" maxlength="45" autocomplete="off" name="rankedlist[cards][1][caption_card]">
						<div class="error-text" style="display: none;">The Ranked list caption field is required.</div>
					</div>
				</div>
				<button type="button" class="add-card">ADD CARD</button>
			</div>
			<div class="hidden-inputs" style="display: none;">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input name="state" type="hidden" value="publish" class="state" autocomplete="off">
				<input name="rankedlist[data][postID]" type="hidden" value="" class="postID" autocomplete="off">
				<input name="rankedlist[data][photo_main]" type="hidden" value="" class="photo" autocomplete="off">
			</div>
			</form>
			<div class="hidden-select-file" style="display: none;">
				<input type="file" name="filedata">
			</div>
			<div class="hidden-content-add-card" style="display: none;">
				<div class="card">
   					<div class="container-tool">
   						<input class="item-title-input" type="text" placeholder="Enter item title (45 symbols max)" maxlength="45" autocomplete="off" name="flipcards[cards][1][card_item_title]">
   						<input name="rankedlist[cards][1][type_card]" type="hidden" value="image" class="type_card" autocomplete="off">
   						<div class="error-text" style="display: none;">The Ranked list item title field is required.</div>
   					</div>
					<div class="create-rankedlist" data-type="card">
		   				<div class="upload-file holder">
		   					<div class="upload-text">
								<div class="upload-text-big">DRAG FILE TO UPLOAD</div>
								<div class="upload-text-sm">Please use hight resolution photo</div>
		   					</div>
		   					<button type="button" class="add-file" data-type="card"></button>
		   				</div>
		   				<div class="or">OR</div>
		   				<div class="send-card-video">
		   					<input class="input-card-video" placeholder="http://youtube.com/video" type="text" name="">
		   					<button type="button"></button>
		   				</div>
					</div>
					<div class="container-tool">
						<input class="caption-input" type="text" placeholder="Add Caption" maxlength="45" name="rankedlist[cards][1][caption_card]" autocomplete="off">
						<div class="error-text" style="display: none;">The Ranked list caption field is required.</div>
					</div>
				</div>
			</div>
			<div class="hidden-card-preview" style="display: none;">
				<div class="card">
					<div class="info-card">
						<div class="vote">
							<div class="vote-button" data-pid="502" data-id="1" data-elemid="1"></div>
							<b data-id="1">+0</b>
						</div>
						<div class="item-title-card"></div>
					</div>
					<div class="rankedlist-card">
						<div class="card-img"><img src="/source/img/monro.png"></div>
						<div class="card-text">gchgfhghgh</div>
					</div>
					<div class="rankedlist-footer"></div>
				</div>
			</div>
			<div class="hidden-content-photo" style="display: none;">
				<div class="rankedlist-photo-type">
					<div class="delete-rankedlist-img"></div>
					<img class="rankedlist-img" src="">
				</div>
			</div>
			<div class="hidden-create" style="display: none;">
				<div class="create-rankedlist" data-type="card">
	   				<div class="upload-file holder">
	   					<div class="upload-text">
							<div class="upload-text-big">DRAG FILE TO UPLOAD</div>
							<div class="upload-text-sm">Please use hight resolution photo</div>
	   					</div>
	   					<button type="button" class="add-file" data-type="card"></button>
	   				</div>
	   				<div class="or">OR</div>
	   				<div class="send-card-video">
	   					<input class="input-card-video" placeholder="http://youtube.com/video" type="text" name="" >
	   					<button type="button"></button>
	   				</div>
				</div>
			</div>
			<div class="down_butts">
				<button type="button" id="preview" class="btn-preview">PREVIEW</button>
				<button type="button" id="save_draft" class="btn-save" style="display: none;">SAVE</button>
				<button type="button" id="publish" class="btn-publish">PUBLISH</button>
			</div>
		</div>
@endsection
@section('tool_preview')
		<div class="post-content rankedlist">
		    <div class="description">The photos were taken in 1950 with a still unknown Marilyn, by a Life 
			Magazine photographer Ed Clark, at the suggestion of a friend of 20th Century Fox telling of the new hiring of the studios.

			“I sent several rolls to LIFE in New York, but they wired back, ‘Who the hell is Marilyn Monroe?” – Ed Clark.

			No one at that time could have even imagined how iconic Marilyn will</div>
			<div class="card">
				<div class="info-card">
					<div class="vote">
						<div class="vote-button" data-pid="502" data-id="1" data-elemid="1"></div>
						<b data-id="1">+0</b>
					</div>
					<div class="item-title-card"></div>
				</div>
				<div class="rankedlist-card">
					<div class="card-img"><img src="/source/img/monro.png"></div>
					<div class="card-text">gchgfhghgh</div>
				</div>
				<div class="rankedlist-footer"></div>
			</div>
		</div>
@endsection
@section('script')
<script type="text/javascript" src="/source/js/tools/rankedlist.js"></script>
@endsection