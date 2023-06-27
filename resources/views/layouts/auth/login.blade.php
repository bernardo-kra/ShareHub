@extends('layouts.auth-master')

@section('content')
    <form method="post" action="{{ route('login') }}" class="text-center">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <a href="/" class="position-absolute top-0 start-0 m-3 text-decoration-none">
            <i class="bi bi-arrow-left"></i>
        </a>
        <img class="mb-4" src="{!! url(
            'https://e7.pngegg.com/pngimages/719/649/png-clipart-laravel-software-framework-php-web-framework-model-view-controller-framework-angle-text.png',
        ) !!}" alt="" width="72" height="57">

        <h1 class="h3 mb-3 fw-normal">Login</h1>
        
        @include('layouts.partials.messages')
        <div class="form-group form-floating mb-3">
            <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username"
                required="required" autofocus>
            <label>Email or Username</label>
            @if ($errors->has('username'))
                <span class="text-danger text-left">{{ $errors->first('username') }}</span>
            @endif
        </div>

        <div class="form-group form-floating mb-3">
            <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Password"
                required="required">
            <label>Password</label>
            @if ($errors->has('password'))
                <span class="text-danger text-left">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <button class="w-100 btn btn-primary btn-lg" type="submit">Login</button>

        @include('layouts.auth.partials.copy')
    </form>
@endsection
