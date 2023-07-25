<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/register.css')}}">
    <title>Forget Password</title>
</head>
<body>
    <div class="loginContainer">

        <div class="logoForm">
            <img src="{{ asset('images/logo.png')}}" alt="" class="logoLogin">
            <p class="logoTXt forgetTXt">Forget Password</p>
            @if($errors->any())
            {!! implode('', $errors->all('<div style="color:red;margin:10px 0px;">:message</div>')) !!}
            @endif
            <form class="formLogin" action="/sendMail" method="post">
                @csrf
                <p class="mailTitle">Email</p>
                <input type="text" id="mail" name="mail" placeholder="Email">

                <button class="btn-login-submit sendMail" type="submit" value="login">Send Mail</button>
            </form>
        </div>

    </div>
</body>
</html>
