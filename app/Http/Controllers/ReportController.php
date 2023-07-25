<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Factory;
use Google\Cloud\Core\Timestamp;

class ReportController extends Controller
{
    public function reportInterval(Request $request)
    {
        $idToken = Session::get('idToken');
        if ($idToken != null) {
            if ($request['from'] == null || $request['to'] == null) {
                return Redirect::back()->withErrors("Please add date correctly");
            } else {

                $approved = 0;
                $pending = 0;
                $completed = 0;
                $reject = 0;
                $canceled = 0;
                $oneStar = 0;
                $twoStar = 0;
                $threeStar = 0;
                $fourStar = 0;
                $fiveStar = 0;
                $factory = (new Factory)
                    ->withServiceAccount(__DIR__ . '/prss-2586c-firebase-adminsdk-hhs77-7f764ff260.json');
                $fireStore  = $factory->createFirestore();
                $database = $fireStore->database();
                $ticketRef = $database->collection('ticket');
                $start = $request['from'];
                $end = $request['to'];
                // return [$start, $end];
                $query = $ticketRef->orderBy('dateTime', 'ASC')->startAt([$start])->endAt([$end]);
                $snapshot = $query->documents();

                foreach ($snapshot as $ticket) {

                    if ($ticket['status'] == 0) {

                        $pending++;
                    } else  if ($ticket['status'] == 1) {

                        $approved++;
                    } else  if ($ticket['status'] == 2) {
                        $completed++;
                    } else    if ($ticket['status'] == 3) {
                        $reject++;
                    } else  if ($ticket['status'] == 4) {
                        $canceled++;
                    }
                    if ($ticket['rate'] == 1) {
                        $oneStar++;
                    } else  if ($ticket['rate'] == 2) {
                        $twoStar++;
                    } else  if ($ticket['rate'] == 3) {
                        $threeStar++;
                    } else  if ($ticket['rate'] == 4) {
                        $fourStar++;
                    } else  if ($ticket['rate'] == 5) {
                        $fiveStar++;
                    }
                }
                $calculateStars = ($oneStar + $twoStar + $threeStar + $fourStar + $fiveStar) == 0 ? 0 : (5 * $fiveStar + 4 * $fourStar + 3 * $threeStar + 2 * $twoStar + 1 * $oneStar) / ($oneStar + $twoStar + $threeStar + $fourStar + $fiveStar);

                return view('report.report')->with('data', [$pending, $approved, $completed, $reject, $canceled, $request['from'], $request['to'], $calculateStars, $canceled]);
            }
        } else {
            return redirect('/login');
        }
    }
    public function showReport(Request $request)
    {
        $idToken = Session::get('idToken');
        if ($idToken != null) {
            return view('report.report')->with('data', [0, 0, 0, 0, 0, '', '', 0, 0]);
        } else {
            return redirect('/login');
        }
    }
}