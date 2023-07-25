@extends('layouts.app')
@section('title', 'Profile | Admin Panel')
@section('content')

<p class="mainText">User Profile</p>

<div class="infoPanel">
    <img src="{{$data[1]}}" alt="" onclick='showImgPanel(this);' class="profileImg">


    <p class="pUserName">{{$data[3]}}</p>

    <p class="pUserRole">@if($data[4] == 'a') Admin @elseif($data[4]=='r') Resident @else User @endif</p>

    <hr />
    <div class="pUserInfo">
        <div class="profileItem">
            <p class="pFullNameTxt">Full Name</p>
            <p class="pFullName">{{$data[3]}}</p>
        </div>
        <div class="profileItem">
            <p class="pFullNameTxt">Email</p>
            <p class="pFullName">{{$data[0]}}</p>
        </div>
        <div class="profileItem">
            <p class="pFullNameTxt">phone</p>
            <p class="pFullName">{{$data[2]}}</p>
        </div>
    </div>
    <a href="/users/{{Request::segment(2)}}/delete/{{$data[5]}}" class="editProf">Delete Account</a>
    <div class="imgShowContainer" id="imgShowContainer">
        <p class="closePanel" onclick='closePanel()'>X</p>
        <img src="" alt="" class="imgPanel" id="imgPanel">

    </div>

</div>
@endsection
@section('js')
<script>
    function closePanel() {
        document.getElementById("imgShowContainer").style.display = "none";
    }

    function showImgPanel(src) {
        var imageSrc = src.getAttribute("src");
        document.getElementById("imgShowContainer").style.display = "block";
        document.getElementById("imgPanel").setAttribute("src", imageSrc);
    }

</script>
@endsection
