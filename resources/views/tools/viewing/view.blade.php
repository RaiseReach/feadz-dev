@extends('page')
@section('title'){{ $post->description_title }} @endsection
@section('meta')
	<!-- Twitter Card data -->
	<meta name="twitter:card" value="summary">
	<meta property="og:site_name" content="FEADZ">

	<meta property="og:title" content="{{ $post->description_title }}" />
	<meta property="og:description" content="{{ $post->description_text }}" />
	@if($post->type == 'gifmaker')
	<meta property="og:type" content="video.other">
	<meta property="og:image" content="{{ url('files/uploads/' . $content[0]['gif']) }}" />
	<meta property="og:image:type" content="image/gif">
	<meta property="og:url" content="{{ url('files/uploads/' . $content[0]['gif']) }}">

		@if(isset($content[0]['video']))
	    <meta property="og:video" content="{{ url('files/uploads/' . $content[0]['video']) }}">
	    <meta property="og:video:secure_url" content="{{ url('files/uploads/' . $content[0]['video']) }}">
	    <meta property="og:video:type" content="video/mp4">
	    <meta property="og:video:width" content="512">
	    <meta property="og:video:height" content="288">
	    @endif
	@else
	<!-- Open Graph data -->
	<meta property="og:type" content="article" />
	<meta property="og:url" content="{{ url($post->author_name . '/' . $post->url) }}" />
	<meta property="og:image" content="{{ url('files/uploads/' . $post->description_image) }}" />
	<meta property="og:image:width" content="788" />
	<meta property="og:image:height" content="440" />
	@endif
