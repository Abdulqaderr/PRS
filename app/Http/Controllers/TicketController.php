<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Redirect;


class TicketController extends Controller
{
    public function updateTicket(Request $request)
    {
        $idToken = Session::get('idToken');
        if ($idToken != null) {

            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            $ticketRef = $database->collection('ticket')->document(
                $request['ticketID']
            );
            $ticketRef->update([
                ['path' => 'status', 'value' => $request['status']],

            ]);
            return redirect('/tickets/' . $request['ticketKind'] . '/show/' . $request['ticketID']);
        } else {
            return redirect('/login');
        }
    }
    public function editTicket(Request $request)
    {
        $idToken = Session::get('idToken');
        if ($idToken != null) {
            $paths = explode('/', url()->current());
            $pictures = [];
            $videoURL = '';

            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            //here to get ticket info
            $ticketRef = $database->collection('ticket')->document(
                $paths[6]
            )->snapshot();

            //here to get ticket pictures 
            $ticketPRef = $database->collection('picture');
            $query = $ticketPRef->where('ticketID', '=', $paths[6]);
            $snapshot = $query->documents();
            foreach ($snapshot as $picture) {
                array_push($pictures, [
                    'image' => $picture['pictureURL'],
                ]);
            }

            //here to get ticket video
            $ticketPRef = $database->collection('video');
            $query = $ticketPRef->where('ticketID', '=', $paths[6]);
            $snapshot = $query->documents();
            foreach ($snapshot as $video) {
                $videoURL = $video['videoURL'];
            }

            $userRef
                = $database->collection('user')->document(
                    $ticketRef['userID']
                )->snapshot();

            return view('tickets.edit')->with('ticket', [
                $ticketRef->data()['dateTime'], $ticketRef->data()['description'],
                $ticketRef->data()['rate'],  $ticketRef->data()['status'],
                $ticketRef->data()['typeOfTicket'],
                $userRef->data()['fullname'],
                $userRef->data()['phonenumber'],
                $userRef->data()['address'],
                $userRef->id(),
                $userRef->data()['profilePictureURL'],
                $pictures,
                $videoURL
            ]);
        } else {
            return redirect('/login');
        }
    }
    public function showTicket(Request $request)
    {
        $idToken = Session::get('idToken');
        if ($idToken != null) {

            $paths = explode('/', url()->current());
            $pictures = [];
            $videoURL = '';

            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            //here to get ticket info
            $ticketRef = $database->collection('ticket')->document(
                $paths[6]
            )->snapshot();

            //here to get ticket pictures 
            $ticketPRef = $database->collection('picture');
            $query = $ticketPRef->where('ticketID', '=', $paths[6]);
            $snapshot = $query->documents();
            foreach ($snapshot as $picture) {
                array_push($pictures, [
                    'image' => $picture['pictureURL'],
                ]);
            }
            //here to get ticket video
            $ticketPRef = $database->collection('video');
            $query = $ticketPRef->where('ticketID', '=', $paths[6]);
            $snapshot = $query->documents();
            foreach ($snapshot as $video) {
                $videoURL = $video['videoURL'];
            }

            $userRef

                = $database->collection('user')->document(
                    $ticketRef['userID']
                )->snapshot();


            return view('tickets.viewTicket')->with('ticket', [
                $ticketRef->data()['dateTime'], $ticketRef->data()['description'],
                $ticketRef->data()['rate'],  $ticketRef->data()['status'],
                $ticketRef->data()['typeOfTicket'],
                $userRef->data()['fullname'],
                $userRef->data()['phonenumber'],
                $userRef->data()['address'],
                $userRef->id(),
                $userRef->data()['profilePictureURL'],
                $ticketRef->data()['attachmentImages'],
                $ticketRef->data()['attachmentvideo'],
                $ticketRef->data()['remark'],
                $ticketRef->data()['feedback'],
            ]);
        } else {
            return redirect('/login');
        }
    }

