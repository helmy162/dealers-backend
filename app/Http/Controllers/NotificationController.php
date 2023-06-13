<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Car;

class NotificationController extends Controller
{
    static function sendNewAuctionNotification(Car $car)
    {
        $title = "{$car->details->make} {$car->details->model} {$car->details->year} is Up For Auction";
        $body = "🔵 Open the app and start bidding!";
        $fields = array(
            'app_id' => "545398d4-a4bd-4202-b82a-152eed4c9e33",
            // 'filters' => array(array("field" => "tag", "key" => "notifyOnNewAuction" , "relation" => "=", "value" => "true")),
            'included_segments' => array('notifyOnNewAuction'), // we can create up to 5 segments; otherwise we use filter ^
            'headings' => array('en' => $title),
            'contents' =>  array('en' => $body)
        );
        
        $fields = json_encode($fields);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic Y2FjOThhNGEtNDgxZC00OTM2LWFlYzMtOWExMjExZGE5Yjg5'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);

        curl_close($ch);

        if (curl_errno($ch)) {
            return false;
        } else {
            return true;
        }
    }

    static function sendWonAuctionNotification(Car $car, $user_id="")
    {
        $title = "🎉 YOU WON AN AUCTION! 🎉";
        $body = "🔵 The auction on ({$car->details->make} {$car->details->model} {$car->details->year}) has ended. You are the winner!";
        $fields = array(
            'app_id' => "545398d4-a4bd-4202-b82a-152eed4c9e33",
            'include_external_user_ids' => array("{$user_id}"), // onesignal's external user id is same as our system's user id
            'headings' => array('en' => $title),
            'contents' =>  array('en' => $body)
        );
        
        $fields = json_encode($fields);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic Y2FjOThhNGEtNDgxZC00OTM2LWFlYzMtOWExMjExZGE5Yjg5'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);

        curl_close($ch);

        if (curl_errno($ch)) {
            return false;
        } else {
            return true;
        }  
    }

    static function sendOutbiddenNotification(Car $car, $outbiddn_user_ids=array())
    {
        $title = "🛑 YOU HAVE BEEN OUTBIDDEN! 🛑";
        $body = "The auction on ({$car->details->make} {$car->details->model} {$car->details->year}) has new bids higher than yours. Come back and start bidding!";
        $fields = array(
            'app_id' => "545398d4-a4bd-4202-b82a-152eed4c9e33",
            'include_external_user_ids' => array_map('strval', $outbiddn_user_ids), // onesignal's external user id is same as our system's user id
            'headings' => array('en' => $title),
            'contents' =>  array('en' => $body)
        );
        
        $fields = json_encode($fields);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic Y2FjOThhNGEtNDgxZC00OTM2LWFlYzMtOWExMjExZGE5Yjg5'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);

        curl_close($ch);

        if (curl_errno($ch)) {
            return false;
        } else {
            return true;
        }  
    }
}
