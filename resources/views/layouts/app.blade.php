<!doctype html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title')</title>
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @yield('header')
</head>
<body>
    <div id="app">

        <nav>
            <div class="logoContainer"><img src="{{ asset('images/logo.png')}}" alt="" class="logo"></div>
            {{-- <div class="searchContainer">
            <div class="searchRelative">
                <input type="text" name="search" id="mainSearch" placeholder="Search...">
                <img src="{{ asset('icons/search.png')}}" alt="" class="searchIcon">
    </div>
    </div> --}}
    <div class="profileInfoContainer">
        <img src="{{Session::get('profileImage')}}" alt="" class="navPUser">
        <p class="navUName">{{Session::get('fullname')}}</p>
        <a href="/logout"> <img src="{{ asset('icons/logout.png')}}" alt="" class="logout"></a>

    </div>
    </nav>
    <div class="content">
        <div class="sidebar">
            <div class="userInfo">
                <div class="imgContainer"><img src="{{Session::get('profileImage')}}" alt="user image" class="userIMG"></div>

                <div class="userDetail">
                    <p class="userName">{{Session::get('fullname')}}</p>

                    <p class="userRole">@if(Session::get('role') == 'a')Admin @else user @endif</p>

                </div>
            </div>
            <div class="menuItem @if( Request::segment(1) == null )active @endif">
                <a href="/" class="menuILink">Home Page</a>
                <div class="menuIconCont"> <i class="medium material-icons menuIIcon">home</i></div>
            </div>
            <div class="menuItem @if( Request::segment(1) == 'profile' )active @endif">
                <a href="/profile" class="menuILink">View Profile</a>
                <div class="menuIconCont"> <i class="medium material-icons menuIIcon">person_pin</i></div>
            </div>
            @if(Session::get('role') == 'a')

            <div class="menuItem @if( Request::segment(1) == 'users' )active @endif">
                <a href="/users/resident" class="menuILink">View Users Profile</a>
                <div class="menuIconCont"> <i class="medium material-icons menuIIcon">person_outline</i></div>
            </div>
            @endif
            @if(Session::get('role') == 'a')

            <div class="menuItem @if( Request::segment(1) == 'report' )active @endif">

                <a href="/report" class="menuILink ">View Statistics Report</a>
                <div class="menuIconCont"> <i class="medium material-icons menuIIcon">insert_chart</i></div>
            </div>
            @endif

            {{-- @if(Session::get('role') == 'a')

            <div class="menuItem  @if( Request::segment(1) == 'register' )active @endif">
                <a href="/register/staff" class="menuILink">Register New Staff</a>
                <div class="menuIconCont"> <i class="medium material-icons menuIIcon">person_add</i></div>
            </div>
            @endif --}}
            @if(Session::get('role') == 'u')

            <div class="menuItem  @if( Request::segment(1) == 'submit' )active @endif">
                <a href="/submit/ticket" class="menuILink">Submit Tickets</a>


                <div class="menuIconCont"> <i class="medium material-icons menuIIcon">book_online</i></div>
            </div>
            @endif



            <div class="menuItem  @if( Request::segment(1) == 'tickets' )active @endif">
                <a href="/tickets/date" class="menuILink">View Tickets @if(Session::get('role') == 'u') Details @endif</a>




                <div class="menuIconCont"> <i class="medium material-icons menuIIcon">chrome_reader_mode</i></div>
            </div>




            @if(Session::get('role') == 'a')

            <div class="menuItem  @if( Request::segment(1) == 'customer' )active @endif">
                <a href="/support" class="menuILink">Customer Services</a>

                <label class="redDotSupport MenuDotSupport" style="width:12px;height:12px;display:inline-block;float: right;"></label>


                <div class="menuIconCont"> <i class="medium material-icons menuIIcon">support_agent</i></div>
            </div>
            @endif

            @if(Session::get('role') == 'u')

            <div class="menuItem  @if( Request::segment(1) == 'customer' )active @endif">
                <a href="/support/user" class="menuILink">Customer Services</a>
                <label class="redDotSupport MenuDotSupportU" style="width:12px;height:12px;display:inline-block;float: right;"></label>



                <div class="menuIconCont"> <i class="medium material-icons menuIIcon">support_agent</i></div>
            </div>
            @endif

            @if(Session::get('role') == 'a')

            <div class="menuItem  @if( Request::segment(1) == 'announcement' )active @endif">
                <a href="/announcement" class="menuILink">Send Announcement</a>


                <div class="menuIconCont"> <i class="medium material-icons menuIIcon">record_voice_over</i></div>
            </div>
            @endif
            @if(Session::get('role') == 'u')

            <div class="menuItem  @if( Request::segment(1) == 'announcement' )active @endif">
                <a href="/user/announcement" class="menuILink">View Announcements</a>

                <div class="menuIconCont"> <i class="medium material-icons menuIIcon">record_voice_over</i></div>
            </div>
            @endif
            @if(Session::get('role') == 'u')

            <div class="menuItem  @if( Request::segment(1) == 'all' )active @endif">

                <a href="/all/tickets" class="menuILink">History</a>



                <div class="menuIconCont"> <i class="medium material-icons menuIIcon">chrome_reader_mode</i></div>
            </div>
            @endif




        </div>


        <div class="contentContainer">

            @yield('content')
        </div>
    </div>
    </div>

    <script src="/jquery/jquery.min.js"></script>


    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-firestore.js"></script>
    @if(Session::get('role') == 'a')

    <Script>
        var items = window.location.pathname.split("/");

        $(document).ready(() => {
            var firstTime = true;

            if (items[1] !== 'support') {

                const firebaseConfig = {
                    apiKey: "AIzaSyDKHWpHcTE56fGAt1Tz808zwqzDVh8IEck"
                    , authDomain: "prss-2586c.firebaseapp.com"
                    , projectId: "prss-2586c"
                    , storageBucket: "prss-2586c.appspot.com"
                    , messagingSenderId: "436957358320"
                    , appId: "1:436957358320:web:684422e815acde42ac3a35"
                    , measurementId: "G-0N9TWKTNVB"
                };

                // Initialize Firebase
                firebase.initializeApp(firebaseConfig);

            }
            let db1 = firebase.firestore();
            db1.collection('messageList').where('rRead', '==', 1).onSnapshot((querySnapshot) => {
                var MessageLists = [];
                querySnapshot.forEach(doc => {
                    MessageLists.push(doc.data());
                });

                if (MessageLists.length > 0) {
                    $(".MenuDotSupport").css('display', 'block')
                    if (!firstTime) {
                        const audio = new Audio(
                            "/tone/tone.mp3"
                        );
                        audio.play();


                        // var snd = new Audio("/tone/tone.mp3");
                        // snd.play();
                    }

                } else {
                    $(".MenuDotSupport").css('display', 'none')

                }

                firstTime = false



            })
        });

    </Script>
    @else
    <Script>
        var items = window.location.pathname.split("/");

        $(document).ready(() => {
            var firstTime = true;

            if (items[1] !== 'support') {

                const firebaseConfig = {
                    apiKey: "AIzaSyDKHWpHcTE56fGAt1Tz808zwqzDVh8IEck"
                    , authDomain: "prss-2586c.firebaseapp.com"
                    , projectId: "prss-2586c"
                    , storageBucket: "prss-2586c.appspot.com"
                    , messagingSenderId: "436957358320"
                    , appId: "1:436957358320:web:684422e815acde42ac3a35"
                    , measurementId: "G-0N9TWKTNVB"
                };

                // Initialize Firebase
                firebase.initializeApp(firebaseConfig);

            }
            let db1 = firebase.firestore();
            var userid = "{{ Session::get('userID')}}";


            console.log(userid);


            db1.collection('messageList').where('sender', '==', userid).where('sRead', '==', 1).onSnapshot((querySnapshot) => {


                var MessageLists = [];
                querySnapshot.forEach(doc => {
                    MessageLists.push(doc.data());
                });

                if (MessageLists.length > 0) {
                    $(".MenuDotSupportU").css('display', 'block')

                    if (!firstTime) {
                        const audio = new Audio(
                            "/tone/tone.mp3"
                        );
                        audio.play();


                        // var snd = new Audio("/tone/tone.mp3");
                        // snd.play();
                    }

                } else {
                    $(".MenuDotSupportU").css('display', 'none')


                }

                firstTime = false



            })
        });

    </Script>


    @endif


    @yield('js')

</body>
</html>
