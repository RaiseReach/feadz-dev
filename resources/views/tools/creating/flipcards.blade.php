@extends('tools.creating.common')
@section('title')Creating Flip Cards @endsection
@section('tool_create')
		<div class="tool-title">Create your Feadz Flipcards</div>
		<div class="flipcards-create">
			<form action="/create/flipcards/send" method="post" id="create-flipcards">
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
	       				<input type="text" class="tool-variable tool-input-title" name="flipcards[data][flipcards_title]" placeholder="Title..." autocomplete="off">
	       				<div class="error-text" style="display: none;">The flipcards title field is required.</div>
	   				</div>
	   				<div class="container-tool">
	   					<textarea class="tool-variable tool-description" name="flipcards[data][flipcards_description]" placeholder="Description..." autocomplete="off" maxlength="2000"></textarea>
	   					<div class="textarea-icon"></div>
	       				<div class="error-text" style="display: none;">The flipcards description field is required.</div>
	   				</div>
				</div>
			</div>
			<div class="added-tags-form" style="display: none;">
			</div>
			<input name="_token" value="{{ csrf_token() }}" style="display: none;">
			<input name="flipcards[category]" type="hidden" id="category" value="">
			<div class="editor">
				<div class="card">
   					<div class="choose-buttons">
   						<button type="button" class="frontcard active" data-card="1" data-side="1">Front card</button>
   						<button type="button" class="backcard" data-card="1" data-side="2">Back card</button>
   					</div>
   					<div class="container-tool">
   						<input class="item-title-input" type="text" placeholder="Enter item title (45 symbols max)" maxlength="45" autocomplete="off" name="flipcards[cards][1][card_item_title]">
   						<div class="error-text" style="display: none;">The flipcards item title field is required.</div>
   					</div>
   					<div class="sides" data-card="1">
   						<div class="front-card" data-side="1">
		   					<div class="create-flipcard" data-type="card">
			   					<div class="upload-file holder">
									<button type="button" class="add-file" data-type="card"></button>
			   						<div class="upload-text">
										<div class="upload-text-big">DRAG FILE TO UPLOAD</div>
										<div class="upload-text-sm">Please use hight resolution photo</div>
			   						</div>
			   					</div>
			   					<div class="or">OR</div>
			   					<div class="send-card-text">
			   						<input class="input-card-text" placeholder="Write a text" type="text" name="" >
			   						<button type="button"></button>
			   					</div>
			   				</div>
   						</div>
   						<div class="back-card" data-side="2">
		   					<div class="create-flipcard" data-type="card">
			   					<div class="upload-file holder">
									<button type="button" class="add-file" data-type="card"></button>
			   						<div class="upload-text">
										<div class="upload-text-big">DRAG FILE TO UPLOAD</div>
										<div class="upload-text-sm">Please use hight resolution photo</div>
			   						</div>
			   					</div>
			   					<div class="or">OR</div>
			   					<div class="send-card-text">
			   						<input class="input-card-text" placeholder="Write a text" type="text" name="" >
			   						<button type="button"></button>
			   					</div>
			   				</div>
   						</div>
   					</div>
				</div>
				<button type="button" class="add-card">ADD CARD</button>
				<div class="hidden-inputs" style="display: none;">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input name="state" type="hidden" value="publish" class="state" autocomplete="off">
					<input name="flipcards[data][postID]" type="hidden" value="" class="postID" autocomplete="off">
					<input name="flipcards[data][photo_main]" type="hidden" value="" class="photo" autocomplete="off">
				</div>
				</form>
			</div>

			<div class="hidden-select-file" style="display: none;">
				<input type="file" name="filedata">
			</div>
			<div class="hidden-create" style="display: none;">
				<div class="create-flipcard" data-type="card">
   				<div class="upload-file holder">
					<button type="button" class="add-file" data-type="card"></button>
   					<div class="upload-text">
						<div class="upload-text-big">DRAG FILE TO UPLOAD</div>
						<div class="upload-text-sm">Please use hight resolution photo</div>
   					</div>
   				</div>
   				<div class="or">OR</div>
   				<div class="send-card-text">
   					<input class="input-card-text" placeholder="Write a text" type="text" name="" >
   					<button type="button" class="button-input-card-text"></button>
   				</div>
				</div>
			</div>
			<div class="hidden-content-photo" style="display: none;">
				<div class="flipcard-photo-type">
					<div class="delete-flipcard-img"></div>
					<img class="flipcard-img" src="">
					<div style="display: none;">
						<input name="" class='type' value="image">
						<input name="" class='card-image' value="">
					</div>
				</div>
			</div>
			<div class="hidden-content-text" style="display: none;">
				<div class="flipcard-text-type">
					<div class="delete-flipcard-text"></div>
					<textarea maxlength="80" autocomplete="off" class="textarea-add-text" placeholder="Write something awesome"></textarea>
					<div class="set-background-buttons">
					<button type="button" class="background-button"></button>
						<div class="choose-background-buttons">
							<button type="button" class="background-button red-orange" data-theme_bg="#ff5504"></button>
							<button type="button" class="background-button brown" data-theme_bg="#795446"></button>
							<button type="button" class="background-button grey" data-theme_bg="#9d9d9d"></button>
							<button type="button" class="background-button blue" data-theme_bg="#3d4db6"></button>
							<button type="button" class="background-button black" data-theme_bg="#000000"></button>
							<button type="button" class="background-button limegreen" data-theme_bg="#ccdd1e"></button>
							<button type="button" class="background-button yellow" data-theme_bg="#ffec16"></button>
							<button type="button" class="background-button orange" data-theme_bg="#ff9700"></button>
							<button type="button" class="background-button burgundy" data-theme_bg="#9c1ab1"></button>
							<button type="button" class="background-button purple" data-theme_bg="#6733b8"></button>
							<button type="button" class="background-button cyan" data-theme_bg="#00a6f6"></button>
							<button type="button" class="background-button aqua" data-theme_bg="#009587"></button>
							<button type="button" class="background-button white" data-theme_bg="#ffffff"></button>
							<button type="button" class="background-button green" data-theme_bg="#47af4a"></button>
							<button type="button" class="background-button pink" data-theme_bg="#eb1460"></button>
							<button type="button" class="background-button darkgrey" data-theme_bg="#363f46"></button>
						</div>
					</div>
					<div style="display: none;">
					<input name="" class="type" value="text">
					<input name="" class="color" value="#47af4a">
					</div>
				</div>
			</div>
		<div class="hidden-content-add-card" style="display: none;">
			<div class="card">
   				<div class="choose-buttons">
   					<button type="button" class="frontcard active" data-card="2" data-side="1">Front card</button>
   					<button type="button" class="backcard" data-card="2" data-side="2">Back card</button>
   				</div>
   				<div class="container-tool">
   					<input class="item-title-input" type="text" placeholder="Enter item title (45 symbols max)" maxlength="45" autocomplete="off">
   					<div class="error-text" style="display: none;">The flipcards item title field is required.</div>
   				</div>
   				<div class="sides">
   					<div class="front-card" data-side="1">
		   				<div class="create-flipcard" data-type="card">
			   				<div class="upload-file holder">
								<button type="button" class="add-file" data-type="card"></button>
			   					<div class="upload-text">
									<div class="upload-text-big">DRAG FILE TO UPLOAD</div>
									<div class="upload-text-sm">Please use hight resolution photo</div>
			   					</div>
			   				</div>
			   				<div class="or">OR</div>
			   				<div class="send-card-text">
			   					<input class="input-card-text" placeholder="Write a text" type="text" name="" >
			   					<button type="button"></button>
			   				</div>
			   			</div>
   					</div>
   					<div class="back-card" data-side="2">
		   				<div class="create-flipcard" data-type="card">
			   				<div class="upload-file holder">
								<button type="button" class="add-file" data-type="card"></button>
			   					<div class="upload-text">
									<div class="upload-text-big">DRAG FILE TO UPLOAD</div>
									<div class="upload-text-sm">Please use hight resolution photo</div>
			   					</div>
			   				</div>
			   				<div class="or">OR</div>
			   				<div class="send-card-text">
			   					<input class="input-card-text" placeholder="Write a text" type="text" name="" >
			   					<button type="button" class="button-input-card-text"></button>
			   				</div>
		   				</div>
   					</div>
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
		<div class="post-content flipcards">
		    <div class="description">The photos were taken in 1950 with a still unknown Marilyn, by a Life 
			Magazine photographer Ed Clark, at the suggestion of a friend of 20th Century Fox telling of the new hiring of the studios.

			“I sent several rolls to LIFE in New York, but they wired back, ‘Who the hell is Marilyn Monroe?” – Ed Clark.

			No one at that time could have even imagined how iconic Marilyn will</div>
			<div class="item-title-card">Lsidfjgls d flkjs kjdldfhjshgdf</div>
			<div class="flipcard">
				<div class="sides" data-id="1" ">
					<div class="front"><img src="/source/img/monro.png"><div class="flip-icon"></div></div>
					<div class="back"><img src="/source/img/monro.png"><div class="flip-icon"></div></div>
				</div>
			</div>
			<div class="flipcard-footer"></div>
		</div>
@endsection
@section('script')
<script type="text/javascript" src="/source/js/tools/flipcards.js"></script>
@endsection
