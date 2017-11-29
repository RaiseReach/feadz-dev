@extends('page')
@section('title')Home page @endsection
@section('content')
            <div class="hidden-share" style="display: none;">
                <div class="feadz-social-net">
                    <div class="share-text">Share</div>
                    <button data-title="" data-url="" data-type="fb" class="fb-social-icon" data-image=""> </button>
                    <button data-title="" data-url="" data-type="pi" class="p-social-icon"> </button>
                    <button data-title="" data-url="" data-type="li" class="in-social-icon"> </button>
                    <button data-title="" data-url="" data-type="tw" class="tw-social-icon"> </button>
                </div>
            </div>
            <div class="hidden-blurb" style="display: none;">
                <div class="blurb-cell">
                    <div class="img">
                        <a href="http://raisereach.com/log-in/"><img  src="/source/img/raise-reach-sm.png" /></a>
                    </div>
                </div>
            </div>
       		<div class="title">
       			<div class="title-text-big">The Feadr</div>
       			<div class="title-text-sm">We all need it so fead it!</div>
       		</div>
       		<div class="bg-image"></div>
            <div class="feadz-cells" data-id="0">
            @php
                $current = 0;
                $count = count($posts);
                $insert_before = mt_rand(0, $count);
            @endphp
			@foreach($posts as $post)
                @if($current == $insert_before)
                <div class="blurb-cell">
                    <div class="img">
                        <a href="http://raisereach.com/log-in/"><img  src="/source/img/raise-reach-sm.png" /></a>
                    </div>
                </div>
                @endif
                <div class="feadz-cell" data-id="">
                	<div class="post-cell">
	                    <div class="img">
	                        <a href="{{ url($post->author_name . '/' . $post->url ) }}" target="_blank" class="img"><img src="{{ url('/files/uploads/' . $post->description_image ) }}" /></a>
	                    </div>
	                    <a href="{{ url($post->author_name . '/' . $post->url ) }}" target="_blank" class="text">{{ $post->description_title }}</a>
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
	                    			<a class="like-amount" href="{{ url($post->author_name.'/'.$post->url . '/#feadz-like') }}">{{ $post->likes_count }}</a>
	                    		</div>
	                    		<div class="feadz-comment">
	                    			<a class="comment-img {{ count($post->comment) != 0 ? 'active' : ''}}" href="{{ url($post->author_name.'/'.$post->url . '/#comments') }}"></a>
	                    			<a class="comment-amount" href="{{ url($post->author_name.'/'.$post->url . '/#comments') }}">{{ $post->comments_count }}</a>
	                    		</div>
	                    	</div>
	                    </div>
	                </div>
                </div>
                @php
                $current++;
                @endphp
            @endforeach
            </div>
            @if($posts->currentPage() != $posts->lastPage() and $posts->lastPage() != 0)
            <button type="button" class="headlines_show_more">SHOW MORE</button>
            @endif
            <div class="join_us">
                    <div class="header">Join Us</div>
                    <div class="vertical-line"></div>
                    <div class="distab">
                        <div class="left"><div class="youtube"><img src="/source/img/join-us.png"></div></div>
                        <div class="right">
                            <div class="join_us_title">Why join us? </div>
                            <div class="text">You built your tribe. Likes. Views.<br>Google and Facebook made billions from your efforts. Did they send you a royalty check for your hard work in delivering value?<br>We never got ours. Until now.<br>Likes, views and followers have VALUE! YouTube, Facebook, etc would not exist without them.<br>It's time to take the power back- and it's 100% free.<br>This changes everything. Join Feadz today.</div>
                            
                            @if (Auth::guest())
                                <button data-toggle="modal" data-target="#sign-up">JOIN NOW</button>
                            @else
                                <button data-toggle="modal" data-target="#upload-tool">Upload</button>
                            @endif
                        </div>
                    </div>
         		</div>	
            </div>
@endsection
@section('script')
<script>
$(document).ready(function(){

    var home = {
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

    $('button.headlines_show_more').click(function() {
	    $.ajax({
	        type: 'GET',
	        url: '/home?page=' + ++home.current_page,
	        success: function (response) {
	        	var obj = JSON.parse(response);
	        	var clone = $('.feadz-cell').clone();
	        	var share = $('.hidden-share > div').clone();
                if (obj.to === obj.total || obj.next_page_url == null) {
                    $('button.headlines_show_more').hide(300);
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
        content += $('.hidden-blurb').clone()[0].outerHTML;
    	$(content).hide().appendTo('.feadz-cells').show(500);
    	popover.load();
    }
});
</script>
@endsection