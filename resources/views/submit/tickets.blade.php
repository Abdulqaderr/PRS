@extends('layouts.app')
@section('title', 'View Profile | Admin Panel')
@section('content')
@section('maintitle','View User Profile')

<div class="userSearchContainer">
    <form action="/tickets/{{Request::segment(2)}}/search" method="get">
        @csrf
        <div class="searchContainer">
            <select id="users" onchange="changeUserKind()" name="user">
                <option value="date" @if( Request::segment(2)=='date' ) selected @endif>Date</option>
                <option value="status" @if( Request::segment(2)=='status' ) selected @endif>Status</option>
            </select>
            <div class="searchRelative userSearchRelative">
                <input type="text" name="search" id="mainSearch" placeholder="Search...">
                <button type="submit" class="searchIcon"><img src="{{ asset('icons/search.png')}}" alt=""></button>
            </div>
        </div>

    </form>
</div>
<div class="userPanel ticketsPanel ">


    @foreach ($tickets as $ticket)
    <a href="/tickets/{{Request::segment(2)}}/show/{{$ticket['ticketID']}}">
        <div class="ticketCard">
            <div class="ticketIL">
                <img src="{{$ticket['userImage']}}" alt="" class="userIMGInfo">
                <p class="userIName">{{$ticket['userName']}}</p>
            </div>
            <div class="ticketIR">
                <p class="UNTXT">Ticket Type</p>
                <p class="UName">{{$ticket['typeOfTicket']}}</p>
                <p class="UMTXT">Description</p>
                <p class="UMail">{{$ticket['description']}}</p>
                <p class="UPTXT">Phone</p>
                <p class="UPhone">{{$ticket['phone']}}</p>
                <p class="UATXT">Address</p>
                <p class="UAddress">{{$ticket['address']}}</p>
                <p class="UATXT">Ticket Status</p>
                <p class="UAddress">
                    @if($ticket['status'] == 0)
                    Pending Ticket
                    @elseif($ticket['status'] == 1)
                    Approved Ticket
                    @elseif($ticket['status'] == 2)
                    Complete Ticket
                    @elseif($ticket['status'] == 3)
                    Rejected Ticket
                    @elseif($ticket['status'] == 4)
                    Canceled Ticket
                    @endif
                </p>
                <p class="UATXT">Date Submitted</p>
                <p class="UAddress">{{date('d-m-Y', strtotime($ticket['dateTime']))}}</p>
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
        console.log(userSelected);
        if (userSelected == 'date') {
            window.location.href = "/tickets/date";
        } else {
            window.location.href = "/tickets/status";

        }
    }

</script>
@endsection
