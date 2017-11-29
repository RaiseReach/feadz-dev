@extends('tools.creating.common')
@section('title')Creating Meme @endsection
@section('tool_create')

		<div class="tool-title">Create Meme</div>
			<div class="meme-create">
				<form action="/create/meme/send" method="post" id="create-meme">
				<input name="_token" value="{{ csrf_token() }}" style="display: none;">
   				<div class="header-block">
					<div class="text_info">
	       				<div class="container-tool">
		       				<input type="text" class="tool-variable tool-input-title" name="meme[meme_title]" placeholder="Title..." autocomplete="off">
	       				</div>
	       				<div class="container-tool">
	       					<textarea class="tool-variable tool-description" name="meme[meme_description]" placeholder="Description..." autocomplete="off" maxlength="2000"></textarea>
	       					<div class="textarea-icon"></div>
	       				</div>
					</div>
       			</div>
				<div class="choose-buttons">
   					<button type="button" class="frontcard active" data-card="popular">Popular</button>
   					<button type="button" class="backcard choose-video" data-card="new">New</button>
				</div>
				<div class="added-tags-form" style="display: none;">
					
				</div>
			<div class="editor">
				<div class="scrollbar-inner scroll-bar_center">
					<div class="scrollmenu-memes" id='scroll-center'>
						<div class="meme active"> <img src="/source/img/meme/1.jpg"> </div>
						<div class="meme"> <img src="/source/img/meme/2.jpg"> </div>
						<div class="meme"> <img src="/source/img/meme/3.jpg"> </div>
						<div class="meme"> <img src="/source/img/meme/4.jpg"> </div>
						<div class="meme"> <img src="/source/img/meme/5.jpg"> </div>
						<div class="meme"> <img src="/source/img/meme/6.jpg"> </div>
						<div class="meme"> <img src="/source/img/meme/7.jpg"> </div>
						<div class="meme"> <img src="/source/img/meme/8.jpg"> </div>
						<div class="meme"> <img src="/source/img/meme/9.jpg"> </div>
					</div>
				</div>
	   			<div class="upload-file holder" style="display: none;">
	   				<div class="upload-text">
						<div class="upload-text-big">UPLOAD YOUR OWN IMAGE</div>
						<div class="upload-text-sm">Please use hight resolution photo</div>
	   				</div>
					<button type="button" class="add-file" data-type="card"></button>
	   			</div>
				<div class="main-meme">
					<img src="/source/img/meme/1.jpg">
					<div class="top-text"></div>
					<div class="bottom-text"></div>
				</div>
				<div class="container-tool">
					<input class="top-text-input" type="text" placeholder="Top Text" maxlength="24" autocomplete="off" name="meme[top_text]">
					<div class="error-text" style="display: none;">The Meme top text field is required.</div>
					<div class="input-meme">
						<div class="set-background-buttons">
							<button type="button" class="background-button" data-side="top" style="background-color: black"></button>
							<div class="choose-background-buttons" data-side="top">
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
								<div class="white-line"></div>
							</div>
						</div>
						<div class="meme-couter">
							<div class="value">2</div>
							<button type="button" class="up" data-side="top"></button>
							<button type="button" class="lower" data-side="top"></button>
						</div>
					</div>
				</div>
				<div class="container-tool">
					<input class="bottom-text-input" type="text" placeholder="Bottom Text" maxlength="24" autocomplete="off" name="meme[bottom_text]">
					<div class="input-meme">
						<div class="set-background-buttons">
							<button type="button" class="background-button" data-side="bottom" style="background-color: black"></button>
							<div class="choose-background-buttons" data-side="bottom">
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
								<div class="white-line"></div>
							</div>
						</div>
						<div class="meme-couter">
							<div class="value">2</div>
							<button type="button" class="up" data-side="bottom"></button>
							<button type="button" class="lower" data-side="bottom"></button>
						</div>
					</div>
				</div>
   			</div>
			<div class="hidden-inputs" style="display: none;">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input name="state" type="hidden" value="publish" class="state" autocomplete="off">
				<input name="meme[data][postID]" type="hidden" value="" class="postID" autocomplete="off">
				<input name="meme[main_meme]" type="hidden" value="/source/img/meme/1.jpg" class="main-meme" autocomplete="off">
				<input name="meme[type]" type="hidden" value="popular" class="type" autocomplete="off">
				<input name="meme[color_top]" type="hidden" value="#000000" class="color-top" autocomplete="off">
				<input name="meme[color_bottom]" type="hidden" value="#000000" class="color-bottom" autocomplete="off">
				<input name="meme[size_top]" type="hidden" value="2" class="size-top" autocomplete="off">
				<input name="meme[size_bottom]" type="hidden" value="2" class="size-bottom" autocomplete="off">
				<input name="meme[category]" type="hidden" id="category" value="">
			</div>
   			</form>

			<div class="hidden-select-file" style="display: none;">
				<input type="file" name="filedata">
			</div>

			<div class="down_butts">
				<button type="button" id="reset" class="btn-reset">RESET</button>
				<button type="button" id="save_draft" class="btn-save" style="display: none;">SAVE</button>
				<button type="button" id="publish" class="btn-publish">PUBLISH</button>
			</div>
			</div>
@endsection

@section('script')
<script type="text/javascript" src="/source/js/tools/meme.js"></script>
@endsection