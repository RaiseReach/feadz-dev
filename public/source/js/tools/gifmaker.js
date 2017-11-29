var video_loaded = false;
var current_file;

function lock_buttons() {
	$('.choose-buttons, .yb-clip-upl, input.caption-input').hide(1000);
}

function getYouTubeIdFromURL(url) {
    var match = url.match(/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/);
    return (match&&match[7].length==11)? match[7]:false;
}

function loadYbVideoById(id_vid) {
    var tag = document.createElement('script');
    startt = 0;
    secs = 1000;
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    var player;
    id_video = id_vid;
    video_loaded = true;
}

function onPlayerReady(event) {
    event.target.playVideo();
    player.seekTo(startt);
    player.mute();
    $('.txt-caption').show(1000);
    $(".nstSlider[data-id='1']").nstSlider("set_range", 0, player.getDuration());
    timeout_id = setTimeout(loopy, secs);
  }

function loopy(event) {
    player.seekTo(startt);
    setTimeout(loopy, secs);
}

function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
      videoId: id_video,
      playerVars: { 'autoplay': 1, 'controls': 0, 'disablekb': 1, 'fs': 0, 'modestbranding': 1, 'showinfo': 0, 'rel': 0},
      events: {
        'onReady': onPlayerReady,
      }
    });
}


$('.btn-preview').click(function() {
	$('input.state').val('preview');
    $('#create-gifmaker').ajaxSubmit({
        dataType: "json",
        success: function (response) {
            if (response.success == true) {
            	$('.main-preview .info').text('Feadz GIF Preview');

            	response.content.title = response.content.title === null ?  'No title' : response.content.title;
            	$('.main-preview .title').text(response.content.title);
            	response.content.description = response.content.description === null ?  'No description' : response.content.description;
            	$('.main-preview .description').text(response.content.description);

            	// tags
            	$('.main-preview .tags').html('');
            	$.each(response.tags, function (i, value) {
            		$('.main-preview .tags').append('<div class="tag">' + value + '</div>');
            	});

            	var folder = (response.gif.search('/') != -1) ? '/temporary/' : '/uploads/';
             	$('.gif-img img').attr('src', '/files' + folder + response.gif);
            	$('.main-preview').modal().open();
            } else {
            	writeErrors(response);
            }
        }
    });
});

$('.btn-publish').click(function() {
	$('input.state').val('publish');
    $('#create-gifmaker').ajaxSubmit({
        dataType: "json",
        success: function (response) {
            if (response.success == true) {
            	window.location.href = '/success' + response.link;
            } else {
            	writeErrors('', response);
            }
        }
    });
});


function writeErrors(error = '', response) {
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

$(".choose-buttons button").click(function() {
	var type = $(this).data('card');
	$(".choose-buttons button").removeClass("active");
	$(this).addClass("active");
	switch(type) {
		case 'url_video':
			$('.yb-clip-upl').show(300);
			$('.upload-file').hide(250);
			break;

		case 'video_file':
			$('.yb-clip-upl').hide(300);
			$('.upload-file').show(300);
			break;
	}
});

$('button.add-file').click(function() {
	$('#input-video').click();
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
			if(file.type != 'video/mp4') {
				writeErrors('Invalid file type! Please, upload a video with the format mp4.');
			} else if (file.size/1024/1024 > 15) {
				writeErrors('The file size must not exceed 15 MB.');
			} else {
				current_file = file;
				var url = URL.createObjectURL(file);
				$('.iframe-youtube').html("<video id='player-user' src='"+url+"'  autoplay muted loop></video><div class='txt-caption'> </div>");

				variant_upload_video = 3;
				video_loaded = true;
				$('.btn-create-gif').show(1000);
				video = document.getElementById('player-user');
				videoStartTime = 0;
				durationTime = 1;
				video.addEventListener('loadedmetadata', function() {
					this.currentTime = videoStartTime;
					$(".nstSlider[data-id='1']").nstSlider("set_range", 0, parseInt(video.duration));
					$('.txt-caption').show(1000);
					video_loaded = true;
				}, false);

				video.addEventListener('timeupdate', function() {
					if(this.currentTime > videoStartTime + durationTime){
						this.currentTime = videoStartTime;
					}
				});
			}
        }
    }
});

$('#input-video').on("change", function() {
	var files = this.files[0];
	if(files.type != 'video/mp4') {
		writeErrors('Invalid file type! Please, upload a video with the format mp4.');
	} else if (files.size/1024/1024 > 15) {
		writeErrors('The file size must not exceed 15 MB.');
	} else {
		variant_upload_video = 2;
		video_loaded = true;
		$('.btn-create-gif').show(1000);
		var url = URL.createObjectURL(files);

		$('.iframe-youtube').html("<video id='player-user' src='"+url+"'  autoplay muted loop></video><div class='txt-caption'> </div>");

		video = document.getElementById('player-user');
		videoStartTime = 0;
		durationTime = 1;
		video.addEventListener('loadedmetadata', function() {
			this.currentTime = videoStartTime;
			$(".nstSlider[data-id='1']").nstSlider("set_range", 0, parseInt(video.duration));
			$('.txt-caption').show(1000);
			video_loaded = true;
		}, false);

		video.addEventListener('timeupdate', function() {
			if(this.currentTime > videoStartTime + durationTime){
				this.currentTime = videoStartTime;
			}
		});
	}
});

