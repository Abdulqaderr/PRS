@extends('layouts.app')
@section('title', 'Edit Profile | Admin Panel')
@section('content')
<p class="mainText">Edit Profile</p>

<div class="infoPanel editPanel">
    <img src="{{$data[1]}}" alt="" class="profileImg">

    <form action="/uploadUserImage" method="post" enctype="multipart/form-data">
        @csrf
        <label for="image" class="changePicture">Change your profile picture</label>
        <input type="file" name="image" id="image">
    </form>

    <form class="formLogin" action="/changeProfile" method="post">
        @csrf
        <p class="mailTitle">Full Name</p>
        <input type="text" id="mail" name="fname" value="{{$data[3]}}" placeholder="Full Name">


        <p class="mailTitle">Email</p>
        <input type="mail" id="password" name="mail" value="{{$data[0]}}" placeholder="Email">

        <p class="mailTitle">Phone</p>
        <input type="text" id="password" name="phone" value="{{$data[2]}}" placeholder="Phone">

        <p class="mailTitle">Password</p>
        <input type="Password" id="password" name="password" placeholder="Password">
        {{-- <button class="btn-login-submit" type="submit" value="login">Login</button> --}}
        <div class="formBtn">
            <button type="submit" class="saveEPBtn">Edit Profile</button>
            <a href="/profile" class="cancelEPBtn">Cancel</a>
        </div>
    </form>

</div>
@endsection
@section('js')
<script>
    var select = document.getElementById('image');
    select.onchange = function() {
        this.form.submit();
    };

</script>
@endsection
