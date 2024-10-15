<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/css/signin.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
    <script src="{{ asset('/js/app.js') }}" defer></script>
    <title>ログインフォーム</title>
</head>

<body>
    <div class="login">
        <h1>ログイン</h1>
        @error('login_error')
            <p class="alert alert-danger">{{ $message }}</p>
        @enderror
        @if (session('logout'))
        <div class="alert alert-danger">{{session('logout')}}</div>            
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <input type="text" name="login_id" placeholder="ユーザID" />
                @error('login_id')
                    <p class="err_msg">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <input type="password" name="password" placeholder="パスワード" />
                @error('password')
                    <p class="err_msg">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-large">ログイン</button>
        </form>
    </div>
</body>


</html>
