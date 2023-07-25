@extends('layouts.app')
@section('title', 'Submit Ticket | Admin Panel')
@section('content')
@section('maintitle','View User Profile')

<div class="userPanel ticketPanel ">

    <form class="submitTicket" action="/submitTicket" method="post" enctype="multipart/form-data">

        @csrf
        <p class="mailTitle">TICKET TYPE</p>

        <select name="typeSelect" id="TicketType">

            <option value="street1">street1</option>
            <option value="street2">street2</option>
            <option value="street3">street3</option>

            <option value="other">Other</option>
        </select>

        <input type="text" id="mail" name="type" value="" placeholder="Broken Street Sidewalk" style="display: none">


        <p class="mailTitle">DESCRIPTION(OPTIONAL*)</p>
        <input type="text" id="password" name="description" value="" placeholder="Description">


        <p class="mailTitle">ADDRESS</p>
        <input type="text" id="password" class='address' name="address" value="" placeholder="Ibn Aljazi Street next to the mosque" required>

        <img src="{{ asset('images/loader.gif')}}" class="addressLoader" alt="" style="display:none"> <label for="" class="adressLoaderTXT" style="display:none">Please Wait</label>
        <label for="" class="adressLoaderTXTResult" style="display:none">Sorry this ticket submit before</label>






        <div class="mediaContainer">
            <div class="photoContainer">
                <p class="photoTXT">PHOTO</p>
                <input type="file" name="image" id="image" style="display: none" accept="image/*" onchange="readURL(this);">
                <img src="{{Session::get('profileImage')}}" alt="" class="ticketSP" style="display: none">
                <label for="image" class="chooseMedia"><i class="medium material-icons menuIIcon">add_box</i></label>

            </div>
            <div class="photoContainer" style="margin-right: 0px;">

                <p class="photoTXT">VIDEO</p>
                <input type="file" name="video" id="video" style="display: none" accept="video/mp4,video/x-m4v,video/*" onchange="readVideoURL(this);">


                <video width="150" height="100" controls class="videoSRC" style="display: none">


                    <source src="movie.mp4" type="video/mp4">
                    <source src="movie.ogg" type="video/ogg">
                    Your browser does not support the video tag.
                </video>


                <label for="video" class="chooseMedia"><i class="medium material-icons menuIIcon">add_box</i></label>




            </div>
        </div>

        {{-- <button class="btn-login-submit" type="submit" value="login">Login</button> --}}
        <div class="formBtn">
            <button type="submit" class="saveSTBtn">Submit</button>

        </div>
    </form>



</div>
@endsection
@section('js')
<link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>

<script>
    $("#TicketType").change(function() {

        if ($(this).val() == 'other') {
            $("#mail").css({
                'display': 'block'
                , 'margin-top': '20px'

            });
            $("#mail").prop('required', true);



        } else {
            $("#mail").css({
                'display': 'none'
            });

            $("#mail").prop('required', false);


        }

    });
    //setup before functions
    var typingTimer; //timer identifier
    var doneTypingInterval = 1000; //time in ms, 5 seconds for example
    var $input = $('.address');


    //on keyup, start the countdown
    $input.on('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
        $('.addressLoader').css({
            'display': 'inline-block'
        });
        $('.adressLoaderTXT').css({
            'display': 'inline-block'
        });

        $('.adressLoaderTXTResult').css({
            'display': 'none'
        });

    });

    //on keydown, clear the countdown
    $input.on('keydown', function() {
        clearTimeout(typingTimer);


    });

    //user is "finished typing," do something
    function doneTyping() {


        var inputText = $('.address').val();

        $.ajax({
            type: "POST"
            , url: "/api/checkAddress"
            , dataType: "html"


            , data: {
                "text": inputText

            }

            , success: function(result) {
                $('.addressLoader').css({
                    'display': 'none'
                });
                $('.adressLoaderTXT').css({
                    'display': 'none'
                });

                if (result == 'true') {
                    console.log('bsm allah');
                    $('.adressLoaderTXTResult').css({
                        'display': 'block'
                    });
                    $('.saveSTBtn').css({

                        'display': 'none'
                    });


                } else {
                    $('.adressLoaderTXTResult').css({
                        'display': 'none'
                    });
                    $('.saveSTBtn').css({

                        'display': 'block'

                    });


                }
                console.log(result);
            }
            , error: function(error) {

                console.log(error);
            }
        });


    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('.ticketSP').attr('src', e.target.result);
                $('.ticketSP').css("display", "block");
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readVideoURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('.videoSRC').attr('src', e.target.result);
                $('.videoSRC').css("display", "block");
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>
@endsection
