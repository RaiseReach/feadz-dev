@extends('page')
@section('title')Search @endsection
@section('content')
	       		<div class="left-posts">
	   		 		<div class="title">
		       			<div class="title-text-big">Featured posts</div>
		       			<div class="title-text-sm">We all need it so fead it!</div>
	       			</div>
	       			@foreach($posts as $post)
	       			<div class="post">
	       				<div class="img"><a target="_blank" href="{{ url($post->author_name . '/' . $post->url) }}"><img width="848px" height="659px" src="{{ url('files/uploads/' . $post->description_image)}}" /></a></div>
	       				<div class="bottom-post">
	       					<div class="published">Published on {{ $post->created_at->diffForHumans() }}, by <a href="/{{ $post->author_name }}">{{ $post->author_name }}</a></div>
							<div class="title-post">{{ $post->description_title }}</div>
							<div class="description-post">{{ $post->description_text }}</div>
							<div class="vertical-line"></div>
				            <div class="lower-block">
			                	<div class="left">
	                                <div class="left-sharing">
		                    		    <button class="sharing" data-delay="800" data-singleton="true" data-trigger="hover" data-popout="true"  data-html="true"  data-toggle="popover" data-placement="top" data-content="
	                                        <div class='feadz-social-net'>
		                    				   <div class='share-text'>Share</div>
								   			   <button data-title='{{  $post->description_title }}' data-url='{{  url($post->author_name.'/'.$post->url) }}' data-image='{{ url('files/uploads/' . $post->description_image) }}' data-type='fb' class='fb-social-icon'> </button>
								   			   <button data-title='{{  $post->description_title }}' data-url='{{  url($post->author_name.'/'.$post->url) }}' data-type='pi' class='p-social-icon'> </button>
								   			   <button data-title='{{  $post->description_title }}' data-url='{{  url($post->author_name.'/'.$post->url) }}' data-type='li' class='in-social-icon'> </button>
								   			   <button data-title='{{  $post->description_title }}' data-url='{{  url($post->author_name.'/'.$post->url) }}' data-type='tw' class='tw-social-icon'> </button>
								   		    </div>"
	                                    ></button>
	                                </div>
			                		<button class="feadr" data-post_id="{{ $post->id }}" data-trigger="focus" data-toggle="popover" data-placement="top" data-content="Feadr!"></button>
								</div>
			                	<div class="right">
			                		<div class="feadz-like">
			                			<button class="like-img {{ count($post->like) != 0 ? 'active' : ''}}" data-post_id="{{ $post->id }}"></button>
			                			<a class="like-amount" href="{{ url($post->author_name . '/' . $post->url . '/#feadz-like') }}">{{ $post->likes_count }}</a>
			                		</div>
			                		<div class="feadz-comment">
			                			<a class="comment-img {{ count($post->comment) != 0 ? 'active' : ''}}" href="{{ url($post->author_name . '/' . $post->url . '/#comments') }}"></a>
			                			<a class="comment-amount" href="{{ url($post->author_name . '/' . $post->url . '/#comments') }}">{{ $post->comments_count }}</a>
			                		</div>
			                	</div>
				            </div>
			            </div>
	       			</div>
	       			@endforeach
	       			<div class="no-posts" style="display: none;">NO MORE POSTS</div>
	       			@if(($posts->currentPage() != $posts->lastPage()) and $posts->lastPage() != 0)
	       			<button type="button" class="profile_show_more">SHOW MORE</button>
	       			@endif
	       		</div>
	       		<div class="right-posts">
	   		 		<div class="title-right">
		       			<div class="title-text-big">Hot Today</div>
		       			<div class="title-text-sm">We all need it so fead it!</div>
	       			</div>
	       			<div class="blurb-section">
	       				@foreach($hotToday as $post)
		       			<div class="post-sm">
		       				<div class="img"><a href="{{ url($post->author_name . '/' . $post->url) }}"><img width="262px" height="200px" src="{{ url('files/uploads/' . $post->description_image) }}" /></a></div>
		       				<div class="title-post-sm">{{ $post->description_title }}</div>
		       				<div class="vertical-line"></div>
		       			</div>
		       			@endforeach
		       		</div>
		       		<div class="connect-with-us">
		       			<div class="connect">Connect with Us</div>
		       			<div class="share-butts">
                                <a class="butt-for-sharing facebook" href="https://www.facebook.com/GoFeadz/"></a>
                                <a class="butt-for-sharing pinterest" href="https://www.pinterest.com/gofeadz/"></a>
                                <a class="butt-for-sharing twitter" href=""></a>
                                <a class="butt-for-sharing instagram" href=""></a>
		       			</div>
		       			@if((Auth::check() and Auth::user()->email_for_news == '') or !Auth::check())
		       			<div class="subs">Subscribe to our newsletter</div>
						<div class="subs-newsletter">
		   					<input class="subs-input" placeholder="Write your Email" type="email" name="">
		   					<button type="button"></button>
		   				</div>
		   				@endif
			            <div class="hidden-share" style="display: none;">
			                <div class="feadz-social-net">
			                    <div class="share-text">Share</div>
			                    <button data-title="" data-url="" data-type="fb" class="fb-social-icon"> </button>
			                    <button data-title="" data-url="" data-type="" class="p-social-icon"> </button>
			                    <button data-title="" data-url="" data-type="li" class="in-social-icon"> </button>
			                    <button data-title="" data-url="" data-type="tw" class="tw-social-icon"> </button>
			                </div>
			            </div>
		       		</div>
	       		</div>