@endsection
@section('content')
	       		<div class="left-post">
					<div class="post">
						<div class="navigation-buttons">
							<button class="prev" onclick="window.location.href = '{{ $prev }}'">PREV</button>
							<button class="back" onclick="window.location.href = '/home'">BACK TO MAIN</button>
							<button class="next" onclick="window.location.href = '{{ $next }}'">NEXT</button>
						</div>
						<div class="post-body">
                            <div class="info">
                                <div class="published">Published on {{ $date }} by <a href="/{{ $post->author_name }}">{{ $post->author_name }}</a></div>
                            </div>
							<div class="one-post-title">{{ $post->description_title }}</div>
							<div class="tags">
							@foreach($tags as $tag)
								<a href="{{ url('tag/' . $tag) }}">{{ $tag }}</a>
							@endforeach
							</div>
							<div class="horizontal-line"></div>
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
	                         	    @if(Auth::check())
	                    			<button class="feadr" data-post_id="{{ $post->id }}" data-trigger="focus" data-toggle="popover" data-placement="top" data-content="Feadr!"></button>
		                            <div class="right-sharing">
		                    		     <button class="report" data-trigger="focus" data-singleton="true" data-popout="true"  data-html="true"  data-toggle="popover" data-placement="bottom" data-content='
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
				                                <button id="report-submit-btn" data-post_id="{{ $post->id }}" type="button" class="btn btn-send" data-dismiss="modal">SEND</button>
				                              </div>
		                    		     '></button>
		                            </div>
		                            @endif
								</div>
				            	<div class="right">
				            		<div class="feadz-views">
				            			<button class="views-img"></button>
				            			<a class="views-amount" href="">{{ $views }}</a>
				            		</div>
				            		<div class="feadz-like" id="feadz-like">
				            			<button class="like-img {{ count($post->like) != 0 ? 'active' : ''}}" data-post_id="{{ $post->id }}"></button>
				            			<a class="like-amount" href="">{{ $likes }}</a>
				            		</div>
				            		<div class="feadz-comment">
				            			<a class="comment-img {{ count($post->comment) != 0 ? 'active' : ''}}" href="#comments"></a>
				            			<a class="comment-amount" href="#comments">{{ $post->comments_count }}</a>
				            		</div>
				            	</div>
				            </div>
								@yield('tool_content')
						</div>
						<div class="navigation-buttons bottom">
							<button class="prev" onclick="window.location.href = '{{ $prev }}'">PREV</button>
							<button class="back" onclick="window.location.href = '/home'">BACK TO MAIN</button>
							<button class="next" onclick="window.location.href = '{{ $next }}'">NEXT</button>
						</div>
		       			<div class="post-comments" id="comments">
							<div class="comments-head">

								<div class="comments-numb">Comments:<span>{{ $post->comments_count }}</span></div>
								@if(Auth::check())<button class="add-comment">ADD COMMENT</button>@endif

							</div>
							<div class="write-comment" style="display: none">
								<input type="hidden" name="post_id" value="{{ $post->id }}">
								<input type="hidden" name="parent_id" value="0">
			   					<textarea placeholder="Write a comment" type="text" name="message" maxlength="255"></textarea>
			   					<button type="button"></button>
			   				</div>
							@foreach($comments as $comment)
							<div class="comments-container">
								<div class="user-comment" data-comment_id="{{ $comment->id }}">
									<div class="user-photo">
										<img src="{{ $comment->author->photo == '' ? '/source/img/default-avatar.png' : '/files/uploads/' . $comment->author->photo }}">
									</div>
									<div class="content">
										<div class="comment-head">
											<a href="{{ url($comment->author->name) }}"><div class="user-name">{{ $comment->author->name }}</div></a>
											<div class="comment-time">{{ $comment->created_at->diffForHumans() }}</div>
										</div>
										<div class="comment">{{ $comment->message }}</div>
										<div class="comment-footer">
						            		<div class="comment-like">
						            			<button class="like-img" data-comment_id="{{ $comment->id }}"></button>
						            			<a class="like-amount" href="" data-comment_id="{{ $comment->id }}">{{ count($comment->likes) }}</a>
						            		</div>
						            		<div class="comment-total">
						            			<button class="comment-img" src=""></button>
						            			<a class="comment-amount" href="">{{ count($comment->allRepliesWithAuthor) }}</a>
						            		</div>
						            	</div>
									</div>
									<div class="horizontal-line"></div>
									@foreach($comment->allRepliesWithAuthor as $replie)
									<div class="reply">
										<div class="user-photo">
											<img src="{{ $replie->author->photo == '' ? '/source/img/default-avatar.png' : '/files/uploads/' . $replie->author->photo }}">
										</div>
										<div class="content">
											<div class="comment-head">
												<a href="{{ url($comment->author->name) }}"><div class="user-name">{{ $replie->author->name }}</div></a>
												<div class="comment-time">{{ $replie->created_at->diffForHumans() }}</div>
											</div>
											<div class="comment">{{ $replie->message }}</div>
											<div class="comment-footer">
							            		<div class="comment-like">
							            			<button class="like-img" data-comment_id="{{ $replie->id }}"></button>
							            			<a class="like-amount" href="" data-comment_id="{{ $replie->id }}">{{ count($replie->likes) }}</a>
							            		</div>
							            	</div>
										</div>
									</div>
									<div class="horizontal-line"></div>
									@endforeach
								</div>
							</div>
							@endforeach
							<button class="show-more" style="display: none">SHOW MORE</button>
		       			</div>
					</div>
	       			<div class="popular">
	   		 			<div class="title">
			       			<div class="title-text-big">Popular on Feadz</div>
			       			<div class="title-text-sm">We all need it so fead it!</div>
		       			</div>
		       			<div class="posts">
		       				@foreach($hotToday as $post)
			       			<div class="post-sm">
			       				<div class="img"><a href="{{ url($post->author_name . '/' . $post->url) }}"><img width="262px" height="200px" src="{{ url('/files/uploads/' . $post->description_image) }}" /></a></div>
			       				<div class="title-post-sm">{{ $post->description_title }}</div>
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
			       			@endforeach
		       			</div>
	       			</div>
	       		</div>
	       		<div class="right-post">
	   		 		<div class="title-right">
		       			<div class="title-text-big">Hot Today</div>
		       			<div class="title-text-sm">We all need it so fead it!</div>
	       			</div>
	       			<div class="hotToday-section">
	       				@foreach ($hotToday as $post)
		       			<div class="post-sm">
		       				<div class="img"><a target="_blank" href="{{ url($post->author_name . '/' . $post->url) }}"><img width="262px" height="200px" src="{{ url('/files/uploads/' . $post->description_image) }}" /></a></div>
		       				<div class="title-post-sm">{{ $post->description_title }}</div>
		       				<div class="horizontal-line"></div>
		       			</div>
		       			@endforeach
		       		</div>
		       		<div class="connect-with-us">
		       			<div class="blurb">
		                    <div class="img">
		                        <a href="http://raisereach.com/log-in/"><img  src="/source/img/raise-reach-big.png" /></a>
		                    </div>
		       			</div>
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
		       		</div>
	       		</div>
				<div id="modal-alert" class="modal-alert" style="display: none;">
					<button type="button" class="btn-close close fileapi-modal" data-dismiss="modal"> </button>
					<div class="title">Something went wrong</div>
					 <ul>
					 </ul>
				</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		$('.feadr').popover();

		var isLoggin = @php print Auth::check() ? 1 : 0;@endphp;

		$('.comment-like button.like-img').click(function() {
			var comment_id = $(this).data('comment_id');
			$.ajax({ 
				type: "POST", 
				url: '/addition/comment-like', 
				data: { 'comment_id' : comment_id, '_token': laravel_token },
				success: function(response) {
					if(response.success == true) {
						var number = response.method == 'like' ? 1 : -1;
						var current = parseInt($('a.like-amount[data-comment_id="' + comment_id +'"]').text());
						$('a.like-amount[data-comment_id="' + comment_id +'"]').text(current + number);
					}
				}
			});
		});

		$('.user-comment button.comment-img, .user-comment a.comment-amount').click(function() {
			if(isLoggin == false) return false;	
			var comment_id = $(this).parents('.user-comment').data('comment_id');
			var clone = $('.write-comment').clone();
			$('.write-comment.replies').hide(100).remove();
			$('.write-comment').hide(300);
			$(clone).addClass('replies');
			$(clone).find('input[name="parent_id"]').val(comment_id);
			$('.user-comment[data-comment_id="' + comment_id +'"]').after($(clone[0].outerHTML).show());
		});

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

		$.fn.hasAttr = function(name) {
		   return this.attr(name) !== undefined;
		};

	    $(".report").popover({ trigger: "manual" , html: true})
	        .on("click", function () {
	            var _this = this;
	           	if ($(".report").hasAttr("aria-describedby") == true) {
	           		$(_this).popover('hide');
	           	}
	           	else {
	           		$(_this).popover("show");
	           	}
	            $(".btn-cancel").on("click", function () {
	                $(_this).popover('hide');
	            });
	            $("#report-submit-btn").on("click", function () {
					var post_id = $(this).data('post_id');
					var reason = $('.report-body input:checked').val();
					$.ajax({ 
						type: "POST", 
						url: '/addition/add-report', 
						data: { 'reason' : reason, 'post_id' : post_id, '_token' : laravel_token},
					});
					$(_this).popover('hide');
	            });
	        });

	    $('button.add-comment').click(function() {
	    	$(this).hide(500);
	    	$('.post-comments .write-comment').show(500);
	    });

	    $('.post-comments').on('click', '.write-comment button', function() {
	    	var message = $(this).parents('.write-comment').find('textarea').val();
	    	if(message != '') {
	    		var post_id = $(this).parents('.write-comment').find('input[name="post_id"]').val();
	    		var parent_id = $(this).parents('.write-comment').find('input[name="parent_id"]').val();
				$.ajax({ 
					type: "POST", 
					url: '/addition/add-comment', 
					data: { 'message' : message, 'post_id' : post_id, 'parent_id' : parent_id, '_token' : laravel_token},
					success: function(response) {
						if(response.success == true) {
							location.reload();
						} else {
							writeErrors(response);
						}
					}
				});
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

		$('.close.fileapi-modal').click(function() {
			$.modal().close();
		});

	});
</script>
@yield('additional_script')
@endsection