$(".btn-create-gif button").click(function() {
	$('.btn-create-gif button').hide(1000);

	// Progress bar
	$('.progressbar').show(1000);

    var elem = document.getElementById("myBar"); 
    var width = 1;
    var id = setInterval(frame, 200);
    function frame() {
        if (width >= 90) {
            clearInterval(id);
            var id = setInterval(frame2, 10000);
        } else {
            width++; 
            $('.percent-bar').html(width + '%');
            elem.style.width = width + '%'; 
        }
    }


    function frame2() {
        if (width >= 97) {
        	clearInterval(id);
        } else {
            width++; 
            $('.percent-bar').html(width + '%');
            elem.style.width = width + '%'; 
        }
    }

    function frame3() {
    	if(width > 99) clearInterval(id);
        width++; 
        $('.percent-bar').html(width + '%');
        elem.style.width = width + '%'; 
    }

	var caption = $('input.caption-input').val();
	$('.un_caption').val(caption);


	if(variant_upload_video == 1) {
		$('.un_variant').val(1);
		$('#create-gif-from-yb').ajaxSubmit({
			dataType: "json",
			success: function (data) {
				clearInterval(id);
				var id = setInterval(frame3, 10);
				$('.progressbar').hide(1000);
				$('.successfully-create, .down_butts').show(1000);

				$('.gif-input').val(data.gif);
				$('.iframe-youtube').html("<img class='picture-gif' src='/files/temporary/"+data.gif+"' />");
				//$('.editor').css({'display': 'block'});
				$('.input-form-photo').val(data.thumbnail_main); 
				lock_buttons();
			}
		});
	} else if(variant_upload_video == 2) {
		$('.un_variant').val(2);
		var document_file = document.getElementById("input-video");
		var files = document_file.files[0];
		var data = new FormData();

	    data.append('file', files);

		$.ajax({
		    url : 'http://146.185.164.150/blob.php',
		    type : 'POST',
		    data : data,
		    processData: false,
		    dataType: "json",
		    contentType: false,
		    success : function(data) {
		    	if(data.success == true) {
		    		$(".un_filename").val(data.filename);
		    		myselfVideo();
		    	}
	        }
		});
	} else if(variant_upload_video == 3) {
		$('.un_variant').val(2);
		var data = new FormData();

	    data.append('file', current_file);

		$.ajax({
		    url : 'http://146.185.164.150/blob.php',
		    type : 'POST',
		    data : data,
		    processData: false,
		    dataType: "json",
		    contentType: false,
		    success : function(data) {
		    	if(data.success == true) {
		    		$(".un_filename").val(data.filename);
		    		myselfVideo();
		    	}
	        }
		});
	}
});

function myselfVideo() {
	$('#create-gif-from-yb').ajaxSubmit({
		dataType: "json",
		success: function (data) {
			$('.progressbar').hide(1000);
			$('.successfully-create, .down_butts').show(1000);

			$('.gif-input').val(data.gif);
			$('.iframe-youtube').html("<img class='picture-gif' src='/files/temporary/"+data.gif+"' />");
			//$('.editor').css({'display': 'block'});
			$('.input-form-photo').val(data.thumbnail_main); 
			lock_buttons();
		}
	});
}

$('input.caption-input').on("change", function(){
	var text = $(this).val();

	if(text.length != 0) {
		$('.txt-caption').text(text);
		if(video_loaded == true) {
			$('.txt-caption').show(1000);
		}
	} else {
		$('.txt-caption').hide(1000);
	}
});

$('button.button-input-yb-clip').click(function() {
	var value_yb = $('input.input-yb-clip').val();

	if(value_yb == "") return false;

	if(getYouTubeIdFromURL(value_yb) != "") {
		loadYbVideoById(getYouTubeIdFromURL(value_yb));
		$('.btn-create-gif').show(1500);
		$('.un_video_url').val(value_yb);
		video_loaded = true;
		variant_upload_video = 1;
	}
});


$('.nstSlider').nstSlider({
    "left_grip_selector": ".leftGrip",
    "value_bar_selector": ".bar",
    "value_changed_callback": function(cause, leftValue, rightValue) {
    	var id = $(this).data('id');

    	if(id == 1) {
    		startt = leftValue;

    		if(typeof video != "undefined") {
    			videoStartTime = leftValue;
    			video.currentTime = videoStartTime;
    		}

    		$('.un_start_time').val(leftValue);
    		leftValue = Math.floor(leftValue / 60) + ':' + leftValue % 60;
    		$('input.start-time').val(leftValue);

    	} else if (id == 2) {
    		durationTime = leftValue;
    		secs = parseInt(leftValue + '000');

    		if(typeof video != "undefined") {
    			videoStartTime = startt;
    			video.currentTime = videoStartTime;
    		}

    		$('.un_end_time').val(leftValue);
    		leftValue = Math.floor(leftValue / 60) + ':' + leftValue % 60;
    		$('input.duration-time').val(leftValue);
    	}
    }
});

$('.start-time').on("change", function() {
	var value = $(this).val();
	var data  = value.split(":", 2);
	var seconds = parseInt(data[0]) * 60 + parseInt(data[1]);

	if(isNaN(seconds)) {
		$(".nstSlider[data-id='1']").nstSlider("set_position", 0);
		$(this).val('0:0');
	} else {
		$(".nstSlider[data-id='1']").nstSlider("set_position", seconds);
	}
});

$('.duration-time').on("change", function() {
	var value = $(this).val();
	var data  = value.split(":", 2);
	var seconds  = parseInt(data[0]) * 60 + parseInt(data[1]);

	if(!isNaN(seconds)) {
		if(seconds <= 0 || seconds > 5) {
			$(this).val('0:1');
			$(".nstSlider[data-id='2']").nstSlider("set_position", 0);
		} else { 
			$(".nstSlider[data-id='2']").nstSlider("set_position", seconds);
		}
	} else {
		$(".nstSlider[data-id='2']").nstSlider("set_position", 0);
		$(this).val('0:1');
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


// set default category "gif"
$('.choose-category .dropdown-toggle').text('GIF');