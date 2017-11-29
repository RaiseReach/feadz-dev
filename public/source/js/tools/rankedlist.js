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
	    		tool.current.card = $(this).parents('.card').data('card');
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
			       data : {formData, '_token': tool.token},
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

$('button.add-card').click(function() {
	tool.current.count++;
	var content = $('.hidden-content-add-card').clone();
	$(content).find('.card').attr('data-card', tool.current.count);
	$(content).find('input.item-title-input').attr('name', 'rankedlist[cards][' + tool.current.count +'][item_title]');
	$(content).find('input.caption-input').attr('name', 'rankedlist[cards][' + tool.current.count +'][caption_card]');
	$(content).find('input.type_card').attr('name', 'rankedlist[cards][' + tool.current.count +'][type_card]');
	$(content.html()).hide().insertBefore('.editor button.add-card').show('normal');
	loadDrag();
});

$('.editor').on('click', '.delete-rankedlist-img', function() {
	tool.current.card = $(this).parents('.card').data('card');
	$(this).parents('.card .rankedlist-photo-type').hide(1000).remove();
	var content = $('.hidden-create > div').clone();
	$('.card[data-card="' + tool.current.card +'"] .container-tool:first').after(content);
	loadDrag();
});

$('.rankedlist-create').on('click', 'button.add-file', function() {
	if($(this).data('type') == 'card') {
		tool.sizes.width.min = 17;
		tool.sizes.height.min = 9.559;
		tool.current.card = $(this).parents('.card').data('card');
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

function insertImage(response, settings) {
	switch(settings.type) {
		case 'photo':
			$('.add-photo').hide();
			$('img.main-photo').remove();
			$('<img class="main-photo" src="/files/temporary/' + response.file + '" alt="main photo"/>').hide().appendTo('.header-photo').show('normal');
			$('.hidden-inputs .photo').val(response.file);
			break;

		case 'card':
			$('.editor .card[data-card="' + tool.current.card + '"] .create-rankedlist').remove();
			var content = $('.hidden-content-photo > div').clone();
			$(content).find('img').attr('src', '/files/temporary/' + response.file);
			$(content).find('img:first').after('<input type="hidden" name="rankedlist[cards]['+ tool.current.card +'][image_card]" value="'+ response.file + '">');
			$('.editor .card[data-card="'+ tool.current.card +'"] input.type_card').val('image');
			$(content).hide().insertAfter('.editor .card[data-card="' + tool.current.card + '"] .container-tool:first').show('normal');

			break;

		default:
			break;
	}
}

$('.btn-preview').click(function() {
	$('input.state').val('preview');
    $('#create-rankedlist').ajaxSubmit({
        dataType: "json",
        success: function (response) {
            if (response.success == true) {
            	$('.main-preview .info').text('Feadz Rankedlist Preview');

            	response.content.title = response.content.title === null ?  'No title' : response.content.title;
            	$('.main-preview .title').text(response.content.title);
            	response.content.description = response.content.description === null ?  'No description' : response.content.description;
            	$('.main-preview .description').text(response.content.description);

            	// tags
            	$('.main-preview .tags').html('');
            	$.each(response.tags, function (i, value) {
            		$('.main-preview .tags').append('<div class="tag">' + value + '</div>');
            	});


            	var clone = $('.hidden-card-preview > div').clone();
            	$('.main-preview .card').remove();
            	var repository = '/files';
            	$.each(response.cards, function (i, value) {
            		if(value.type_card == "image") {
            			var folder = (value.image_card.search('/') != -1) ? '/temporary/' : '/uploads/';
            			if(value.image_card === null || value.image_card == '') value.image_card = '../../source/img/noimg.jpg';
            			var content = clone;
            			$(content).find('.item-title-card').text(value.item_title);
            			$(content).find('.card-text').text(value.caption_card);
            			$(content).find('img').attr('src', repository + folder + value.image_card);
            		} else {
            			if(value.youtube_clip === null || value.youtube_clip == '') value.youtube_clip = '../../source/img/noimg.jpg';
            			var content = clone;
            			$(content).find('.item-title-card').text(value.item_title);
            			$(content).find('.card-text').text(value.caption_card);
            			$(content).find('.card-img, .card-video').remove();
            			$(content).find('.card-text:first').after('<div class="card-video">'+ value.youtube_clip +'</div>');
            		}
            		$('.post-content.rankedlist').append(content[0].outerHTML);
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
    $('#create-rankedlist').ajaxSubmit({
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

$('.editor').on('click', '.send-card-video button', function() {
	tool.current.card = $(this).parents('.card').data('card');
	var value = $('.editor .card[data-card="' + tool.current.card + '"] input.input-card-video').val();
	$.ajax({
	    type: "POST",
	    url: '/addition/youtube-info',
	    data: { 'video_url' : value },
	    success: function(response) {
	    	if(response.success) {
	    		$('.editor .card[data-card="' + tool.current.card + '"] .create-rankedlist').remove();
	    		var content = $('.hidden-content-photo > div').clone();
	    		$(content).find('img').attr('src', response.thumbnail_url);
	    		$(content).find('img').after('<img class="youtube-icon" src="/source/img/youtube-icon.png" /> ');
	    		$(content).find('img:first').after('<input type="hidden" name="rankedlist[cards]['+ tool.current.card +'][youtube_clip]" value="'+ value + '">');
	    		$('.editor .card[data-card="'+ tool.current.card +'"] input.type_card').val('video');
	    		$(content).hide().insertAfter('.editor .card[data-card="' + tool.current.card + '"] .container-tool:first').show('normal');
	    	}
	    },
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