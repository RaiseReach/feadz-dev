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

$("button.button-snip-create").click(function() {
    $('#create-snip').ajaxSubmit({
        dataType: "json",
        type: "POST",
        error: function(){
        	writeErrors(' ', 'The entered url address is invalid.');
	    },
        success: function (response) {
        	if(response.success == true) {
        		window.location.href = '/success' + response.link;
        	} else {
        		writeErrors(response);
        	}
        },
        timeout: 3000 
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