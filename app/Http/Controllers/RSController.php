<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Factory;

class RSController extends Controller
{
    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');

        $this->auth = $factory->createAuth();
    }

    public function deleteUser(Request $request)
    {
        $idToken = Session::get('idToken');
        if ($idToken != null) {
            $paths = explode('/', url()->current()); //this line to get url in array

            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            $userRef = $database->collection('user')->document(
                $paths[6]
            )->delete(); //this line to detele user
            $this->auth->deleteUser($paths[6]);
            return redirect('/users/' . $paths[4]);
        } else {
            return redirect('/login');
        }
    }
    public function showUserDetail(Request $request)
    {
        $idToken = Session::get('idToken');
        if ($idToken != null) {
            $paths = explode('/', url()->current());

            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            $userRef = $database->collection('user')->document(
                $paths[5]
            )->snapshot(); //here to get user info by document id

            return view('users.profile')->with('data', [
                $userRef->data()['email'], $userRef->data()['profilePictureURL'],
                $userRef->data()['phonenumber'], $userRef->data()['fullname'],
                $userRef->data()['role'], $userRef->id()
            ]); //to return blade will show profile detail with user data
        } else {
            return redirect('/login');
        }
    }
    public function userSearch(Request $request)
    {

        $idToken = Session::get('idToken');
        if ($idToken != null) {
            //  $paths = explode('/', url()->current());

            $users = [];
            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            $userRef = $database->collection('user');
            $query = $userRef->orderBy('fullname', 'asc')->where('role', '=', 'u')->startAt(["\uf8ff" . $request['search']])
                ->endAt([$request['search'] . "\uf8ff"]);
            $snapshot = $query->documents();
            foreach ($snapshot as $user) {
                array_push($users, [
                    'id' => $user->id(),
                    'mail' => $user['email'],
                    'fullname' => $user['fullname'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'secret' => $user['secret'],
                    'profilePictureURL' => $user['profilePictureURL'],
                    'phonenumber' => $user['phonenumber'],
                    'address' => $user['address']
                ]);
            }

            return view('users.users', ['users' => $users]);
        } else {
            return redirect('/login');
        }
    }

    public function showRS(Request $request)
    {
        $idToken = Session::get('idToken');
        if ($idToken != null) {

            $paths = explode('/', url()->current());

            if ($paths[4] == 'staff') { //here to check which kind of user before search 
                if (Session::get('role') == 'a') { //check if user is admin or resident to show resident user or not 
                    $users = [];

                    $factory = (new Factory)
                        ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
                    $fireStore  = $factory->createFirestore();
                    $database = $fireStore->database();
                    $userRef = $database->collection('user');
                    $query = $userRef->where('role', '=', 'u');
                    $snapshot = $query->documents();
                    foreach ($snapshot as $user) {
                        array_push($users, [
                            'id' => $user->id(),
                            'mail' => $user['email'],
                            'fullname' => $user['fullname'],
                            'username' => $user['username'],
                            'role' => $user['role'],
                            //      'secret' => $user['secret'],
                            'profilePictureURL' => $user['profilePictureURL'],
                            'phonenumber' => $user['phonenumber'],
                            'address' => $user['address']
                        ]);
                    }
                    return view('users.users', ['users' => $users]);
                } else {
                    return redirect('/users/resident');
                }
            } else {
                $users = [];

                $factory = (new Factory)
                    ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
                $fireStore  = $factory->createFirestore();
                $database = $fireStore->database();
                $userRef = $database->collection('user');
                $query = $userRef->where('role', '=', 'u');
                $snapshot = $query->documents();
                foreach ($snapshot as $user) {
                    array_push($users, [
                        'id' => $user->id(),
                        'mail' => $user['email'],
                        'fullname' => $user['fullname'],
                        'username' => $user['username'],
                        'role' => $user['role'],
                        // 'secret' => $user['secret'],
                        'profilePictureURL' => $user['profilePictureURL'],
                        'phonenumber' => $user['phonenumber'],
                        'address' => $user['address']
                    ]);
                }

                return view('users.users', ['users' => $users]);
            }
        } else {
            return redirect('/login');
        }
    }
}