@extends('page')

@section('content')
		<div class="login">
			<div class="title">RESET PASSWORD</div>
			<div class="form">
				<form class="form-horizontal" role="form" method="POST" action="/password/reset">	
					<input type="hidden" class="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="email" class="email" name="email" placeholder="E-mail Address" value="{{ old('email') }}">
					@if (count($errors) > 0)
						<div class="alerts">
							@foreach ($errors->all() as $error)
									<div class="alert">{{ $error }}</div>
								@endforeach
							</ul>
						</div>
					@endif
					<button>SEND RESET LINK</button>
				</form>
			</div>
		</div>
@endsection
