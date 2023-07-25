<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Factory;

use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function sendAnnouncement(Request $request)
    {

        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
        $fireStore  = $factory->createFirestore();
        $database = $fireStore->database();
        $userRef = $database->collection('announcement')->newDocument();
        $userRef->set([
            'date' => date('Y-m-d H:i:s'),
            'description' => $request['message'],
            'userID' => Session::get('firebaseUserId'),
            'title' => $request['title']
        ]);
        return
            view('announcement.announcement')->with('message', 'Announcement send successfully');
    }
    public function showAnnouncement()
    {

        $idToken = Session::get('idToken');
        if ($idToken != null) {
            $userRole = Session::get('role');
            $announcement = "";
            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            $annouRef = $database->collection('announcement')->orderBy('date', 'desc');
            $snapshot = $annouRef->documents();
            foreach ($snapshot as $annou) {
                $announcement =
                    $annou['description'];
                break;
            }

            if ($userRole == 'u') {
                return view('announcement.announcement')->with('message', '')->with('announcement', $announcement);
            } else {
                return view('announcement.announcement')->with('message', '')->with('announcement', '');
            }
        } else {
            return redirect('/login');
        }
    }
    public function showUAnnouncement()
    {

        $idToken = Session::get('idToken');
        if ($idToken != null) {
            $userRole = Session::get('role');
            $announcement = [];
            $annouIndex = 0;
            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
            $fireStore  = $factory->createFirestore();
            $database = $fireStore->database();
            $annouRef = $database->collection('announcement')->orderBy('date', 'desc');
            $snapshot = $annouRef->documents();
            foreach ($snapshot as $annou) {
                $announcement[$annouIndex] = ['title' => $annou['title'], 'description' => $annou['description'], 'date' => $annou['date']];
                $annouIndex++;
            }

            if ($userRole == 'u') {
                return view('announcement.uannouncement')->with('message', '')->with('announcement', $announcement);
            } else {
                return view('announcement.uannouncement')->with('message', '')->with('announcement', '');
            }
        } else {
            return redirect('/login');
        }
    }
}