    public function showUsersTickets(Request $request)
    {
        $idToken = Session::get('idToken');

        if ($idToken != null) {
            $userRole = Session::get('role');
            $userID = Session::get('userID');
            $tickets = [];
            $paths = explode('/', url()->current());
            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();

            $ticketRef = $database->collection('ticket')->orderBy('dateTime', 'desc');
            $snapshot = $ticketRef->documents();
            foreach ($snapshot as $ticket) {

                $userRef
                    = $database->collection('user')->document(
                        $ticket['userID']
                    )->snapshot();
                array_push(
                    $tickets,
                    [
                        'ticketID' => $ticket->id(),
                        'dateTime' => $ticket['dateTime'],
                        'description' => $ticket['description'],
                        'rate'  => $ticket['rate'],
                        'status'  => $ticket['status'],
                        'typeOfTicket'  => $ticket['typeOfTicket'],
                        'userImage'  =>  $userRef->data()['profilePictureURL'],
                        'userName' =>  $userRef->data()['fullname'],
                        'address' => $userRef->data()['address'],
                        'phone' => $userRef->data()['phonenumber'],
                    ]
                );
            }
            return view('tickets.tickets')->with('tickets', $tickets);
        } else {
            return redirect('/login');
        }
    }
    public function SearchTickets(Request $request)
    {
        $idToken = Session::get('idToken');
        if ($idToken != null) {

            $tickets = [];
            $paths = explode('/', url()->current());
            if ($paths[4] == 'date') {
                $factory = (new Factory)
                    ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
                $fireStore  = $factory->createFirestore();
                $database = $fireStore->database();
                $ticketRef = $database->collection('ticket')->orderBy('dateTime', 'asc');
                $snapshot = $ticketRef->documents();
                foreach ($snapshot as $ticket) {
                    if (Str::contains($ticket['typeOfTicket'], $request['search'])) {
                        $userRef
                            = $database->collection('user')->document(
                                $ticket['userID']
                            )->snapshot();
                        array_push(
                            $tickets,
                            [
                                'ticketID' => $ticket->id(),
                                'dateTime' => $ticket['dateTime'],
                                'description' => $ticket['description'],
                                'rate'  => $ticket['rate'],
                                'status'  => $ticket['status'],
                                'typeOfTicket'  => $ticket['typeOfTicket'],
                                'userImage'  =>  $userRef->data()['profilePictureURL'],
                                'userName' =>  $userRef->data()['fullname'],
                                'address' => $userRef->data()['address'],
                                'phone' => $userRef->data()['phonenumber'],
                            ]
                        );
                    }
                }
            } else {
                $factory = (new Factory)
                    ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
                $fireStore  = $factory->createFirestore();
                $database = $fireStore->database();
                $ticketRef = $database->collection('ticket')->orderBy('status', 'asc');
                $snapshot = $ticketRef->documents();

                foreach ($snapshot as $ticket) {

                    if (Str::contains($ticket['typeOfTicket'], $request['search'])) {
                        $userRef
                            = $database->collection('user')->document(
                                $ticket['userID']
                            )->snapshot();
                        array_push(
                            $tickets,
                            [
                                'ticketID' => $ticket->id(),
                                'dateTime' => $ticket['dateTime'],
                                'description' => $ticket['description'],
                                'rate'  => $ticket['rate'],
                                'status'  => $ticket['status'],
                                'typeOfTicket'  => $ticket['typeOfTicket'],
                                'userImage'  =>  $userRef->data()['profilePictureURL'],
                                'userName' =>  $userRef->data()['fullname'],
                                'address' => $userRef->data()['address'],
                                'phone' => $userRef->data()['phonenumber'],
                            ]
                        );
                    }
                }
            }
            return view('tickets.tickets')->with('tickets', $tickets);
        } else {
            return redirect('/login');
        }
    }
    public function showTickets()
    {
        $idToken = Session::get('idToken');

        if ($idToken != null) {
            $userRole = Session::get('role');
            $userID = Session::get('userID');
            $tickets = [];
            $paths = explode('/', url()->current());
            if ($paths[4] == 'date') {
                $factory = (new Factory)
                    ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
                $fireStore  = $factory->createFirestore();
                $database = $fireStore->database();
                if ($userRole == 'u') {
                    $ticketRef = $database->collection('ticket')->where('userID', '=',    $userID)->orderBy('dateTime', 'desc');
                    $snapshot = $ticketRef->documents();
                    foreach ($snapshot as $ticket) {

                        $userRef
                            = $database->collection('user')->document(
                                $ticket['userID']
                            )->snapshot();
                        array_push(
                            $tickets,
                            [
                                'ticketID' => $ticket->id(),
                                'dateTime' => $ticket['dateTime'],
                                'description' => $ticket['description'],
                                'rate'  => $ticket['rate'],
                                'status'  => $ticket['status'],
                                'typeOfTicket'  => $ticket['typeOfTicket'],
                                'userImage'  =>  $userRef->data()['profilePictureURL'],
                                'userName' =>  $userRef->data()['fullname'],
                                'address' => $userRef->data()['address'],
                                'phone' => $userRef->data()['phonenumber'],
                            ]
                        );
                    }
                } else {
                    $ticketRef = $database->collection('ticket')->orderBy('dateTime', 'desc');
                    $snapshot = $ticketRef->documents();
                    foreach ($snapshot as $ticket) {

                        $userRef
                            = $database->collection('user')->document(
                                $ticket['userID']
                            )->snapshot();
                        array_push(
                            $tickets,
                            [
                                'ticketID' => $ticket->id(),
                                'dateTime' => $ticket['dateTime'],
                                'description' => $ticket['description'],
                                'rate'  => $ticket['rate'],
                                'status'  => $ticket['status'],
                                'typeOfTicket'  => $ticket['typeOfTicket'],
                                'userImage'  =>  $userRef->data()['profilePictureURL'],
                                'userName' =>  $userRef->data()['fullname'],
                                'address' => $userRef->data()['address'],
                                'phone' => $userRef->data()['phonenumber'],
                            ]
                        );
                    }
                }
            } else {
                $factory = (new Factory)
                    ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
                $fireStore  = $factory->createFirestore();
                $database = $fireStore->database();
                $ticketRef = $database->collection('ticket')->orderBy('status', 'desc');

                $snapshot = $ticketRef->documents();
                foreach ($snapshot as $ticket) {

                    $userRef
                        = $database->collection('user')->document(
                            $ticket['userID']
                        )->snapshot();
                    array_push(
                        $tickets,
                        [
                            'ticketID' => $ticket->id(),
                            'dateTime' => $ticket['dateTime'],
                            'description' => $ticket['description'],
                            'rate'  => $ticket['rate'],
                            'status'  => $ticket['status'],
                            'typeOfTicket'  => $ticket['typeOfTicket'],
                            'userImage'  =>  $userRef->data()['profilePictureURL'],
                            'userName' =>  $userRef->data()['fullname'],
                            'address' => $userRef->data()['address'],
                            'phone' => $userRef->data()['phonenumber'],
                        ]
                    );
                }
            }
            return view('tickets.tickets')->with('tickets', $tickets);
        } else {
            return redirect('/login');
        }
    }
    public function showSubmitTicket()
    {
        return view('submit.viewTicket');
    }
    public function submitTicket(Request $request)
    {
        // return $request['typeSelect'] == 'other' ? $request['type'] : $request['typeSelect'];
        $imagePath = "";
        $videoPath = "";

        if ($request['image']) {
            $image = $request['image']; //image file from frontend  

            $firebase_storage_path = 'tickets/';
            $name     = time(); //unique name for image 
            $localfolder = public_path('admin') . '/';
            $extension = $image->getClientOriginalExtension();
            $file      = $name . '.' . $extension;

            if ($image->move($localfolder, $file)) {
                $uploadedfile = fopen($localfolder . $file, 'r');

                $factory = (new Factory)
                    ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');

                $path = $factory->createStorage()->getBucket()->upload($uploadedfile, [
                    'name' => $firebase_storage_path . $file,
                    'predefinedAcl' => 'PUBLICREAD',
                ]);

                //will remove from local laravel folder  
                unlink($localfolder . $file);

                $expiresAt = new \DateTime('01/01/2030');

                $imagePath = $path->signedUrl($expiresAt);
            }
        }
        if ($request['video']) {
            $image = $request['video']; //image file from frontend  

            $firebase_storage_path = 'tickets/';
            $name     = time(); //unique name for image 
            $localfolder = public_path('admin') . '/';
            $extension = $image->getClientOriginalExtension();
            $file      = $name . '.' . $extension;

            if ($image->move($localfolder, $file)) {
                $uploadedfile = fopen($localfolder . $file, 'r');

                $factory = (new Factory)
                    ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');

                $path = $factory->createStorage()->getBucket()->upload($uploadedfile, [
                    'name' => $firebase_storage_path . $file,
                    'predefinedAcl' => 'PUBLICREAD',
                ]);

                //will remove from local laravel folder  
                unlink($localfolder . $file);

                $expiresAt = new \DateTime('01/01/2030');

                $videoPath = $path->signedUrl($expiresAt);
            }
        }
        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
        $fireStore  = $factory->createFirestore();
        $database = $fireStore->database();
        $userRef = $database->collection('ticket');
        $userRef->add([
            'attachmentImages' => [$imagePath],
            'attachmentvideo' => $videoPath,
            'dateTime' => Carbon::now()->toDateTimeString(),
            'typeOfTicket' => $request['type'] == 'other' ? $request['type'] : $request['typeSelect'],
            'description' => $request['description'],
            'location' => $request['address'],
            'userID' => Session::get('userID'),
            'userName' => Session::get('fullname'),
            'rate' => 0,
            'status' => 0,
            'remark' => '',
            'feedback' => '',

        ]);
        return Redirect::back();
    }
    public function updateRate($rate, $id)
    {
        $idToken = Session::get('idToken');
        if ($idToken != null) {

            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            $ticketRef = $database->collection('ticket')->document(
                $id
            );
            $ticketRef->update([
                ['path' => 'rate', 'value' => $rate],

            ]);
            return Redirect::back();
        } else {
            return redirect('/login');
        }
    }
    public function checkAddress(Request $request)
    {
        try {

            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            $ticketRef = $database->collection('ticket')->where('location', '=', $request->text);
            $snapshot = $ticketRef->documents();
            foreach ($snapshot as $ticket) {
                if ($ticket['location'] == $request->text) {
                    return 'true';
                }
            }
            return 'false';
        }

        //catch exception
        catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}