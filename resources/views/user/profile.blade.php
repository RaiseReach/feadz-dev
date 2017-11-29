@extends('page')
@section('title'){{ $user->name }} @endsection
@section('user_info')
		<div class="background">
			<div class="wrap">
				<div class="user-block">
					<div class="top-part">
						<div class="user-photo"><img src="{{ $user->photo == '' ? '/source/img/default-avatar.png' : '/files/uploads/' . $user->photo }}"></div>
						<div class="user-info">
							<div class="user-name">{{ $user->name }}</div>
							<div class="feadz-member">Feadz Member</div>
							<div class="channel-views">{{ $views }}</div>
							<div class="user-description">{{ $user->description }}</div>
						</div>
						@if($editButton)<button class="edit" onclick="window.location.href = '/user/settings'"></button>@endif
					</div>
					<div class="lower-part">
						<button class="user-buttons active" data-type="overview">OVERVIEW</button>
						<button class="user-buttons" data-type="posts">POSTS</button>
						<button class="user-buttons" data-type="likes">UPVOTES</button>
						<button class="user-buttons" data-type="comments">COMMENTS</button>
					</div>
				</div>
			</div>
		</div>
@endsection
@section('content')
   		<div class="left-side">
			<div class="heading">LATEST OVERVIEWS</div>
   			@foreach($overview as $object)
   				@php
   				if (isset($object->message)) {
   					$type = 'comment';
   				} else if(isset($object->description_title)) {
   					$type = 'post';
   				} else {
   					$type = 'like';
   				}
   				@endphp
   				@if($type == 'post')
	   			<div class="post">
	   				@if($editButton)
	   				<div class="editing-class">
	   					<button class="edit" data-url="{{ $object->url }}"></button>
	   					<button class="remove" data-id="{{ $object->id }}"></button>
	   				</div>
	   				@endif
	   				<div class="img"><a target="_blank" href="{{ url($object->author_name . '/' . $object->url) }}"><img width="848px" height="659px" src="{{ url('/files/uploads/' . $object->description_image) }}" /></a></div>
	   				<div class="bottom-post">
						<div class="title-post">{{ $object->description_title }}</div>
						<div class="description-post">{{ $object->description_text }}</div>
						<div class="vertical-line"></div>
			            <div class="lower-block">
		                	<div class="left">
	                            <div class="left-sharing">
	                    		    <button class="sharing" data-delay="800" data-singleton="true" data-trigger="hover" data-popout="true"  data-html="true"  data-toggle="popover" data-placement="top" data-content="
	                                    <div class='feadz-social-net'>
	                    				   <div class='share-text'>Share</div>
							   			   <button data-title='{{  $object->description_title }}' data-url='{{  url($object->author_name.'/'.$object->url) }}' data-image='{{ url('files/uploads/' . $object->description_image) }}' data-type='fb' class='fb-social-icon'> </button>
							   			   <button data-title='{{  $object->description_title }}' data-url='{{  url($object->author_name.'/'.$object->url) }}' data-type='pi' class='p-social-icon'> </button>
							   			   <button data-title='{{  $object->description_title }}' data-url='{{  url($object->author_name.'/'.$object->url) }}' data-type='li' class='in-social-icon'> </button>
							   			   <button data-title='{{  $object->description_title }}' data-url='{{  url($object->author_name.'/'.$object->url) }}' data-type='tw' class='tw-social-icon'> </button>
							   		    </div>"
	                                ></button>
	                            </div>
		                		<button class="feadr" data-post_id="{{ $object->id }}" data-trigger="focus" data-toggle="popover" data-placement="top" data-content="Feadr!"></button>
					        </div>
		                	<div class="right">
		                		<div class="feadz-like">
		                			<button class="like-img {{ count($object->like) != 0 ? 'active' : ''}}" data-post_id="{{ $object->id }}"></button>
		                			<a class="like-amount" href="{{  url($object->author_name.'/'.$object->url.'/#feadz-like') }}">{{ $object->likes_count }}</a>
		                		</div>
		                		<div class="feadz-comment">
		                			<a class="comment-img {{ count($object->comment) != 0 ? 'active' : ''}}" href="{{  url($object->author_name.'/'.$object->url.'/#comments') }}"></a>
		                			<a class="comment-amount" href="{{  url($object->author_name.'/'.$object->url.'/#comments') }}">{{ $object->comments_count }}</a>
		                		</div>
		                	</div>
			            </div>
		            </div>
	   			</div>
   				@elseif($type == 'comment')
   				@php
   					$photo  = $object->author->photo == null ? '/source/img/default-avatar.png' : '/files/uploads/' . $object->author->photo;
   				@endphp
				<div class="user-comment">
					<div class="user-photo">
						<img src="{{ url($photo)}}">
					</div>
					<div class="content">
						<div class="comment-head">
							<a href="{{ url($object->author->name) }}"><div class="user-name">{{ $object->author->name }}</div></a>
							<div class="comment-time">{{ $object->created_at->diffForHumans()}}</div>
						</div>
						<div class="comment">{{ $object->message }}</div>
					</div>
					<div class="post-photo"><a href="{{ url($object->parentPost->author_name, $object->parentPost->url) }}"><img src="{{ url('/files/uploads', $object->parentPost->description_image) }}"></a></div>
					<div class="horizontal-line"></div>
				</div>
   				@else
   				@php
   					$photo  = $object->user->photo == null ? '/source/img/default-avatar.png' : '/files/uploads/' . $object->user->photo;
   				@endphp
	        	<div class="user-upvote">
					<div class="user-photo">
						<img src="{{ url($photo) }}">
					</div>
					<div class="upvote"><strong>{{ $object->user->name }}</strong> liked <a href="{{ url($object->parentPost->author_name, $object->parentPost->url) }}">{{ $object->parentPost->description_title }}</a></div>
					<div class="upvote-time">{{ $object->created_at->diffForHumans()}}</div>
	        	</div>
   				@endif
   			@endforeach
   			@if(count($overview) == 0)
   				<div class="no-posts" style="">NO MORE OVERVIEW</div>
   				<button type="button" class="profile_show_more" style="display: none;">SHOW MORE</button>
   			@else 
   				<div class="no-posts" style="display: none;">NO MORE OVERVIEW</div>
   				<button type="button" class="profile_show_more">SHOW MORE</button>
   			@endif
   		</div>
   		<div class="right-side">
		 		<div class="title-right">
       			<div class="title-text-big">Hot Today</div>
       			<div class="title-text-sm">We all need it so fead it!</div>
   			</div>
   			<div class="blurb-section">
            @foreach($hotToday as $post)
       			<div class="post-sm">
       				<div class="img"><a href="{{ url($post->author_name . '/' . $post->url) }}"><img width="262px" height="200px" src="{{ url('/files/uploads/' . $post->description_image)}}" /></a></div>
       				<div class="title-post-sm">{{ $post->description_title }}</div>
       				<div class="vertical-line"></div>
       			</div>
            @endforeach
       		</div>
       		<div class="connect-with-us">
       			<div class="connect">Connect with Us</div>
       			<div class="share-butts">
                <a data-title="" data-url="" data-type="fb"  class="butt-for-sharing facebook" href=""></a>
                <a data-title="" data-url="" data-type="pt"  class="butt-for-sharing pinterest" href=""></a>
                <a data-title="" data-url="" data-type="tw"  class="butt-for-sharing twitter" href=""></a>
                <a data-title="" data-url="" data-type="li"  class="butt-for-sharing instagram" href=""></a>
       			</div>
       			<div class="subs">Subscribe to our newsletter</div>
				<div class="subs-newsletter">
   					<input class="subs-input" placeholder="Write your Email" type="email" name="">
   					<button type="button"></button>
   				</div>
       		</div>
	        <div class="hidden-share" style="display: none;">
	            <div class="feadz-social-net">
	                <div class="share-text">Share</div>
	                <button data-title="" data-url="" data-type="fb" class="fb-social-icon"> </button>
	                <button data-title="" data-url="" data-type="pi" class="p-social-icon"> </button>
	                <button data-title="" data-url="" data-type="li" class="in-social-icon"> </button>
	                <button data-title="" data-url="" data-type="tw" class="tw-social-icon"> </button>
	            </div>
	        </div>
	        <div class="hidden-comment" style="display: none;">
				<div class="user-comment">
					<div class="user-photo">
						<img src/>
					</div>
					<div class="content">
						<div class="comment-head">
							<a href=""><div class="user-name"></div></a>
							<div class="comment-time"></div>
						</div>
						<div class="comment"></div>
					</div>
					<div class="post-photo"><a href=""><img /></a></div>
					<div class="horizontal-line"></div>
				</div>
	        </div>
	        <div class="hidden-upvote" style="display: none;">
	        	<div class="user-upvote">
					<div class="user-photo">
						<img src="/source/img/default-avatar.png">
					</div>
					<div class="upvote"><strong></strong> liked <a ></a></div>
					<div class="upvote-time">2017-11-06 09:52:38</div>
	        	</div>
	        </div>
	        <div class="hidden-elements" style="display: none;"></div>
	        <div class="hidden-post" style="display: none;">
	   			<div class="post">
	   				@if($editButton)
	   				<div class="editing-class">
	   					<button class="edit" data-url=""></button>
	   					<button class="remove" data-id=""></button>
	   				</div>
	   				@endif
	   				<div class="img"><a href=""><img width="848px" height="659px" src="" /></a></div>
	   				<div class="bottom-post">
						<div class="title-post"></div>
						<div class="description-post"></div>
						<div class="vertical-line"></div>
			            <div class="lower-block">
		                	<div class="left">
		                		<div class="left-sharing">
		                			<button class="sharing" data-html="true"  data-toggle="popover" data-placement="top" data-content=''></button>
		                		</div>
		                		<button class="feadr" data-toggle="popover" data-placement="top" data-content="Feadr!"></button>
	                            <div class="right-sharing">
	                    		     <button class="report" data-singleton="true" data-trigger="focus" data-popout="true"  data-html="true"  data-toggle="popover" data-placement="top" data-content='

			                              <div class="report-body">
			                                <input type="radio" id="report_type1" name="report_type" value="Spam" checked><label for="report_type1"><span></span>Spam</label><br>
			                                <input type="radio" id="report_type2" name="report_type" value="Pornography"><label for="report_type2"><span></span>Pornography</label><br>
			                                <input type="radio" id="report_type3" name="report_type" value="Hatred and bullying"><label for="report_type3"><span></span>Hatred and bullying</label><br>
			                                <input type="radio" id="report_type4" name="report_type" value="Self-Harm"><label for="report_type4"><span></span>Self-Harm</label><br>
			                                <input type="radio" id="report_type5" name="report_type" value="Violent, gory and harmful content"><label for="report_type5"><span></span>Violent, gory and harmful content</label><br>
			                                <input type="radio" id="report_type6" name="report_type" value="Child Porn"><label for="report_type6"><span></span>Child Porn</label><br>
			                                <input type="radio" id="report_type7" name="report_type" value="Illegal activities e.g. Drug Uses"><label for="report_type7"><span></span>Illegal activities e.g. Drug Uses</label><br>
			                                <input type="radio" id="report_type8" name="report_type" value="Deceptive content"><label for="report_type8"><span></span>Deceptive content</label><br>
			                                <input type="radio" id="report_type9" name="report_type" value="Copyright and trademark infringement"><label for="report_type9"><span></span>Copyright and trademark infringement</label><br>
			                                <input type="radio" id="report_type10" name="report_type" value="I just don&#8217t like it"><label for="report_type10"><span></span>I just don&#8217t like it</label><br>
			                              </div>
			                              <div class="report-footer">
			                              	<button type="button" class="btn btn-cancel" data-dismiss="modal">CANCEL</button>
			                                <button id="report-submit-btn" type="button" class="btn btn-send" data-dismiss="modal">SEND</button>
			                              </div>
	                    		     '></button>
	                            </div>
							</div>
		                	<div class="right">
		                		<div class="feadz-like">
		                			<button class="like-img" data-post_id></button>
		                			<a class="like-amount" href=""></a>
		                		</div>
		                		<div class="feadz-comment">
		                			<a class="comment-img" href=""></a>
		                			<a class="comment-amount" href=""></a>
		                		</div>
		                	</div>
			            </div>
		            </div>
	   			</div>
	        </div>
   		</div>
