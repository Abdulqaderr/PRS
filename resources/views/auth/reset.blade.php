<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/register.css')}}">
    <title>Reset Password</title>
</head>
<body>
    <div class="loginContainer">

        <div class="logoForm">
            <img src="{{ asset('images/logo.png')}}" alt="" class="logoLogin">
            <p class="logoTXt forgetTXt">Reset Password</p>
            @if($error != null)
            <div style="color:red;margin:7px 0px;">{{$error}}</div>

            @endif

            <form class="formLogin" action="/resetPassword" method="post">
                @csrf
                <p class="mailTitle">Code</p>
                <input type="text" id="mail" name="code" placeholder="Code" value="{{$data[0]}}">
                <p class="mailTitle">Password</p>
                <input type="password" id="password" name="password" placeholder="Password" value="{{$data[1]}}">

                <p class="mailTitle">Comfirm password</p>
                <input type="password" id="password" name="cpassword" placeholder="Password" value="{{$data[2]}}">

                <button class="btn-login-submit sendMail" type="submit" value="login">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
