<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/register.css')}}">
    <title>Register</title>
</head>
<body>
    <div class="loginContainer">

        </p>
        <div class="logoForm">
            <img src="{{ asset('images/logo.png')}}" alt="" class="logoLogin">
            <p class="logoTXt forgetTXt">SIGN UP</p>

            <form class="formLogin" action="/register" method="post">
                @if($error != null)
                <div style="color:red;margin:7px 0px;">{{$error}}</div>

                @endif


                @csrf
                <p class="mailTitle">USER NAME</p>
                <input type="text" id="mail" name="username" value='{{$username}}' placeholder="Username" required autofocus autocomplete="username">





                <p class="mailTitle">FULL NAME</p>
                <input type="text" id="mail" name="fullname" value='{{$fullname}}' placeholder="Full Name" required autocomplete="fullname">



                <p class="mailTitle">EMAIL</p>
                <input type="mail" id="mail" name="mail" value='{{$mail}}' placeholder="Email" required autocomplete="email">



                <p class="mailTitle">PHONE NUMBER</p>
                <input type='text' id="mail" name="phone" placeholder="Phone Number" required autocomplete="phone">

                <p class="mailTitle">GENDER</p>
                <select name="gender" id="mail">


                    <option value="male">Male</option>
                    <option value="female">Female</option>

                </select>

                <p class="mailTitle">password</p>
                <input type="password" id="password" name="password" placeholder="Password" required autocomplete="new-password">



                <button class="btn-login-submit" type="submit" value="login">CREATE ACCOUNT</button>
            </form>
        </div>
    </div>
</body>
</html>
