@extends('layouts.app')
@section('title', 'View Profile | Admin Panel')
@section('content')
@section('maintitle','View User Profile')

<div class="userSearchContainer">

</div>
<div class="userPanel supportPanel ">

    <usupport userid="{{Session::get('userID')}}"></usupport>



</div>

</div>
@endsection
@section('js')

<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>




@endsection
