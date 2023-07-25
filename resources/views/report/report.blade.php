@extends('layouts.app')
@section('title', 'View Weekly Reports | Admin Panel')
@section('content')

<p class="mainText reportMainTXT">View Weekly Reports</p>

<div class="infoPanel reportPanel">
    @if($errors->any())
    {!! implode('', $errors->all('<div style="color:red;margin:0px 0px 10px;">:message</div>')) !!}
    @endif
    <form action="/report/interval">
        <span class="fromTXT">From: </span>
        <input type="date" name="from" id="reportFrom" @if($data[5] !='' ) value='{{$data[5]}}' @endif>
        <span class="toTXT">to: </span>
        <input type="date" name="to" id="reportTo" @if($data[6] !='' ) value='{{$data[6]}}' @endif>
        <button type="submit" class="ReportBTN">Show</button>
    </form>
    <div class="reportDetailContainer">
        <p class="mainTXT">
            Date
        </p>
        <p class="detailTXT ">
            <span id="dateFrom">@if($data[5] !='' ) {{$data[5]}} @else 0000-00-00 @endif </span> - <span id="dateTo">@if($data[6] !='' ) {{$data[6]}} @else 0000-00-00 @endif</span>
        </p>
        <p class="mainTXT">
            Pending Tickets

        </p>
        <p class="detailTXT">
            {{$data[0]}}
        </p>
        <p class="mainTXT">
            Approved Tickets
        </p>
        <p class="detailTXT">
            {{$data[1]}}
        </p>
        <p class="mainTXT">
            Completed Tickets
        </p>
        <p class="detailTXT">
            {{$data[2]}}
        </p>
        <p class="mainTXT">
            Rejected Tickets
        </p>
        <p class="detailTXT">
            {{$data[3]}}
        </p>
        <p class="mainTXT">
            Canceled Tickets
        </p>
        <p class="detailTXT">
            {{$data[8]}}
        </p>
        <p class="mainTXT">
            Average Reviewing
        </p>
        <p class="detailTXT">
            @if($data[7] >= 1)
            <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport">
            @else
            <img src="{{ asset('icons/star.png')}}" alt="" class="starReport">
            @endif
            @if($data[7] >= 2)
            <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport">
            @else
            <img src="{{ asset('icons/star.png')}}" alt="" class="starReport">
            @endif
            @if($data[7] >= 3)
            <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport">
            @else
            <img src="{{ asset('icons/star.png')}}" alt="" class="starReport">
            @endif
            @if($data[7] >= 4)
            <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport">
            @else
            <img src="{{ asset('icons/star.png')}}" alt="" class="starReport">
            @endif
            @if($data[7] >= 5)
            <img src="{{ asset('icons/ystar.png')}}" alt="" class="starReport">
            @else
            <img src="{{ asset('icons/star.png')}}" alt="" class="starReport">
            @endif


        </p>

    </div>
</div>
@endsection
@section('js')
{{-- <script>
    document.getElementById("reportFrom").addEventListener("change", function() {
        var input = this.value;
        document.getElementById('dateFrom').innerText = input;
    });
    document.getElementById("reportTo").addEventListener("change", function() {
        var input = this.value;
        document.getElementById('dateTo').innerText = input;
    });

</script> --}}
@endsection
