<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Firebase\Auth\Token\Exception\InvalidToken;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Exception\Auth\RevokedIdToken;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;
use Illuminate\Support\Facades\Mail;

class userController extends Controller
{

    protected $auth, $database;
    //this construct to initialize firebase info
    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
        $this->auth = $factory->createAuth();
    }
    public function RegisterUser(Request $request)
    {


        //if condition to check if all input insert correctly 
        if ($request['username'] == null || $request['fullname'] == null || $request['mail'] == null  || $request['phone'] == null  ||  $request['password'] == null) {
            // if in correctly the return with error and all data to make user continue register
            return view('users.registerStaff')->with('success', '')->with('error', "Insert all input correctly")->with('mail', $request['mail'])
                ->with('username', $request['username'])
                ->with('fullname', $request['fullname'])->with('phone', $request['phone'])->with('password', $request['password']);
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
                    'fullname' => $request['fullname'],
                    'phonenumber' => $request['phone'],
                    'profilePictureURL' => $request['gender'] == 'male' ? 'https://firebasestorage.googleapis.com/v0/b/prss-2586c.appspot.com/o/user%2FdefaultPhoto.png?alt=media&token=c1b3a960-8521-4957-bf32-2c1b459b2fb7' : 'https://firebasestorage.googleapis.com/v0/b/prss-2586c.appspot.com/o/user%2FdefaultPhoto.png?alt=media&token=c1b3a960-8521-4957-bf32-2c1b459b2fb7',
                    'role' => 'u',
                    'gender' => $request['gender'],
                    'secret' => '',
                    'address' => '',
                    'username' => $request['username'],
                    'userID' => $newUser->uid,
                ]);
                //to save user info in session


                Session::save();
                return redirect('/');
            } catch (\Throwable $e) { //here if firebase return error 

                switch ($e->getMessage()) {

                    case 'The email address is already in use by another account.':
                        return view('auth.register')->with('success', '')->with('error', "The email address is already in use by another account.")->with('mail', $request['mail'])
                            ->with('username', $request['username'])
                            ->with('fullname', $request['fullname'])->with('phone', $request['phone'])->with('password', $request['password']);

                        break;
                    case 'A password must be a string with at least 6 characters.':
                        return view('auth.register')->with('success', '')->with('error', "A password must be at least 6 characters.")->with('mail', $request['mail'])
                            ->with('username', $request['username'])
                            ->with('fullname', $request['fullname'])->with('phone', $request['phone'])->with('password', $request['password']);

                        break;
                    default:
                        dd($e->getMessage());
                        break;
                }
            }
        }
    }
    public function resetPassword(Request $request)
    {
        $checkCode = false;
        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
        $fireStore  = $factory->createFirestore();
        $database = $fireStore->database();
        $resetRef = $database->collection('resetPassword');
        $query = $resetRef->where('code', '=',  $request['code']);
        $snapshot = $query->documents();
        foreach ($snapshot as $reset) {
            if ($reset['code']) {
                $code = $reset['code'];
                $mail = $reset['mail'];
                $id = $reset->id();
                $checkCode = true;
            }
        }
        if ($checkCode) {
            if ($request['password'] == null) {
                return view('auth.reset')->with('error', 'Password empty')->with('data', [$request['code'], $request['password'], $request['cpassword']]);
            } else if (strlen($request['password']) < 6) {
                return view('auth.reset')->with('error', 'Password must be six character or more')->with('data', [$request['code'], $request['password'], $request['cpassword']]);
            } else if ($request['cpassword'] == null) {
                return view('auth.reset')->with('error', 'Confirm password empty')->with('data', [$request['code'], $request['password'], $request['cpassword']]);
            } else if ($request['password'] != $request['cpassword']) {
                return view('auth.reset')->with('error', 'Password not equal')->with('data', [$request['code'], $request['password'], $request['cpassword']]);
            }
            $mailCheck = $this->auth->getUserByEmail($mail);
            $this->auth->changeUserPassword($mailCheck->uid, $request['password']);

            $resetRef->document($id)->delete();
            return redirect('/login');
        } else {
            return view('auth.reset')->with('error', 'Code not found')->with('data', ['', '', '']);
        }
    }
    public function showForget()
    {
        return view('auth.forget');
    }
    public function sendMail(Request $request)
    {
        //here will start by check if mail exiting or not

        try {
            $this->auth->getUserByEmail($request['mail']);
            $length = 10;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $data = [
                'email' => $request['mail'],
                'forgetPassword' => "Password Reset",
                'code' => $randomString,
                'bodyMessage' =>  "Password Reset",
                'lang' => 'rtl',
            ];
            Mail::send('template.resetTemplate', $data, function ($message) use ($data) {
                $message->from(
                    'abdulqaderrr@hotmail.com',
                    "Password Reset"
                );
                $message->to($data['email']);
                $message->subject($data['bodyMessage']);
            });
            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            $userRef = $database->collection('resetPassword')->newDocument();
            $userRef->set([
                'mail' => $request['mail'],
                'code' => $randomString,
            ]);
            return view('auth.reset')->with('error', '')->with('data', ['', '', '']);
        } catch (\Throwable $e) {
            switch ($e->getMessage()) {
                case "No user with email '" . $request['mail'] . "' found.":
                    return Redirect::back()->withErrors("Email not found");
                case "The email address is invalid.":
                    return Redirect::back()->withErrors("Email is invalid");
                default:
                    dd($e->getMessage());
                    break;
            }
        }
    }
    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
    public function uploadUserImage(Request $request)
    {
        $image = $request['image']; //image file from frontend  

        $firebase_storage_path = 'Admin/';
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

            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            $userRef = $database->collection('user');
            $query = $userRef->where('email', '=', Session::get('mail'));
            $snapshot = $query->documents(); //get user data to get document id to can change his data in db 
            foreach ($snapshot as $user) {
                $documentID = $user->id();
            }

            $userRef1 = $database->collection('user')->document(
                $documentID
            );
            $userRef1->update([
                ['path' => 'profilePictureURL', 'value' => $imagePath],
            ]);

            Session::put('profileImage', $imagePath);
            Session::save();
            return Redirect::back();
        } else {
            echo 'error';
        }
        // dd($request);
    }
    public function changeProfile(Request $request)
    {

        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
        $fireStore  = $factory->createFirestore();
        $database = $fireStore->database();
        $userRef = $database->collection('user');
        $query = $userRef->where('email', '=', Session::get('mail'));
        $snapshot = $query->documents(); //get user data to get document id to can change his data in db 
        foreach ($snapshot as $user) {
            $documentID = $user->id();
        }
        $userRef1 = $database->collection('user')->document(
            $documentID
        );


        if ($request->post('mail') == Session::get('mail')) { // check if user change mail if true will change it in auth firebase (check by compare a mail come from user and the mail in session)

            if ($request->post('password') != null) { // to check if user change password
                $this->auth->changeUserPassword(Session::get('firebaseUserId'), $request->post('password'));
            }

            $userRef1->update([
                ['path' => 'fullname', 'value' => $request->post('fname')],
                ['path' => 'phonenumber', 'value' => $request->post('phone')],
            ]);
            Session::put('fullname', $request->post('fname')); // update his name is session 
            Session::save();
            return Redirect::back();
        } else {

            if ($request->post('password') != null) {
                $this->auth->changeUserPassword(Session::get('firebaseUserId'), $request->post('password'));
            }

            $this->auth->changeUserEmail(Session::get('firebaseUserId'), $request->post('mail'));

            $userRef1->update([
                ['path' => 'fullname', 'value' => $request->post('fullname')],
                ['path' => 'phonenumber', 'value' => $request->post('phone')],
                ['path' => 'email', 'value' => $request->post('mail')],
            ]);
            Session::put('mail', $request->post('mail'));
            Session::put('fullname', $request->post('fullname'));
            Session::save();
            return Redirect::back();
        }
    }
    public function showEditProfile(Request $request)
    {

        $idToken = Session::get('idToken');
        if ($idToken != "") {
            $email = '';
            $imageProfile = '';
            $phone = '';
            $fullname = '';
            $role = '';
            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            $userRef = $database->collection('user');
            $query = $userRef->where('email', '=',  Session::get('mail'));
            $snapshot = $query->documents();
            foreach ($snapshot as $user) {
                $email = $user['email'];
                $imageProfile = $user['profilePictureURL'];
                $phone = $user['phonenumber'];
                $fullname = $user['fullname'];
                $role = $user['role'];
            }

            return view('profile.edit')->with('data', [$email, $imageProfile, $phone, $fullname, $role]);
        } else {
            return redirect('/login');
        }
    }
    public function showProfile(Request $request)
    {
        $idToken = Session::get('idToken');

        if ($idToken != "") {
            $email = '';
            $imageProfile = '';
            $phone = '';
            $fullname = '';
            $role = '';
            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            $userRef = $database->collection('user');
            $query = $userRef->where('email', '=',  Session::get('mail'));
            $snapshot = $query->documents();
            foreach ($snapshot as $user) {
                $email = $user['email'];
                $imageProfile = $user['profilePictureURL'];
                $phone = $user['phonenumber'];
                $fullname = $user['fullname'];
                $role = $user['role'];
            }

            return view('profile.profile')->with('data', [$email, $imageProfile, $phone, $fullname, $role]);
        } else {
            return redirect('/login');
        }
    }
    public function loginIndex() //this function for handle login route if user login or not before
    {

        $idToken = Session::get('idToken');
        if ($idToken != null) {
            return redirect('/');
        } else {
            return view('auth.login');
        }
    }
    public function signIndex() //this function for handle login route if user login or not before
    {

        $idToken = Session::get('idToken');
        if ($idToken != null) {
            return redirect('/');
        } else {
            return view('auth.register')->with('error', '')->with('mail', '')
                ->with('fullname', '')
                ->with('username', '')->with('phone', '')->with('password', '');
        }
    }
    public function signIn(Request $request) //this function for sign in and save user data in session
    {
        try {
            //   $this->auth->changeUserPassword()
            //$this->auth->changeUserEmail();
            $signInResult = $this->auth->signInWithEmailAndPassword($request['mail'], $request['password']);
            //  dump($signInResult->data());

            $this->userCheck($signInResult->idToken());
            // Session::put('firebaseUserId', $signInResult->firebaseUserId());

            //this section to get user info form firestore
            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            $userRef = $database->collection('user');
            $query = $userRef->where('email', '=', $request['mail']);
            $snapshot = $query->documents();

            foreach ($snapshot as $user) {
                $role = $user['role'];

                $imageProfile = $user['profilePictureURL'];
                $fullname = $user['fullname'];
                $userID = $user->id();
            }

            //to save user info in session
            Session::put('idToken', $signInResult->idToken());
            Session::put('role', $role);
            Session::put('profileImage', $imageProfile);
            Session::put('fullname', $fullname);
            Session::put('mail', $request['mail']);
            Session::put('userID', $userID);

            Session::save();
            return redirect('/');
        } catch (\Throwable $e) { //to catch if user enter invalid password or email and to catch if error happen in code inside try
            switch ($e->getMessage()) {
                case 'INVALID_PASSWORD':
                    return Redirect::back()->withErrors("Invalid password");
                case 'EMAIL_NOT_FOUND':
                    return Redirect::back()->withErrors("Email not found");
                default:
                    dd($e->getMessage());
                    break;
            }
        }
    }
    public function userCheck($idToken) //this function check user statis and get user id from firebase auth
    {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken, $checkIfRevoked = true);
            // dump($verifiedIdToken);
            Session::put('firebaseUserId', $verifiedIdToken->claims()->get('sub'));
            Session::save();
            //   dump($this->auth->getUser($verifiedIdToken->claims()->get('sub')));
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }
}