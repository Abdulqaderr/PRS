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
        <p class="UAddress">
            @if($ticket[3] == 0)
            Pending Ticket
            @elseif($ticket[3] == 1)
            Approved Ticket
            @elseif($ticket[3] == 2)
            Complete Ticket
            @elseif($ticket[3] == 3)
            Rejected Ticket
            @elseif($ticket[3] == 4)
            Canceled Ticket
            @endif
        </p>

        <p class="UNTXT">Date Submitted</p>
        <p class="UName">{{date('d-m-Y', strtotime($ticket[0]))}}</p>

        <p class="mainTXT">
            Review (If Completed)
        </p>
        @if(Session::get('role') == 'a')

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
        @else
        @if($ticket[3] == 2)


        <p class="detailTXT">
            @if($ticket[2] > 1)
            <a href="/tickets/star/1/{{Request::segment(4)}}"> <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport"></a>


            @else
            <a href="/tickets/star/1/{{Request::segment(4)}}"> <img src="{{ asset('icons/star.png')}}" alt="" class="starReport"></a>



            @endif
            @if($ticket[2] >= 2)

            <a href="/tickets/star/2/{{Request::segment(4)}}"> <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport"></a>



            @else
            <a href="/tickets/star/2/{{Request::segment(4)}}"> <img src="{{ asset('icons/star.png')}}" alt="" class="starReport"></a>



            @endif
            @if($ticket[2] >= 3)

            <a href="/tickets/star/3/{{Request::segment(4)}}"> <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport"></a>


            @else
            <a href="/tickets/star/3/{{Request::segment(4)}}"> <img src="{{ asset('icons/star.png')}}" alt="" class="starReport"></a>


            @endif
            @if($ticket[2] >= 4)

            <a href="/tickets/star/4/{{Request::segment(4)}}"> <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport"></a>


            @else
            <a href="/tickets/star/4/{{Request::segment(4)}}"> <img src="{{ asset('icons/star.png')}}" alt="" class="starReport"></a>


            @endif
            @if($ticket[2] >= 5)

            <a href="/tickets/star/5/{{Request::segment(4)}}"> <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport"></a>


            @else
            <a href="/tickets/star/5/{{Request::segment(4)}}"> <img src="{{ asset('icons/star.png')}}" alt="" class="starReport"></a>


            @endif


        </p>
        @else

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



        @endif


        @endif

        @if($ticket[3] == 3 ||$ticket[3] == 4 )
        <p class="ticketMessage">
            @if($ticket[3] == 4) {{$ticket[12]}}@elseif($ticket[3] == 3 ) {{$ticket[13]}} @endif
        </p>
        @endif
        @if(Session::get('role') == 'a') <a href="/tickets/{{Request::segment(2)}}/edit/{{Request::segment(4)}}" class="editProf updateTicketBTN">Update Ticket Status</a>@endif



    </div>
    <div class="ticketRS">
        <div class="pictureTicketContainer">
            <p class="UNTXT">Picture</p>
            @if(count($ticket[10]) != 0)


            @foreach ($ticket[10] as $picture)
            <img src="{{ $picture}}" onclick='showImgPanel(this);' alt="" class="pictureTicket">


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
