@extends('page')
@section('title')My Stats @endsection
@section('content')
<div class="sides"> 
	<div class="left-side">
		<div class="title">Earning Status</div>
		<div class="horizontal-line"> </div>
		<div class="subtitle">Your trafic today</div>
		<div class="trafic-blocks-today">
			<div class="trafic-block views">
				<div class="views_value">{{ $views_today }}</div>
				<div class="trafic-text">Views</div>
			</div>
			<div class="trafic-block profit">
				<div class="views_value">0<span>$</span></div>
				<div class="trafic-text">Daily Profit</div>
			</div>
		</div>
		<div class="subtitle">Your trafic all time</div>
		<div class="trafic-blocks-all">
			<div class="trafic-block views">
				<div class="views_value">{{ $views_all }}</div>
				<div class="trafic-text">Views</div>
			</div>
			<div class="trafic-block profit">
				<div class="views_value">0<span>$</span></div>
				<div class="trafic-text">Daily Profit</div>
			</div>
		</div>
	</div>
	<div class="vertical-line"></div>
	<div class="right-side">
		<div class="title">Referral Program</div>
		<div class="horizontal-line"> </div>
		<div class="subtitle">Your invite link</div>
		<div class="invite-link">
			<input type="text" name="" value="{{ url('/ref/' . Auth::user()->name) }}" disabled="disabled">
			<button type="button" class="button-invite-link">COPY</button>
		</div>
		<div class="invite-text">Use our tools to invite your friends to join Feadz and get a % their posts for life! It’s fre for them as well and always will be! It’s a win!</div>
	</div>

</div>
@endsection
@section('script')
<script>
	$('button.button-invite-link').click(function() {
		$('.invite-link input').prop('disabled', false).select();
		document.execCommand("copy");
		$('.invite-link input').prop('disabled', true);
	});
</script>
@endsection