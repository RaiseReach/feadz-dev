@extends('page')

@section('content')
        <div class="login">
            <div class="title">RESET PASSWORD</div>
            <div class="form">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('password.request') }}">   
                    {{ csrf_field() }}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="email" class="email" name="email" placeholder="E-mail" value="{{ old('email') }}" required>
                    <input type="password" class="password" name="password" placeholder="Password" required>
                    <input type="password" class="password" name="password_confirmation" placeholder="Confirm Password" required>
                    @if (count($errors) > 0)
                        <div class="alerts">
                            @foreach ($errors->all() as $error)
                                    <div class="alert">{{ $error }}</div>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <button>RESET PASSWORD</button>
                </form>
            </div>
        </div>
@endsection