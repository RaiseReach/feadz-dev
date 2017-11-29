@extends('page')
@section('title')Profile settings @endsection
@section('content')
@php
	$photo = Auth::user()->photo;
	$photo = $photo == '' ? '/source/img/default-avatar.png' : '/files/uploads/' . $photo;
	$checked = $user->hide_upvotes == 1 ? 'checked' : '';
@endphp
<div class="profile-settings">
	<form action="/user/settings" method="post">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="sides"> 
		<div class="left-side">
			<div class="title">Profile</div>
			<div class="horizontal-line"> </div>
			<div class="block-avatar">
				<div class="user-photo"><img src="{{ $photo }}" alt="user-photo"/></div>
				<div class="buttons">
					<button type="button" class="choose-file">CHOOSE FILE</button>
					<button type="button" class="random-file">Random</button>
				</div>
				<div class="rules">
					<div class="mimetype">JPG, JPEG, GIF or PNG</div>
					<div class="size">Max size: <b>2MB</b></div>
				</div>
			</div>
			<div class="block-user-info">
				<input type="text" name="real_name" placeholder="Your name and surname" value="{{ $user->real_name }}" maxlength="32">
				<textarea name="description" placeholder="Tell people who are you" maxlength="300">{{ $user->description }}</textarea>
			</div>
		</div>
		<div class="vertical-line"></div>
		<div class="right-side">
			<div class="title">Account</div>
			<div class="horizontal-line"> </div>
			<div class="block-account">
				<input type="text" name="name" class="username" placeholder="Username" value="{{ $user->name }}">
				<input type="email" name="email_for_news" class="email" placeholder="Email" value="{{ $user->email_for_news }}">
				<div class="block-upvotes">
					<div class="text">Upvotes</div>
					<div class="upvotes">
					    <div class="round">
					    	<input type="checkbox" id="checkbox" {{ $checked }} name="hide_upvotes" value="true" />
					    	<label for="checkbox"></label>
					    </div>
					    <div class="info">Hide my upvotes</div>
					</div>
				</div>
			</div>
			<div class="title password">Password</div>
			<div class="horizontal-line"> </div>
			<div class="block-password">
				<input type="password" name="password" placeholder="New Password">
				<input type="password" name="password_confirmation" placeholder="Re-Type New Password">
			</div>
		</div>
		<div class="horizontal-line-absolute"></div>
	</div>
	<button type="submit" class="save">SAVE</button>
	</form>
</div>
<div class="hidden-select-file" style="display: none;">
	<input type="file" name="filedata">
</div>
<div class="crop-photo" style="display: none;">
	<div class="text-photo">Please, select the area that you want to select as your photo.</div>
	<div class="modal-upload-column-img">
		<div class="popup__body"><div class="js-img"></div></div>
	</div>
	<div class="img-credentials">
		<div class="js-upload btn btn_browse btn_browse_small">SAVE</div>
	</div>
	<button type="button" class="close fileapi-modal"></button>
</div>
@endsection
@section('script')
<script>

	var tool = {
		token: laravel_token,
		sizes: {
			selection: {
				percents: '50%',
			},
			width: {
				max: 400,
				min: 50,
			},
			height: {
				min: 50,
			}
		}
	};
	tool.sizes.width.max = (screen.width >= 768) ? 700 : (screen.width <= 479) ? 300 : 400; 

	$('button.choose-file').click(function() {
		$('.hidden-select-file input[name="filedata"]').click();
	});

	$('.hidden-select-file').fileapi({
	   url: '/user/set-photo',
	   accept: 'image/*',
	   data: {'_token': laravel_token },
	   imageSize: { minWidth: 50, minHeight: 50 },
	   onFileComplete: function (evt, uiEvt) {
	   		var response = uiEvt.result; // server response
	   		if(response.success == true) {
	   			$('.user-photo img').attr('src', '/files/uploads/' + response.file);
	   		}
	   },
	   onSelect: function (evt, ui){
	      var file = ui.files[0];
	      if( !FileAPI.support.transform ) {
	         alert('Your browser does not support Flash :(');
	      }
	      else if( file ){
	         $('.crop-photo').modal({
	            closeOnEsc: true,
	            closeOnOverlayClick: false,
	            onOpen: function (overlay){
	               $(overlay).on('click', '.js-upload', function (){
	                  $.modal().close();
	                  $('.hidden-select-file').fileapi('upload');
	               });
	               $('.js-img', overlay).cropper({
	                  file: file,
	                  bgColor: '#fff',
					  maxSize: [tool.sizes.width.max, tool.sizes.width.max],
					  minSize: [tool.sizes.width.min, tool.sizes.height.min],
					  selection: tool.sizes.selection.percents,
	                  onSelect: function (coords){
	                     $('.hidden-select-file').fileapi('crop', file, coords);
	                  }
	               });
	            }
	         }).open();
	      }
	   }
	});

	$('button.random-file').click(function() {
		$.ajax({
		method: "POST",
		data: {'_token' : laravel_token},
		url: "/user/random-photo",
		}).done(function( response ) {
	   		if(response.success == true) {
	   			$('.user-photo img').attr('src', '/files/uploads/' + response.file);
	   		}
		});
	});
</script>
@endsection