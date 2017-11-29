@extends('tools.viewing.view')
@section('tool_content')
		<div class="post-content rankedlist">
		    <div class="description">{{ $post->description_text }}</div>
			<?php $data_id = 1; ?>
			@foreach($data as $keys => $val)
			@foreach($val as $key => $value)
			<div class="card" data-id="{{ $data_id }}">
				<div class="info-card">
					<div class="vote">
						<div class="vote-button" data-pid="{{ $post->id }}" data-id="{{ $data_id }}" data-elemid="{{ $value['element_id'] }}"></div>
						<b data-id="{{ $data_id }}">+{{ $value['votes'] }}</b>
					</div>
					<div class="item-title-card">{{ $value['item_title'] }}</div>
				</div>
				@if($value['type_card'] == 'image')
					<div class="rankedlist-card">
						<div class="card-img"><img src="/files/uploads/{{ $value['image'] }}"></div>
						<div class="card-text">{{ $value['caption'] }}</div>
					</div>
				@else
					<div class="rankedlist-card">
						<div class="card-video">{!! $value['youtube'] !!}</div>
						<div class="card-text">{{ $value['caption'] }}</div>
					</div>
				@endif
				<div class="rankedlist-footer"></div>
			</div>
			@endforeach
			@endforeach
		</div>
@endsection
@section('additional_script')
<script>
	$(document).ready(function() {
		$('.post-content.rankedlist').on('click', '.vote-button', function() {
			current_id = $(this).attr('data-id');
			elem_id = $(this).data('elemid');
			pid = $(this).data('pid');
			$.post(
			  "/create/rankedlist/vote",
			  {
				cid: elem_id,
				pid: pid,
				_token: laravel_token
			  },
			  onSuccessVote
			);
			
			function onSuccessVote(data) {
				if(data.success == true) {
					votes_element = data.votes;
					$('.vote b[data-id="'+current_id+'"]').html('+'+votes_element);
					if(current_id != 1) {
						before_current_votes = parseInt($('.vote b[data-id="'+(current_id - 1)+'"]').html());
						if(votes_element > before_current_votes) {
							$('.card[data-id="'+current_id+'"]').attr('data-id', (current_id - 1));
							$('.card[data-id="'+(current_id - 1)+'"]:first').attr('data-id', (current_id));
							
							$('.vote-button[data-id="'+current_id+'"]').attr('data-id', (current_id - 1));
							$('.vote-button[data-id="'+(current_id - 1)+'"]:first').attr('data-id', (current_id));
							
							$('.vote b[data-id="'+current_id+'"]').attr('data-id', (current_id - 1));
							$('.vote b[data-id="'+(current_id - 1)+'"]:first').attr('data-id', (current_id));
							
							b_element = $('.card[data-id="'+(current_id)+'"]').html();
							
							$('.card[data-id="'+current_id+'"]').fadeToggle(500, function() {
								$('.card[data-id="'+current_id+'"]').remove();
								$('.card[data-id="'+(current_id - 1)+'"]').after('<div class="card" data-id="'+current_id+'">'+b_element+'</div>');
							});
						}
					}
				}
			}
		});
	})
</script>
@endsection