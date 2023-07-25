@extends('layouts.app')
@section('title', 'Home | Send Announcement')
@section('content')

<p class="mainText">Send Announcement</p>

<div class="infoPanel annPanel">
    @foreach ($announcement as $annou)


    <div class="announContainer">
        <p class="annouTitle">{{$annou['title']}}</p>

        <p class="annouSubject">{{$annou['description']}}</p>
        <p class="annouDate">{{$annou['date']}}</p>


    </div>
    @endforeach

</div>


@endsection
