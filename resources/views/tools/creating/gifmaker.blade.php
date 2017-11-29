@extends('tools.creating.common')
@section('title')Creating GIF @endsection
@section('tool_create')
   			<div class="tool-title">Create your Feadz GIF</div>
   			<form action="/create/gifmaker/send" method="post" id="create-gifmaker">
   			<div class="gifmaker-create">
   				<div class="header-block">
					<div class="text_info">
	       				<div class="container-tool">
		       				<input type="text" class="tool-variable tool-input-title" name="gifmaker[data][gifmaker_title]" placeholder="Title..." autocomplete="off">
		       				<div class="error-text" style="display: none;">The GIF title field is required.</div>
	       				</div>
	       				<div class="container-tool">
	       					<textarea class="tool-variable tool-description" name="gifmaker[data][gifmaker_description]" placeholder="Description..." autocomplete="off" maxlength="2000"></textarea>
	       					<div class="textarea-icon"></div>
		       				<div class="error-text" style="display: none;">The GIF description field is required.</div>
	       				</div>
					</div>
       			</div>
				<div class="added-tags-form" style="display: none;">
					
				</div>

       			<div class="hidden-inputs" style="display: none;">
       				<input name="_token" type="hidden" value="{{ csrf_token() }}">
					<input name="state" type="hidden" value="publish" class="state" autocomplete="off">
					<input name="gifmaker[data][postID]" type="hidden" value="" class="postID" autocomplete="off">
					<input name="gifmaker[data][photo_main]" type="hidden" value="" class="input-form-photo" autocomplete="off">
					<input name="gifmaker[gif]" type="hidden" value="" class="gif-input" autocomplete="off">
					<input name="gifmaker[category]" type="hidden" id="category" value="gif">
       			</div>
       			</form>
   				<div class="choose-buttons">
   					<button type="button" class="frontcard active" data-card="url_video">Add URL Video</button>
   					<button type="button" class="backcard choose-video" data-card="video_file">Add Video File</button>
   				</div>
				<form id="create-gif-from-yb" action="/create/gifmaker/create" method="POST">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="gifmaker[create][video_youtube]" value="" class="un_video_url">
					<input type="hidden" name="gifmaker[create][start_time]" class="un_start_time" value="0">
					<input type="hidden" name="gifmaker[create][end_time]" class="un_end_time" value="1">
					<input type="hidden" name="gifmaker[create][color]" class="un_color" value="0">
					<input type="hidden" name="gifmaker[create][font_family]" class="un_style" value="0">
					<input type="hidden" name="gifmaker[create][font_size]" class="un_size" value="0">
					<input type="hidden" name="gifmaker[create][caption]" class="un_caption" value="">
					<input type="hidden" name="gifmaker[create][variant]" class="un_variant" value="1">
					<input type="hidden" name="gifmaker[create][filename_blob]" class="un_filename" value="">
				</form>

   				<div class="editor">
	   				<div class="create-gif">
			   			<div class="upload-file holder" style="display: none;">
			   				<div class="upload-text">
								<div class="upload-text-big">UPLOAD YOUR OWN VIDEO</div>
								<div class="upload-text-sm">Maximum file size: 15MB</div>
			   				</div>
							<button type="button" class="add-file" data-type="card"></button>
			   			</div>
		   				<div class="yb-clip-upl">
		   					<input class="input-yb-clip" placeholder="Enter YouTube clip URL" type="text" name="" >
		   					<button type="button" class="button-input-yb-clip"></button>
		   				</div>
		   				<div class="container-tool">
		   					<input class="caption-input" type="text" placeholder="Add Caption" maxlength="12" autocomplete="off">
		   					<div class="error-text" style="display: none;">The Ranked list caption field is required.</div>
		   				</div>
		   				<div class="block-video-duration">
							<div class="subtitle">Time duration</div>
							<div class="iframe-youtube"><div id="player"></div> <div class="txt-caption"> </div></div>
							<div class="choose-time">Start time</div>
							<div class="slider-block">
								<div class="nstSlider" data-id="1" data-range_min="0" data-range_max="3600" data-cur_min="0" data-cur_max="3600">
								    <div class="bar" style="left: 0px; width: 452px;"></div>
								    <div class="leftGrip" tabindex="0" style="left: 0px;"></div>
								</div>
								<input class="start-time">
							</div>
							<div class="choose-time">Duration</div>
							<div class="slider-block">
								<div class="nstSlider" data-id="2" data-range_min="1" data-range_max="5" data-cur_min="1" data-cur_max="0">
								    <div class="bar" style="left: 10px; width: 10px;"></div>
								    <div class="leftGrip" tabindex="0" style="left: 0px;"></div>
								</div>
								<input class="duration-time">
							</div>
							<div class="btn-create-gif" style="display: none;">
								<button type="button" style="display: block;">CREATE GIF</button>
							</div>
							<div class="progressbar" style="display: none;">
								<div class="txt-gif-creating"> GIF IS CREATING </div>
								<div id="myProgress">
								  <div id="myBar"></div>
								</div>
								<div class="percent-bar">1%</div>
							</div>
							<div class="successfully-create">GIF WAS CREATED!</div>
						</div>
	   				</div>
	   			</div>
	   			<input type="file" name="video" id="input-video" accept="video/mp4" style="display: none;" />
				<div class="down_butts" style="display: none;">
					<button type="button" id="preview" class="btn-preview">PREVIEW</button>
					<button type="button" id="save_draft" class="btn-save" style="display: none;">SAVE</button>
					<button type="button" id="publish" class="btn-publish">PUBLISH</button>
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
@endsection