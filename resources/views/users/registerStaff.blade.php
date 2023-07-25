@extends('layouts.app')
@section('title', 'Register a new Staff | Admin Panel')
@section('content')
{{-- <p class="mainText mainStaffTXT">Register a new Staff</p> --}}

<div class="infoPanel staffPanel">
    <img src="https://firebasestorage.googleapis.com/v0/b/sars-e6e88.appspot.com/o/staff%2F711246-200.png?alt=media&token=ba2d69ae-9d05-4ba1-82da-06ea21077736" alt="" class="staffRIMG">

    @if($error != null)
    <div style="color:red;margin:7px 0px;">{{$error}}</div>

    @endif
    @if($success != null)
    <div style="color:green;margin:7px 0px;">{{$success}}</div>
    @endif

    <form class="formRegisterStaff" action="/register/new/staff" method="post">
        @csrf
        <p class="mailTitle">Username</p>
        <input type="text" id="mail" name="uname" value='{{$uname}}' placeholder="Staff username">


        <p class="mailTitle">Full Name</p>
        <input type="text" id="mail" name="fname" value='{{$fname}}' placeholder="Staff Full Name">


        <p class="mailTitle">Email</p>
        <input type="text" id="mail" name="mail" value='{{$mail}}' placeholder="Staff Email">

        <p class="mailTitle">Phone Number</p>
        <input type="text" id="mail" name="phone" value='{{$phone}}' placeholder="Staff Phone">

        <p class="mailTitle">Gender</p>
        <select id="gender" name="gender">
            <option value="f">Female</option>
            <option value="m">Male</option>
        </select>


        <p class="mailTitle">Password</p>
        <input type="password" id="mail" value='{{$password}}' name="password" placeholder="Password">


        <button class="btn-login-submit" type="submit" value="login">Create</button>
    </form>

</div>
@endsection
