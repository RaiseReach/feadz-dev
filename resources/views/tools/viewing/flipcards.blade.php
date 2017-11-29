@extends('tools.viewing.view')
@section('tool_content')
		<div class="post-content flipcards">
		    <div class="description">{{ $post->description_text }}</div>
			<?php $card_id = 1; ?>
			@foreach($content as $cards => $card)
			<div class="item-title-card">{{ $card['card_item_title'] }}</div>
			<div class="flipcard">
				<div class="sides" data-card="{{ $card_id }}">
				@if ($card['card_type_front'] == "image")
					<div class="front-card" data-side="1"><img src="/files/uploads/{{ $card['front_card_image'] }}"><div class="flip-icon" data-card="{{ $card_id }}" data-side="1"></div></div>
				@else
					<div class="front-card" data-side="1" style="background-color: {{ $card['front_card_theme'] }}">{{ $card['front_card_text'] }}<div class="flip-icon" data-card="{{ $card_id }}" data-side="1"></div></div>
				@endif
				@if ($card['card_type_back'] == "image")
					<div class="back-card" data-side="2"><img src="/files/uploads/{{ $card['back_card_image'] }}"><div class="flip-icon" data-card="{{ $card_id }}" data-side="2"></div></div>
				@else
					<div class="back-card" data-side="2" style="background-color: {{ $card['back_card_theme'] }}">{{ $card['back_card_text'] }}<div class="flip-icon" data-card="{{ $card_id }}" data-side="2"></div></div>
				@endif
				</div>
			</div>
			<div class="flipcard-footer"></div>
			<?php $card_id++; ?>
			@endforeach
		</div>
@endsection
@section('additional_script')
<script>
$('div.flip-icon').click(function() {
	switch($(this).data('side')) {
		case 1:
			$('.flipcard .sides[data-card="' + $(this).data('card') + '"]').css({'-webkit-transform':'rotateY(180deg)'});
			break;

		case 2:
			$('.flipcard .sides[data-card="' + $(this).data('card') + '"]').css({'-webkit-transform':'rotateY(0deg)'});
			break;

		default:
			break;
	}
});
</script>
@endsection