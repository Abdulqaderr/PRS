@extends('layouts.app')
@section('title', 'View Profile | Admin Panel')
@section('content')
@section('maintitle','View User Profile')

<div class="userSearchContainer">

    <form action="/users/{{Request::segment(2)}}/search" method="get">
        @csrf
        <div class="searchContainer">

            <div class="searchRelative userSearchRelative">
                <input type="text" name="search" id="mainSearch" placeholder="Search...">
                <button type="submit" class="searchIcon"><img src="{{ asset('icons/search.png')}}" alt=""></button>
            </div>
        </div>

    </form>
</div>

<div class="userPanel">

    @foreach ($users as $user)
    <a href="/users/{{Request::segment(2)}}/{{$user['id']}}">
        <div class="userCard">
            <div class="userIL">
                <img src="{{$user['profilePictureURL']}}" alt="" class="userIMGInfo">
                <p class="userIName">{{$user['username']}}</p>
                <p class="userIRole">@if($user['role'] == 'r') resident @else user @endif</p>
            </div>
            <div class="userIR">
                <p class="UNTXT">Full Name</p>
                <p class="UName">{{$user['fullname']}}</p>
                <p class="UMTXT">Email</p>
                <p class="UMail">{{$user['mail']}}</p>
                <p class="UPTXT">Phone</p>
                <p class="UPhone">{{$user['phonenumber']}}</p>
                <p class="UATXT">Address</p>
                <p class="UAddress">{{$user['address']}}</p>
            </div>
        </div>
    </a>

    @endforeach


</div>
@endsection
@section('js')
<script>
    function changeUserKind() {
        var userSelected = document.getElementById("users").value;

        if (userSelected == 'resident') {
            window.location.href = "/users/resident";
        } else {
            window.location.href = "/users/staff";
        }
    }

</script>
@endsection
