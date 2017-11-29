@extends('tools.creating.common')
@section('title')Creating Snip @endsection
@section('tool_create')
		<div class="tool-title">Create your Feadz Snip</div>
		<div class="snip-create">
			<div class="subtitle">Add URL</div>
			<div class="snip-create-block">
				<form action="/create/snip/send" method="POST" id="create-snip">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input class="input-snip-create" placeholder="http://example.com" type="text" name="snip[data][url]">
					<button type="button" class="button-snip-create">SNIP</button>

				<div class="added-tags-form" style="display: none;">
					
				</div>
				<input name="snip[category]" type="hidden" id="category" value="">
				</form>
			</div>

		</div>
@endsection
@section('script')
<script type="text/javascript" src="/source/js/tools/snip.js"></script>
@endsection