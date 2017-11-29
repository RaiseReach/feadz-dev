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
		card: 1,
		side: 1,
		count: 1,
	}
};

tool.sizes.width.max = (screen.width >= 768) ? 700 : (screen.width <= 479) ? 300 : 400; 

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var loadDrag = function() {
	$('.holder').on({
	    'dragover dragenter': function(e) {
	        e.preventDefault();
	        e.stopPropagation();
	    },
	    'drop': function(e) {
	    	tool.current.type = $(this).parent().data('type');
	    	if(tool.current.type == 'card') {
	    		tool.current.side = $(this).parents('.sides > div').data('side');
	    		tool.current.card = $(this).parents('.sides').data('card');
	    	}
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
};

loadDrag();

$('.flipcards-create').on('click', 'button.add-file', function() {
	if($(this).data('type') == 'card') {
		tool.sizes.width.min = 17;
		tool.sizes.height.min = 9.559;
		tool.current.card = $(this).parents('.sides').data('card');
		tool.current.side = $(this).parents('.sides > div').data('side');
		tool.current.type = 'card';
	} else {
		tool.sizes.width.min = 17;
		tool.sizes.height.min = 9.622;
		tool.current.type = 'photo';
	}
	$('.hidden-select-file input[name="filedata"]').click();
});

$('.hidden-select-file').fileapi({
   url: '/addition/save-image',
   accept: 'image/*',
   data: {'_token': tool.token },
   imageSize: { minWidth: 100, minHeight: 100 },
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

$('.btn-preview').click(function() {
	$('input.state').val('preview');
    $('#create-flipcards').ajaxSubmit({
        dataType: "json",
        success: function (response) {
            if (response.success == true) {
            	$('.main-preview .info').text('Feadz Flipcards Preview');

            	response.content.title = response.content.title === null ?  'No title' : response.content.title;
            	$('.main-preview .title').text(response.content.title);
            	response.content.description = response.content.description === null ?  'No description' : response.content.description;
            	$('.main-preview .description').text(response.content.description);

            	// tags
            	$('.main-preview .tags').html('');
            	$.each(response.tags, function (i, value) {
            		$('.main-preview .tags').append('<div class="tag">' + value + '</div>');
            	});

            	var card_id = 1;
            	var repository = '/files';
            	$('.main-preview .flipcard').remove();
            	$('.main-preview .item-title-card').remove();
            	$.each(response.cards, function(i, value) {
            		var html = '';
            		if(value.card_item_title === null) value.card_item_title = "";
            		html += '<div class="item-title-card">' + value.card_item_title + '</div>';
            		html += '<div class="flipcard"> <div class="sides" data-card="'+ card_id +'">';
						if(value.card_type_front == "image") {
							var folder = (value.front_card_image.search('/') != -1) ? '/temporary/' : '/uploads/';
							if(value.front_card_image === null || value.front_card_image == "") value.front_card_image = "../../source/no-img.jpg";
							html += '<div class="front"><img src="'+ repository + folder + value.front_card_image+'" /><div class="flip-icon" data-side="1" data-card="' + card_id +'"></div></div>';
						} else {
							html += '<div class="front" style="background-color:' + value.front_card_theme +'">'+value.front_card_text+'<div class="flip-icon" data-card="' + card_id +'" data-side="1"></div></div>';
						}
						if(value.card_type_back == "image") {
							var folder = (value.back_card_image.search('/') != -1) ? '/temporary/' : '/uploads/';
							if(value.back_card_image === null || value.back_card_image == "") value.back_card_image = "../../source/no-img.jpg";
							html += '<div class="back"><img src="'+ repository + folder + value.back_card_image+'" /><div class="flip-icon" data-side="2" data-card="' + card_id +'"></div></div>';
						} else {
							html += '<div class="back" style="background-color: ' + value.back_card_theme +'">'+value.back_card_text+'<div class="flip-icon" data-card="' + card_id +'" data-side="2"></div></div>';
						}
            		html += '</div>';
            		$('.main-preview .flipcard-footer').before(html);
            		card_id++;
            	});
           
            	$('.main-preview').modal().open();
            } else {
            	writeErrors(response);
            }
        }
    });
});

$('.btn-publish').click(function() {
	$('input.state').val('publish');
    $('#create-flipcards').ajaxSubmit({
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
			var content = $('.hidden-content-photo > div').clone();
			if(settings.side == 1) {
				$(content).find('input.type').attr('name', 'flipcards[cards][' + tool.current.card +'][card_type_front]');
				$(content).find('input.card-image').attr('name', 'flipcards[cards][' + tool.current.card +'][front_card_image]');
				$(content).find('input.card-image').val(response.file);
			} else {
				$(content).find('input.type').attr('name', 'flipcards[cards][' + tool.current.card +'][card_type_back]');
				$(content).find('input.card-image').attr('name', 'flipcards[cards][' + tool.current.card +'][back_card_image]');
				$(content).find('input.card-image').val(response.file);
			}

			$('.sides[data-card=' + settings.card + '] div[data-side=' + settings.side + '] .create-flipcard').html('');
			$(content).hide().appendTo('.sides[data-card=' + settings.card + '] div[data-side=' + settings.side + '] .create-flipcard').show('slow');
			$('.sides[data-card=' + settings.card + '] div[data-side=' + settings.side + '] img.flipcard-img').attr("src", "/files/temporary/" + response.file);
			break;

		default:
			break;
	}
}

$('.btn-preview').click(function() {
	$('.main-preview').modal().open();
});

$('button.add-card').click(function() {
	tool.current.count++;
	var content = $('.hidden-content-add-card > div').clone();
	$(content).find('button.frontcard, button.backcard').attr('data-card', tool.current.count);
	$(content).find('.sides').attr('data-card', tool.current.count);
	$(content).find('input.item-title-input').attr('name', 'flipcards[cards]['+ tool.current.count +'][card_item_title]');
	$($(content)[0].outerHTML).hide().insertAfter('.editor .card:last').show('normal');
	loadDrag();
});

$('.editor').on('click', 'div.send-card-text button', function() {
	tool.current.card = $(this).parents('.sides').data('card');
	tool.current.side = $(this).parents('.sides > div').data('side');
	var text = $('.sides[data-card="' + tool.current.card + '"] div[data-side="' + tool.current.side + '"] input.input-card-text').val();
	var content = $('.hidden-content-text > div').clone();
	if(tool.current.side == 1) {
		$(content).find('textarea').attr('name', 'flipcards[cards][' + tool.current.card +'][front_card_text]')
		$(content).find('input.type').attr('name', 'flipcards[cards][' + tool.current.card +'][card_type_front]');
		$(content).find('input.color').attr('name', 'flipcards[cards][' + tool.current.card +'][front_card_theme]');
	} else {
		$(content).find('textarea').attr('name', 'flipcards[cards][' + tool.current.card +'][back_card_text]')
		$(content).find('input.type').attr('name', 'flipcards[cards][' + tool.current.card +'][card_type_back]');
		$(content).find('input.color').attr('name', 'flipcards[cards][' + tool.current.card +'][back_card_theme]');
	}

	$('.sides[data-card="' + tool.current.card + '"] div[data-side="' + tool.current.side + '"] .create-flipcard').html('');
	$(content).hide().appendTo('.sides[data-card="' + tool.current.card + '"] div[data-side="' + tool.current.side + '"] .create-flipcard').show('slow');
	$('.sides[data-card="' + tool.current.card + '"] div[data-side="' + tool.current.side + '"] .create-flipcard textarea').val(text);
});

$('.editor').on('click', '.delete-flipcard-text', function() {
	$(this).parents('.flipcard-text-type').hide('normal', function() {
		var content = $('.hidden-create').html();
		$(this).parent().html(content);
		$(this).remove();
		loadDrag();
	})
});

$('.editor').on('click', '.delete-flipcard-img', function() {
	$(this).parents('.flipcard-photo-type').hide('normal', function() {
		var content = $('.hidden-create').html();
		$(this).parent().html(content);
		$(this).remove();
		loadDrag();
	})
});

$('.editor').on('click', '.choose-background-buttons button', function() {
	tool.current.card = $(this).parents('.sides').data('card');
	tool.current.side = $(this).parents('.sides > div').data('side');
	var theme_bg = $(this).data("theme_bg");
	$(this).parents('.flipcard-text-type').css({'background-color': theme_bg });
	$('.sides[data-card="' + tool.current.card + '"] div[data-side="' + tool.current.side + '"] .background-button:first').css({'background-color': theme_bg });
	$('.sides[data-card="' + tool.current.card + '"] div[data-side="' + tool.current.side + '"] input.color').val(theme_bg);
});

$('.editor').on('click', '.choose-buttons button', function() {
	$('.choose-buttons button[data-card="' + $(this).data('card') +'"]').removeClass('active');
	$(this).addClass('active');
	switch($(this).data('side')) {
		case 1:
			$('.editor .sides[data-card="' + $(this).data('card') + '"]').css({'-webkit-transform':'rotateY(0deg)'});
			break;

		case 2:
			$('.editor .sides[data-card="' + $(this).data('card') + '"]').css({'-webkit-transform':'rotateY(180deg)'});
			break;

		default:
			break;
	}
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

$('body').on('click', '.flip-icon', function() {
	switch($(this).data('side')) {
		case 1:
			$('.main-preview .flipcard .sides[data-card="' + $(this).data('card') + '"]').css({'-webkit-transform':'rotateY(180deg)'});
			break;

		case 2:
			$('.main-preview .flipcard .sides[data-card="' + $(this).data('card') + '"]').css({'-webkit-transform':'rotateY(0deg)'});
			break;

		default:
			break;
	}
})