$(document).ready(function() {

	var tool = {
		token: $('meta[name="csrf-token"]').attr('content'),
		sizes: {
			selection: {
				percents: '50%',
			},
			width: {
				max: 400,
				min: 10,
			},
			height: {
				min: 10,
			}
		},
		current: {
			type: 'meme',
			count: 1,
		}
	};

	tool.sizes.width.max = (screen.width >= 768) ? 700 : (screen.width <= 479) ? 300 : 400; 

	$('.hidden-select-file').fileapi({
	   url: '/addition/save-image',
	   accept: 'image/*',
	   imageSize: { minWidth: 100, minHeight: 100 },
	   data: {'_token': tool.token},
	   onFileComplete: function (evt, uiEvt) {
	   		var response = uiEvt.result; // server response
	   		if(response.success == true) {
	   			insertImage(response, tool.current);
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
	
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	$('.holder').on({
	    'dragover dragenter': function(e) {
	        e.preventDefault();
	        e.stopPropagation();
	    },
	    'drop': function(e) {
	        var dataTransfer =  e.originalEvent.dataTransfer;
	        if( dataTransfer && dataTransfer.files.length) {
	            e.preventDefault();
	            e.stopPropagation();
	            var file = dataTransfer.files[0];
	            var formData = new FormData();
				formData.append('filedata', file);
				$.ajax({
			       url : '/addition/save-image',
			       type : 'POST',
			       data : formData,
			       processData: false,
			       contentType: false,
			       success : function(response) {
			           insertImage(response, tool.current);
			       }
				});
	        }
	    }
	});

	function writeErrors(response, error = '') {
		if(error == '') {
	        $.each(response.errorText, function (i, value) {
	            error += '<li>' + value + '</li>';
	        });
	    } else {
	    	error = '<li>' + error + '</li>';
	    }
        $('.modal-alert > ul').html(error);
        $('.modal-alert').modal().open();
	}

	function insertImage(response, settings) {
		switch(settings.type) {
			case 'meme':
				$('input.main-meme').val(response.file);
				$('div.main-meme img').attr('src', '/files/temporary/' +response.file);
				$('.main-meme').show(100);
				$('.upload-file').hide();
				break;
		}
	}

	$('.choose-background-buttons button.background-button').click(function() {
		var color = $(this).data('theme_bg');
		var side  = $(this).parent().data('side');
		switch(side) {
			case 'top':
				$('div.top-text').css({'color': color});
				$('input.color-top').val(color);
				break;

			case 'bottom':
				$('div.bottom-text').css({'color': color});
				$('input.color-bottom').val(color);
				break;
		}
		$('button.background-button[data-side="' + side +'"]').css({'background-color': color });
	});

	$('.btn-publish').click(function() {
		$('input.state').val('publish');
        $('#create-meme').ajaxSubmit({
            dataType: "json",
            success: function (response) {
                if (response.success == true) {
                	window.location.href = '/success' + response.link;
                } else {
                	writeErrors(response);
                }
            }
        });
	});

	$('.add-tags button').click(function() {
		var tag = $('.add-tags input');
		tag.val($.trim(tag.val().replace(/\//g, " ")));
		if(tag.val().length != 0) {
			$('<div class="tag">' + tag.val() + '<button type="button" class="del-tag"></button></div>').hide().appendTo('.added-tags').show('normal');
			$('.added-tags-form').append('<input name="tags[]" value="' + tag.val() + '">');
			tag.val('');
		}
	});

	$('.added-tags').on('click', '.tag button', function() {
		$(this).parent('div').hide('normal', function() {
			var value = $(this).text();
			$('.added-tags-form input[value="' + $.trim(value) +'"]').remove();
			$(this).remove();
		})
	});

	$('.choose-category .dropdown-menu li > a').click(function() {
		var category = $(this).data('category');
		$('.choose-category .dropdown-toggle').text(category);
		$('input#category').val(category.toLowerCase());
	});

	$('.choose-permission .dropdown-menu li > a').click(function() {
		var permission = $(this).data('permission');
		$('.choose-permission .dropdown-toggle').text(permission);
	});

	$('.close.fileapi-modal').click(function() {
		$.modal().close();
	});

	$('#reset').click(function() {
		$('input.top-text-input, input.bottom-text-input').val('');
	});

	$(".choose-buttons button").click(function() {
		var type = $(this).data('card');
		$(".choose-buttons button").removeClass("active");
		$(this).addClass("active");
		switch(type) {
			case 'popular':
				$('.scroll-wrapper').show(300);
				$('.upload-file').hide();
				$('.main-meme').show();
				$('input.type').val(type);
				$('input.main-meme').val('/source/img/meme/1.jpg');
				$('.main-meme img').attr('src', '/source/img/meme/1.jpg');
				break;

			case 'new':
				$('.meme .scroll-wrapper').hide();
				$('.upload-file').show(300);
				$('.main-meme').hide();
				$('input.type').val(type);
				break;
		}
	});

	$('.scrollmenu-memes div.meme').click(function() {
		$('.scrollmenu-memes div.meme').removeClass('active');
		$(this).addClass('active');
		$('.main-meme img').attr('src', $(this).find('img').attr('src'));
		$('input.main-meme').val($(this).find('img').attr('src'));
	});

	$('input.top-text-input').bind('input keyup', function(){
		var value = $(this).val();
	    var $this = $(this);
	    var delay = 100; // 2 seconds delay after last input

	    clearTimeout($this.data('timer'));
	    $this.data('timer', setTimeout(function(){
	        $this.removeData('timer');
	        $('div.top-text').text(value);
	    }, delay));
	});

	$('input.bottom-text-input').bind('input keyup', function(){
		var value = $(this).val();
	    var $this = $(this);
	    var delay = 100; // 2 seconds delay after last input

	    clearTimeout($this.data('timer'));
	    $this.data('timer', setTimeout(function(){
	        $this.removeData('timer');
	        $('div.bottom-text').text(value);
	    }, delay));
	});

	$('button.up').click(function() {
		var side = $(this).data('side');
		var current_size;
		var default_fontsize;
		var ScreenWidth = window.innerWidth;
        if (ScreenWidth >= 1200) {
            default_fontsize    = 12;
        } else if (ScreenWidth <= 1199 && ScreenWidth  >= 768){
            default_fontsize    = 10;
        }
        else {
            default_fontsize    = 8;
        }
		switch(side) {
			case 'top':
				current_size = $('input.size-top').val();
				break;

			case 'bottom':
				current_size = $('input.size-bottom').val();
				break;
		}
		current_size = parseInt(current_size);
		if(current_size == 4) return false;
		current_size += 1;

		switch(side) {
			case 'top':
				$('input.size-top').val(current_size);
				$('div.top-text').css({'font-size' : default_fontsize * current_size });
				break;

			case 'bottom':
				$('input.size-bottom').val(current_size);
				$('div.bottom-text').css({'font-size' : default_fontsize * current_size });
				break;
		}

		$(this).parent('.meme-couter').find('.value').text(current_size);
	});

	$('button.add-file').click(function() {
		tool.sizes.width.min = 17;
		tool.sizes.height.min = 11;
		$('.hidden-select-file input[name="filedata"').click();
	});

	$('button.lower').click(function() {
		var side = $(this).data('side');
		var current_size;
		var default_fontsize;
		var ScreenWidth = window.innerWidth;
        if (ScreenWidth >= 1200) {
            default_fontsize    = 12;
        } else if (ScreenWidth <= 1199 && ScreenWidth  >= 768){
            default_fontsize    = 10;
        }
        else {
            default_fontsize    = 6;
        }

		switch(side) {
			case 'top':
				current_size = $('input.size-top').val();
				$('div.top-text').css({'font-size' : default_fontsize * current_size });
				console.log(default_fontsize * current_size);
				break;

			case 'bottom':
				current_size = $('input.size-bottom').val();
				$('div.bottom-text').css({'font-size' : default_fontsize * current_size });
				break;
		}
		current_size = parseInt(current_size);
		if(current_size == 1) return false;
		current_size -= 1;

		switch(side) {
			case 'top':
				$('input.size-top').val(current_size);
				break;

			case 'bottom':
				$('input.size-bottom').val(current_size);
				break;
		}

		$(this).parent('.meme-couter').find('.value').text(current_size);
	});
	var ScreenWidth = window.innerWidth;
	var ScrollbarWidth;
    if  (ScreenWidth  >= 768){
        ScrollbarWidth    = ScreenWidth - 15;
    }
    $(".editor.scrollbar-inner").width(ScrollbarWidth);
});