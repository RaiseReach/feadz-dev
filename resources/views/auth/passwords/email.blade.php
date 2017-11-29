@extends('page')

@section('content')
        <div class="login">
            <div class="wrap">
                <div class="title">RESET PASSWORD</div>
                <div class="form">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">   
                        {{ csrf_field() }}
                        <input type="email" name="email" placeholder="E-mail" value="{{ old('email') }}">
                        @if (count($errors) > 0)
                            <div class="alerts">
                                @foreach ($errors->all() as $error)
                                        <div class="alert">{{ $error }}</div>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <button>Send Reset Link</button>
                    </form>
                </div>
            </div>
        </div>
@endsection