@endsection
@section('script')
<script>
 $(document).ready(function(){
    var posts = {
        'current_page' : 1
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

    if($('.post').length == 0) {
    	$('.no-posts').show();
    }

    $('button.profile_show_more').click(function() {
	    $.ajax({
	        type: 'GET',
	        url: window.location.pathname + '?page=' + ++posts.current_page,
	        success: function (response) {
	        	var obj = JSON.parse(response);
	        	var clone = $('.post').clone();
	        	var share = $('.hidden-share > div').clone();
                if (obj.to === obj.total || obj.next_page_url == null) {
                    $('button.profile_show_more').hide(300);
                    $('.no-posts').show(100);
                }
                createElements(obj, clone, share, '');
	        }
	    });
    });

    function createElements(obj, clone, share, content) {
    	$.each(obj.data, function( key, value ) {
            value.like.length != 0 ? $(clone).find('.like-img').addClass('active') : $(clone).find('.like-img').removeClass('active');
            value.comment.length != 0 ? $(clone).find('.comment-img').addClass('active') : $(clone).find('.comment-img').removeClass('active');
    		$(clone).find('a').attr('href', '/' + value.author_name + '/' + value.url);
    		$(clone).find('.img img').attr('src', '/files/uploads/' + value.description_image);
    		$(clone).find('a.like-amount').text(value.likes_count);
    		$(clone).find('a.comment-amount').text(value.comments_count);
    		$(clone).find('a.text').text(value.description_title);
    		$(share).find('button.fb-social-icon').attr('data-url', location.hostname + '/' + value.author_name + '/' + value.url).attr('data-title', value.description_title);
    		$(share).find('button.p-social-icon').attr('data-url', location.hostname + '/' + value.author_name + '/' + value.url).attr('data-title', value.description_title);
    		$(share).find('button.in-social-icon').attr('data-url', location.hostname + '/' + value.author_name + '/' + value.url).attr('data-title', value.description_title);
    		$(share).find('button.tw-social-icon').attr('data-url', location.hostname + '/' + value.author_name + '/' + value.url).attr('data-title', value.description_title);
    		$(clone).find('button.sharing').attr('data-content', share[0].outerHTML);
    		content += clone[0].outerHTML;
    	});
    	$(content).hide().insertAfter('.left-posts .post:last').show(500);
    	popover.load();
    }
 });
</script>
@endsection