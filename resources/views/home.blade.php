@extends('layouts.app')
@section('title', 'Home | Admin Panel')
@section('content')



<p class="mainText">Home Page</p>

<div class="infoPanel homePanel">
    <a href="/profile">
        <div class="hCard">
            <img src="{{ asset('images/viewprofile.png')}}" alt="" class="cardImg">
            <p class="cardTxt">View Profile</p>
        </div>
    </a>
    @if(Session::get('role') == 'u')

    <a href="/user/announcement">
        <div class="hCard">
            <img src="{{ asset('images/ticket.png')}}" alt="" class="cardImg">
            <p class="cardTxt">Submit Tickets</p>
        </div>
    </a>
    @endif

    @if(Session::get('role') == 'a')

    <a href="/users/resident">
        <div class="hCard">
            <img src="{{ asset('images/userprofile.png')}}" alt="" class="cardImg">
            <p class="cardTxt">View Users Profile</p>
        </div>
    </a>
    @endif

    <div class="clear"></div>
    @if(Session::get('role') == 'a')

    <a href="/report">

        <div class="hCard">
            <img src="{{ asset('images/statistics.png')}}" alt="" class="cardImg">
            <p class="cardTxt">View Statistics Report</p>
        </div>
    </a>
    @endif
    @if(Session::get('role') == 'u')

    <a href="/tickets/date">



        <div class="hCard">
            <img src="{{ asset('images/sTickets.png')}}" alt="" class="cardImg">

            <p class="cardTxt">View Tickets Details</p>

        </div>
    </a>
    @endif
    @if(Session::get('role') == 'a')

    <a href="/tickets/date">
        <div class="hCard">
            <img src="{{ asset('images/tickets.png')}}" alt="" class="cardImg">
            <p class="cardTxt">View Tickets </p>

        </div>
    </a>
    @endif


    @if(Session::get('role') == 'u')

    <a href="/all/tickets">

        <div class="hCard">
            <img src="{{ asset('images/tickets.png')}}" alt="" class="cardImg">
            <p class="cardTxt">History</p>

        </div>
    </a>
    @endif

    @if(Session::get('role') == 'u')

    <a href="/user/announcement">
        <div class="hCard">
            <img src="{{ asset('images/announcement.png')}}" alt="" class="cardImg">
            <p class="cardTxt">View Announcement</p>
        </div>
    </a>
    @endif

    @if(Session::get('role') == 'a')

    <a href="/announcement">

        <div class="hCard">
            <img src="{{ asset('images/announcement.png')}}" alt="" class="cardImg">
            <p class="cardTxt">Send Announcement</p>
        </div>
    </a>
    @endif
    @if(Session::get('role') == 'a')

    <a href="/support">


        <div class="hCard">
            <img src="{{ asset('images/customer-service.png')}}" alt="" class="cardImg">

            <p class="cardTxt">Customer Services</p>

        </div>
    </a>
    @endif @if(Session::get('role') == 'u')

    <a href="/support/user">


        <div class="hCard">
            <img src="{{ asset('images/customer-service.png')}}" alt="" class="cardImg">

            <p class="cardTxt">Customer Services</p>

        </div>
    </a>
    @endif

</div>
@endsection