@endsection
@section('script')
<script>
$(document).ready(function(){

	var profile = {
		'current_page' : 1,
		'current_type' : 'overview',
		'editButtons'  : {{ $editButton }}
	};

    var popover = {
        load: function() {
            $(".report").popover({ trigger: "manual" , html: true})
            .on("click", function () {
                var _this = this;
                $(this).popover("show");
                $(".btn-cancel, #report-submit-btn").on("click", function () {
                    $(_this).popover('hide');
                });
            });
            $('.feadr').popover();
            $(".sharing").popover({ trigger: "manual" , html: true})
                .on("mouseenter", function () {
                    var _this = this;
                    $(this).popover("show");
                    $(".popover").on("mouseleave", function () {
                        $(_this).popover('hide');
                    });
                }).on("mouseleave", function () {
                    var _this = this;
                    setTimeout(function () {
                        if (!$(".popover:hover").length) {
                            $(_this).popover("hide");
                        }
                    }, 200);
            });
        }
    };
    popover.load();

	$('.left-side').on('click', '.editing-class .edit', function() {
		var url = $(this).data('url');
		window.location.href = "/edit/" + url;
	});

	$('.left-side').on('click', '.editing-class .remove', function() {
		var id = $(this).data('id');
	    $.ajax({
	        type: 'POST',
	        data: {'post_id' : id, '_token' : "{{ csrf_token() }}"},
	        url: '/addition/delete-post',
	        success: function (response) {
	        	if(response.success == true) {
	        		$('button.remove[data-id="' + id +'"]').parents('.post').hide(300, function() {
	        			$(this).remove();
	        		});
	        	}
	        }
	    });
	});

	$('.user-block .lower-part button').click(function() {
		$('.lower-part button').removeClass('active');
		$(this).addClass('active');
		$('.left-side .post, .left-side .user-comment, .left-side .user-upvote').hide(100).remove();
		profile.current_type = $(this).data('type');
		profile.current_page = 0;
		switch(profile.current_type) {
			case 'comments':
				$('.no-posts').text('NO MORE COMMENTS');
				$('.left-side .heading').text("LATEST COMMENTS");
			    $.ajax({
			        type: 'GET',
			        url: window.location.pathname + '?type=comments&page=' + ++profile.current_page,
			        success: function (response) {
			        	var obj = JSON.parse(response);
			        	if(obj.comments.length == 0) {
			        		$('button.profile_show_more').hide();
			        		$('.no-posts').show(300);
			        	} else {
				        	var clone = $('.hidden-comment > div').clone();
				        	var share = '';
							createElements(obj.comments, clone, share, 'comments');
							$('button.profile_show_more').show();
							$('.no-posts').hide();
			        	}
			        }
			    });
				break;
			case 'likes':
				$('.no-posts').text('NO MORE UPVOTES');
				$('.left-side .heading').text("LATEST UPVOTES");
			    $.ajax({
			        type: 'GET',
			        url: window.location.pathname + '?type=likes&page=' + ++profile.current_page,
			        success: function (response) {
			        	let obj = JSON.parse(response);
			        	if(obj.likes.length == 0) {
			        		$('button.profile_show_more').hide();
			        		$('.no-posts').show(300);
			        	} else {
				        	let clone = $('.right-side .hidden-upvote > div').clone();
				        	let share = '';
			        		createElements(obj.likes, clone, share, 'likes');
			        		$('button.profile_show_more').show();
			        		$('.no-posts').hide();
			        	}
			        }
			    });
				break;
			case 'posts':
				$('.no-posts').text('NO MORE POSTS');
				$('.left-side .heading').text("LATEST POSTS");
			    $.ajax({
			        type: 'GET',
			        url: window.location.pathname + '?type=posts&page=' + ++profile.current_page,
			        success: function (response) {
			        	let obj = JSON.parse(response);
			        	if(obj.posts.length == 0) {
			        		$('button.profile_show_more').hide();
			        		$('.no-posts').show(300);
			        	} else {
				        	let clone = $('.right-side .hidden-post > div').clone();
				        	let share = $('.hidden-share > div').clone();
			                createElements(obj.posts, clone, share, 'posts');
			                $('button.profile_show_more').show();
			                $('.no-posts').hide();
			        	}
			        }
			    });
			    break;
			default:
				$('.no-posts').text('NO MORE OVERVIEW');
				$('.left-side .heading').text("LATEST OVERVIEWS");
			    $.ajax({
			        type: 'GET',
			        url: window.location.pathname + '?type=overview&page=' + ++profile.current_page,
			        success: function (response) {
			        	let obj = JSON.parse(response);
			        	let share = $('.hidden-share > div').clone();
			        	if(obj.length == 0) {
			        		$('button.profile_show_more').hide();
			        		$('.no-posts').show(300);
			        	} else {
				        	$.each(obj, function( key, value ) {
				        		if(typeof value.author_name !== "undefined") {
				        			var post = { 0 : value } ;
				        			var clone = $('.right-side .hidden-post > div').clone();
				        			createElements(post, clone, share, 'posts');
				        		} else if(typeof value.message !== "undefined") {
				        			var comment = { 0 : value } ;
				        			var clone = $('.right-side .hidden-comment > div').clone();
				        			createElements(comment, clone, share, 'comments');
				        		} else {
				        			var like = { 0 : value } ;
				        			var clone = $('.right-side .hidden-upvote > div').clone();
				        			createElements(like, clone, share, 'likes');
				        		}
				        	});
				        	$('.no-posts').hide();
				        	$('button.profile_show_more').show();
			        	}
			        }
			    });
			    break;
		}
	});

    $('button.profile_show_more').click(function() {
	    $.ajax({
	        type: 'GET',
	        url: window.location.pathname + '?type=' + profile.current_type +'&page=' + ++profile.current_page,
	        success: function (response) {
	        	var obj = JSON.parse(response);
	        	var share = $('.hidden-share > div').clone();
	        	if(profile.current_type == 'comments') {
	        		var clone = $('.hidden-comment').clone();
	        		if(obj.comments.length == 0) {
	        			$('button.profile_show_more').hide(300);
	        			$('.no-posts').show(300);
	        		} else {
	        			createElements(obj.comments, clone, share, profile.current_type);
	        		}
	        	} else if(profile.current_type == 'likes') {
	        		var clone = $('.hidden-upvote > div').clone();
	        		if(obj.likes.length == 0) {
	        			$('button.profile_show_more').hide(300);
	        			$('.no-posts').show(300);
	        		} else {
	        			createElements(obj.likes, clone, share, profile.current_type);
	        		}
	        	} else if(profile.current_type == 'posts') {
	        		var clone = $('.right-side .hidden-post > div').clone();
	        		if(obj.posts.length == 0) {
	        			$('button.profile_show_more').hide(300);
	        			$('.no-posts').show(300);
	        		} else {
	        			createElements(obj.posts, clone, share, profile.current_type);
	        		}
	        	} else {
	        		if(obj.length == 0) {
	        			$('button.profile_show_more').hide(300);
	        			$('.no-posts').show(300);
	        		} else {
			        	$.each(obj, function( key, value ) {
			        		if(typeof value.author_name !== "undefined") {
			        			var post = { 0 : value } ;
			        			var clone = $('.right-side .hidden-post > div').clone();
			        			createElements(post, clone, share, 'posts');
			        		} else if(typeof value.message !== "undefined") {
			        			var comment = { 0 : value } ;
			        			var clone = $('.right-side .hidden-comment > div').clone();
			        			createElements(comment, clone, share, 'comments');
			        		} else {
			        			var like = { 0 : value } ;
			        			var clone = $('.right-side .hidden-upvote > div').clone();
			        			createElements(like, clone, share, 'likes');
			        		}
			        	});
	        		}
	        	}
	        }
	    });
    });

    function createElements(obj, clone, share, type, content = '') {
    	switch(type) {
		    case 'comments':
		    	$.each(obj, function( key, value ) {
		    		$(clone).find('.user-photo img').attr('src', $('.top-part .user-photo img').attr('src'));
		    		$(clone).find('.comment-time').text(value.parent_post.created_at);
		    		$(clone).find('.user-name').text(value.author.name);
		    		$(clone).find('.comment').text(value.message);
		    		$(clone).find('.post-photo img').attr('src', '/files/uploads/' + value.parent_post.description_image);
		    		$(clone).find('.post-photo a').attr('href', '/' + value.parent_post.author_name + '/' + value.parent_post.url);
		    		var photo = value.author.photo == '' ? '/source/img/default-avatar.png' : '/files/uploads/' +value.author.photo;
		    		$(clone).find('.user-photo img').attr('src', photo);
		    		content += $(clone)[0].outerHTML;
		    	});
		    	$(content).hide().insertBefore('.left-side .no-posts').show(500);
		    	break;
		    case 'likes':
		    	$.each(obj, function( key, value ) {
		    		$(clone).find('strong').text(value.user.name);
		    		$(clone).find('a').text(value.parent_post.description_title).attr('href', '/' + value.user.name + '/' + value.parent_post.url);
		    		var photo = value.user.photo == '' ? '/source/img/default-avatar.png' : '/files/uploads/' +value.user.photo;
		    		$(clone).find('.user-photo img').attr('src', photo);
		    		content += $(clone)[0].outerHTML;
		    	});
		    	$(content).hide().insertBefore('.left-side .no-posts').show(500);
		    	break;
    		case 'posts':
		    	$.each(obj, function( key, value ) {
		            value.like.length != 0 ? $(clone).find('.like-img').addClass('active') : $(clone).find('.like-img').removeClass('active');
		            value.comment.length != 0 ? $(clone).find('.comment-img').addClass('active') : $(clone).find('.comment-img').removeClass('active');
		    		$(clone).find('a').attr('href', '/' + value.author_name + '/' + value.url);
		    		$(clone).find('.img img').attr('src', '/files/uploads/' + value.description_image);
		    		$(clone).find('a.like-amount').text(value.likes_count);
		    		$(clone).find('a.comment-amount').text(value.comments_count);
		    		$(clone).find('a.text').text(value.description_title);
		    		$(clone).find('.title-post').text(value.description_title);
		    		$(clone).find('.description-post').text(value.description_text);
		    		$(clone).find('button.like-img').attr('data-post_id', value.id);
		    		$(clone).find('button.edit').attr('data-url', value.url);
		    		$(clone).find('button.remove').attr('data-id', value.id);
		    		$(share).find('button.fb-social-icon').attr('data-url', location.hostname + '/' + value.author_name + '/' + value.url).attr('data-title', value.description_title);
		    		$(share).find('button.p-social-icon').attr('data-url', location.hostname + '/' + value.author_name + '/' + value.url).attr('data-title', value.description_title);
		    		$(share).find('button.in-social-icon').attr('data-url', location.hostname + '/' + value.author_name + '/' + value.url).attr('data-title', value.description_title);
		    		$(share).find('button.tw-social-icon').attr('data-url', location.hostname + '/' + value.author_name + '/' + value.url).attr('data-title', value.description_title);
		    		$(clone).find('button.sharing').attr('data-content', share[0].outerHTML);
		    		content += $(clone)[0].outerHTML;
		    	});
		    	$(content).hide().insertBefore('.left-side .no-posts').show(500);
		    	popover.load();
		    	break;
    	}
    }

});
</script>
@endsection