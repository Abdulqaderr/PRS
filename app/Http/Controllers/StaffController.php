<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Kreait\Firebase\Factory;

class StaffController extends Controller
{
    protected $auth, $database;

    //this construct to initialize firebase info
    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');

        $this->auth = $factory->createAuth();
    }

    public function RegisterNewStaff(Request $request)
    {
        //if condition to check if all input insert correctly 
        if ($request['uname'] == null || $request['fname'] == null || $request['mail'] == null  || $request['phone'] == null  || $request['password'] == null) {
            // if in correctly the return with error and all data to make user continue register
            return view('users.registerStaff')->with('success', '')->with('error', "Insert all input correctly")->with('mail', $request['mail'])
                ->with('uname', $request['uname'])
                ->with('fname', $request['fname'])->with('phone', $request['phone'])->with('password', $request['password']);
        } else {
            //here will sign up new staff in firebase auth
            $email = $request['mail'];
            $pass = $request['password'];

            try {
                $newUser = $this->auth->createUserWithEmailAndPassword($email, $pass);

                $factory = (new Factory)
                    ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
                $fireStore  = $factory->createFirestore();
                $database = $fireStore->database();
                $userRef = $database->collection('user')->document($newUser->uid);
                $userRef->set([
                    'email' => $request['mail'],
                    'fullname' => $request['fname'],
                    'phonenumber' => $request['phone'],
                    'profilePictureURL' => $request['gender'] == 'm' ? 'https://firebasestorage.googleapis.com/v0/b/sars-e6e88.appspot.com/o/staff%2FdefaultPhoto.png?alt=media&token=c5704c29-888f-4a05-b157-10dea526e6e3' : 'https://firebasestorage.googleapis.com/v0/b/prss-2586c.appspot.com/o/user%2Ffemale.png?alt=media&token=edee1de9-5076-4792-b727-df1cbd4c1cf6',
                    'role' => 'u',
                    'gender' => $request['gender'],
                    'secret' => '',
                    'address' => '',
                    'username' => $request['username'],
                    'userID' => $newUser->uid,
                ]);
                return view('users.registerStaff')->with('success', 'Staff insert successfully')->with('error', null)->with('mail', '')
                    ->with('uname', '')
                    ->with('fname', '')->with('phone', '')->with('password', '');
            } catch (\Throwable $e) { //here if firebase return error 
                switch ($e->getMessage()) {
                    case 'The email address is already in use by another account.':
                        return view('users.registerStaff')->with('success', '')->with('error', "The email address is already in use by another account.")->with('mail', $request['mail'])
                            ->with('uname', $request['uname'])
                            ->with('fname', $request['fname'])->with('phone', $request['phone'])->with('password', $request['password']);

                        break;
                    case 'A password must be a string with at least 6 characters.':
                        return view('users.registerStaff')->with('success', '')->with('error', "A password must be at least 6 characters.")->with('mail', $request['mail'])
                            ->with('uname', $request['uname'])
                            ->with('fname', $request['fname'])->with('phone', $request['phone'])->with('password', $request['password']);

                        break;
                    default:
                        dd($e->getMessage());
                        break;
                }
            }
        }
    }
    public function RegisterStaff()
    {
        $idToken = Session::get('idToken');
        if ($idToken != null) {
            if (Session::get('role') == 'a') {
                return view('users.registerStaff')->with('success', '')->with('error', null)->with('mail', '')
                    ->with('uname', '')
                    ->with('fname', '')->with('phone', '')->with('password', '');
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/login');
        }
    }
}