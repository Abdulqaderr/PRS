<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/register.css')}}">
    <title>Login</title>
</head>
<body>
    <div class="loginContainer">

        </p>
        <div class="logoForm">
            <img src="{{ asset('images/logo.png')}}" alt="" class="logoLogin">
            <p class="logoTXt forgetTXt">LOGIN</p>
            @if($errors->any())
            {!! implode('', $errors->all('<div style="color:red;margin:10px 0px;">:message</div>')) !!}
            @endif
            <form class="formLogin" action="/signIn" method="post">
                @csrf
                <p class="mailTitle">Email</p>
                <input type="text" id="mail" name="mail" placeholder="Email">
                <p class="mailTitle">password</p>
                <input type="password" id="password" name="password" placeholder="Password">
                <a href="/forget" class="forgetTXT">Forget Password</a>
                <a href="/register" class="newAccountTXT">Don’t Have an Account?</a>


                <button class="btn-login-submit" type="submit" value="login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
