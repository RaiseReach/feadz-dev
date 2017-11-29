@extends('tools.viewing.view')
@section('tool_content')
		<div class="post-content story">
		    <div class="description">{{ $post->description_text }}</div>
			<div id="myCarousel" class="carousel slide carousel-story" data-ride="carousel">

			  <!-- Wrapper for slides -->
				<div class="carousel-inner">
					<?php $first = false; ?>
					@foreach($content as $cards => $card)
					@if($first == true)
						<div class="item">
						  <img src="/files/uploads/{{ $card }}">
						</div>
					@else
						<div class="item active">
						  <img src="/files/uploads/{{ $card }}">
						</div>
						<?php $first = true ?>
					@endif
					@endforeach
				</div>
			

				<div class="story-carousel-control">
					<a class="left-carousel-control" href="#myCarousel" data-slide="prev"></a>
					<a class="right-carousel-control" href="#myCarousel" data-slide="next"></a>
				</div>

				<div class="story-counter">
					<a class="current">1</a><span>/</span> {{ count($content) }}
				</div>
				<div class="story-footer"></div>
			</div>
		</div>
@endsection
@section('additional_script')
<script>
$('#myCarousel').on('slide.bs.carousel', function (e) {
  var active = $(e.target).find('.carousel-inner > .item.active');
  var next = $(e.relatedTarget);
  var to = next.index();
  $('a.current').text(to + 1);
})
</script>
@endsection