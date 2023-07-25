@extends('layouts.app')
@section('title', 'Home | Send Announcement')
@section('content')

<p class="mainText">Send Announcement</p>

<div class="infoPanel annPanel">

    @if($message != null)
    <div style="color:green;margin:0px 0px;">{{$message}}</div>
    @endif
    {{-- <p class="descriptionTXT">
        Description
    </p> --}}
    <form action="/announcement/send" method="post">
        @csrf
        @if(Session::get('role') == 'a') <p class="mailTitle annTitle">Title</p>@endif

        @if(Session::get('role') == 'a') <input type="text" id="annTitleTXT" name="title" value='' placeholder="Announcement Title" required>@endif

        @if(Session::get('role') == 'a')

        <p class="mailTitle annTitle">Description</p>
        @else

        <p class="mailTitle annTitle">Announcement</p>
        @endif

        @if(Session::get('role') == 'a')

        <textarea name="message" id="announcement" required style=" height: 310px; "></textarea>
        @else
        <textarea name="message" id="announcement" required style=" height: 380px; " readonly>{{$announcement}}</textarea>



        @endif


        @if(Session::get('role') == 'a') <button type="submit" class="postAnnoun">Post Announcement</button>@endif


    </form>

</div>
@endsection
