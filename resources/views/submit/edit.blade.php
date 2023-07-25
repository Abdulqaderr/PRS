@extends('layouts.app')
@section('title', 'View Profile | Admin Panel')
@section('content')
@section('maintitle','View User Profile')

<div class="userPanel ticketPanel">
    <div class="userTicketContainer">
        <div class="userTicket">
            <img src="{{$ticket[9]}}" alt="" class="userTicketIMG">
            <div class="userTicketInfo">
                <p class="userTicketName">{{$ticket[5]}}</p>
                <a href="/users/resident/{{$ticket[8]}}" class="editProf viewProfile">View Profile</a>
            </div>
        </div>
    </div>
    <div class="ticketLS">
        <p class="UNTXT">Ticket Type</p>
        <p class="UName">{{$ticket[4]}}</p>
        <p class="UNTXT">Description</p>
        <p class="UName">{{$ticket[1]}}</p>

        <p class="UNTXT">Phone</p>
        <p class="UName">{{$ticket[6]}}</p>

        <p class="UNTXT">Address</p>
        <p class="UName">{{$ticket[7]}}</p>

        <p class="UNTXT">Ticket Status</p>
        <form action="/updateTicketStatus" method="POST" id="formStatusForm">
            @csrf
            <input type="text" name="ticketID" value="{{Request::segment(4)}}" class="ticketIDInput">
            <input type="text" name="ticketKind" value="{{Request::segment(2)}}" class="ticketIDInput">
            <select id="tickets" name="status">
                <option value="0" @if( $ticket[3]==0) selected @endif>Pending Ticket</option>
                <option value="1" @if( $ticket[3]==1 ) selected @endif>Approved Ticket</option>
                <option value="2" @if( $ticket[3]==2 ) selected @endif> Complete Ticket</option>
                <option value="3" @if( $ticket[3]==3 ) selected @endif> Rejected Ticket</option>
                <option value="4" @if( $ticket[3]==4 ) selected @endif> Canceled Ticket</option>
            </select>

        </form>
        {{--
        <p class="UAddress">
            @if($ticket[3] == 0)
            Submitted Ticket
            @elseif($ticket[3] == 1)
            Under Reviewing Ticket
            @elseif($ticket[3] == 2)
            Pending Ticket
            @elseif($ticket[3] == 3)
            Confirmed Ticket
            @elseif($ticket[3] == 4)

            Rejected Ticket
            @endif
        </p> --}}

        <p class="UNTXT">Date Submitted</p>
        <p class="UName">{{date('d-m-Y', strtotime($ticket[0]))}}</p>

        <p class="mainTXT">
            Review (If Completed)
        </p>
        <p class="detailTXT">
            @if($ticket[2] > 1)
            <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport">
            @else
            <img src="{{ asset('icons/star.png')}}" alt="" class="starReport">
            @endif
            @if($ticket[2] > 2)

            <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport">
            @else
            <img src="{{ asset('icons/star.png')}}" alt="" class="starReport">
            @endif
            @if($ticket[2] > 3)

            <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport">
            @else
            <img src="{{ asset('icons/star.png')}}" alt="" class="starReport">
            @endif
            @if($ticket[2] > 4)

            <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport">
            @else
            <img src="{{ asset('icons/star.png')}}" alt="" class="starReport">
            @endif
            @if($ticket[2] > 5)

            <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport">
            @else
            <img src="{{ asset('icons/star.png')}}" alt="" class="starReport">
            @endif
        </p>

        <span class="editProf updateTicketBTN" onclick="submitFunction()">Save Ticket Status</span>

    </div>
    <div class="ticketRS">
        <div class="pictureTicketContainer">
            <p class="UNTXT">Picture</p>
            @if(count($ticket[10]) != 0)


            @foreach ($ticket[10] as $picture)
            <img src="{{ $picture['image']}}" alt="" class="pictureTicket">

            @endforeach
            @else
            <p class="emptyContainer">There are no pictures for this ticket</p>
            @endif

        </div>
        <div class="videoTicketContainer">
            <p class="UNTXT">Video</p>
            @if($ticket[11] != null)
            <video width="320" height="240" controls>
                <source src="{{$ticket[11]}}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            @else
            <p class="emptyContainer">There are no video for this ticket</p>
            @endif


        </div>
    </div>


</div>
@endsection
@section('js')
<script>
    function submitFunction() {
        document.getElementById("formStatusForm").submit();
    }

</script>
@endsection
