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
			type: 'photo',
			count: 1,
		}
	};

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
	    	tool.current.type = $(this).parent().data('type');
	        var dataTransfer =  e.originalEvent.dataTransfer;
	        if( dataTransfer && dataTransfer.files.length) {
	            e.preventDefault();
	            e.stopPropagation();
	            var file = dataTransfer.files[0];
	            var formData = new FormData();
				formData.append('filedata', file);
				$.ajax({
				       url : '/addition/save-imagee',
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
			case 'photo':
				$('.add-photo').hide();
				$('img.main-photo').remove();
				$('<img class="main-photo" src="/files/temporary/' + response.file + '" alt="main photo"/>').hide().appendTo('.header-photo').show('normal');
				$('.hidden-inputs .photo').val(response.file);
				break;

			case 'card':
				var content = $('.hidden-create > div').clone();
				$(content).find('img.story-img').attr('src', '/files/temporary/' + response.file);
				$(content).find('input').val(response.file);
				$(content).hide().insertBefore('div.upload-file').show('normal');
				break;

			default:
				break;
		}
	}

	$('.btn-preview').click(function() {
		$('input.state').val('preview');
        $('#create-story').ajaxSubmit({
            dataType: "json",
            success: function (response) {
                if (response.success == true) {


                	response.content.title = response.content.title === null ?  'No title' : response.content.title;
                	$('.main-preview .title').text(response.content.title);
                	response.content.description = response.content.description === null ?  'No description' : response.content.description;
                	$('.main-preview .description').text(response.content.description);

                	response.first = false;
                	response.items = '';
                	var carousel_bs = $('.hidden-carousel > div').clone();
                	$(carousel_bs).find('.item').remove();
                	if(response.cards.length != 0) {
	                	$.each(response.cards, function (i, value) {
	                		var folder = (value.search('/') != -1) ? '/temporary/' : '/uploads/';
	                		if(response.first == false) {
	                			response.items += '<div class="item active"><img src="/files' + folder + value +'" /></div>';
	                		} else {
	                			response.items += '<div class="item"><img src="/files' + folder + value +'" /></div>';
	                		}
	                		response.first = true;
	                	});
                	} else {
                		response.items = '<div class="item active"><img src="/source/img/noimg.jpg" /></div>';
                	}

                	$(carousel_bs).find('.story-counter span').text("/ "+ response.cards.length + " ");
                	$(carousel_bs).find('.carousel-inner').html(response.items);

                	$('.main-preview .description').append(carousel_bs);
                	// tags
                	$('.main-preview .tags').html('');
                	$.each(response.tags, function (i, value) {
                		$('.main-preview .tags').append('<div class="tag">' + value + '</div>');
                	});
                	$('.main-preview').modal({
                		onOpen: function() { 
                			$('.main-preview #myCarousel').carousel({'interval' : 2000 });
							$('.main-preview #myCarousel').on('slide.bs.carousel', function (e) {
							  var active = $(e.target).find('.carousel-inner > .item.active');
							  var next = $(e.relatedTarget);
							  var to = next.index();
							  $('.main-preview a.current').text(to + 1);
							})
                		}
                	}).open();

                } else {
                	writeErrors(response);
                }
            }
        });
	});

	$('.btn-publish').click(function() {
		$('input.state').val('publish');
        $('#create-story').ajaxSubmit({
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

	$('.editor').on('click', '.delete-story-img', function() {
		$(this).parent('div').hide('normal', function() {
			$(this).remove();
		})
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

	$('.story-create').on('click', 'button.add-file', function() {
		if($(this).data('type') == 'card') {
			tool.sizes.width.min = 17;
			tool.sizes.height.min = 9.559;
			tool.current.type = 'card';
		} else {
			tool.sizes.width.min = 17;
			tool.sizes.height.min = 9.622;
			tool.current.type = 'photo';
		}
		$('.hidden-select-file input[name="filedata"]').click();
	});
});