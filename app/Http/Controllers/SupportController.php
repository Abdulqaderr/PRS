<?php

namespace App\Http\Controllers;

use App\appUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Firebase\Auth\Token\Exception\InvalidToken;
use Illuminate\Support\Facades\Redirect;

class SupportController extends Controller
{

  public function showSupport()
  {
    $idToken = Session::get('idToken');
    if ($idToken != null) {
      return view('support.support');
    } else {
      return redirect('/login');
    }
  }
  public function ushowSupport()
  {
    return view('usupport.support');
  }
  public function sendNotification(Request $request)
  {

    $firebaseToken = $request[0];

    $SERVER_API_KEY = ' AAAAwf76P_4:APA91bHyF4LFt8buKC51GomQsgfokke3P5dYny4ri_Pl4NDBGOUFyTOU1S0nFk4UptWi2qRlpRMSQk2VmldYMo__qbrVeWTpJspcPUECIabkudOItblJK0FDsplQtcCc5RKsH_gPoQbO';

    $data = [
      "registration_ids" => [$firebaseToken],
      "data" => ['kind' => 3],
      "notification" => [
        "title" => $request[1],
        "body" => $request[2],
        "content_available" => true,
        "priority" => "high",
      ]
    ];
    $dataString = json_encode($data);

    $headers = [
      'Authorization: key=' . $SERVER_API_KEY,
      'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);

    return $response;
  }
  public function usendMessageListToController(Request $request)
  {
    $adminID = Session::get('userID');

    $factory = (new Factory)
      ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
    $fireStore  = $factory->createFirestore();
    $database = $fireStore->database();
    $userRef = $database->collection('user');

    for ($i = 0; $i < count($request->all()); $i++) {
      ///  $request[0]['userOne'];

      $query = $userRef->where('userID', '=', $request[$i]['sender']);
      $snapshot = $query->documents();

      foreach ($snapshot as $user) {
        $email = $user['email'];
        $imageProfile = $user['profilePictureURL'];
        $phone = $user['phonenumber'];
        $fullname = $user['fullname'];
        $role = $user['role'];
      }

      // $dataFromDB = appUser::where('id', '=', $request[$i]['userTwo'])->select('image', 'name', 'notid')->get();
      $messages[$i]['userOne'] = $request[$i]['sender'];
      $messages[$i]['userTwo'] =
        $adminID;
      $messages[$i]['notid'] = '';
      $messages[$i]['image']
        = $imageProfile;
      $messages[$i]['name'] = $fullname;
      $messages[$i]['aread'] =
        $request[$i]['rRead'];
      $messages[$i]['uread'] =
        $request[$i]['sRead'];
      $messages[$i]['messageID'] =
        $request[$i]['messageID'];
    }
    return [
      'status' => true,
      'data' => $messages,

    ];
  }
  public function sendMessageListToController(Request $request)
  {
    $adminID = Session::get('userID');

    $factory = (new Factory)
      ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
    $fireStore  = $factory->createFirestore();
    $database = $fireStore->database();
    $userRef = $database->collection('user');

    for ($i = 0; $i < count($request->all()); $i++) {
      ///  $request[0]['userOne'];

      $query = $userRef->where('userID', '=', $request[$i]['sender']);
      $snapshot = $query->documents();

      foreach ($snapshot as $user) {
        $email = $user['email'];
        $imageProfile = $user['profilePictureURL'];
        $phone = $user['phonenumber'];
        $fullname = $user['fullname'];
        $role = $user['role'];
      }

      // $dataFromDB = appUser::where('id', '=', $request[$i]['userTwo'])->select('image', 'name', 'notid')->get();
      $messages[$i]['userOne'] =
        $adminID;
      $messages[$i]['userTwo'] = $request[$i]['sender'];
      $messages[$i]['notid'] = '';
      $messages[$i]['image']
        = $imageProfile;
      $messages[$i]['name'] = $fullname;
      $messages[$i]['aread'] =
        $request[$i]['rRead'];
      $messages[$i]['uread'] =
        $request[$i]['sRead'];
      $messages[$i]['messageID'] =
        $request[$i]['messageID'];
    }
    return [
      'status' => true,
      'data' => $messages,

    ];
  }
  public function showAllUsers()
  {

    $idToken = Session::get('idToken');
    if ($idToken != null) {
      return view('support.allUser');
    } else {
      return redirect('/login');
    }
  }
}