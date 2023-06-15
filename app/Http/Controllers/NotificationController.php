<?php

namespace App\Http\Controllers;

use App\Models\Car;

class NotificationController extends Controller
{
    public static function sendNewAuctionNotification(Car $car)
    {
        $title = "{$car->details->make} {$car->details->model} {$car->details->year} is Up For Auction";
        $body = '🔵 Open the app and start bidding!';
        $fields = [
            'app_id' => '545398d4-a4bd-4202-b82a-152eed4c9e33',
            // 'filters' => array(array("field" => "tag", "key" => "notifyOnNewAuction" , "relation" => "=", "value" => "true")),
            'included_segments' => ['notifyOnNewAuction'], // we can create up to 5 segments; otherwise we use filter ^
            'headings' => ['en' => $title],
            'contents' => ['en' => $body],
        ];

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8',
            'Authorization: Basic Y2FjOThhNGEtNDgxZC00OTM2LWFlYzMtOWExMjExZGE5Yjg5']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        curl_close($ch);

        if (curl_errno($ch)) {
            return false;
        } else {
            return true;
        }
    }

    public static function sendWonAuctionNotification(Car $car, $user_id = '')
    {
        $title = '🎉 YOU WON AN AUCTION! 🎉';
        $body = "🔵 The auction on ({$car->details->make} {$car->details->model} {$car->details->year}) has ended. You are the winner!";
        $fields = [
            'app_id' => '545398d4-a4bd-4202-b82a-152eed4c9e33',
            'include_external_user_ids' => ["{$user_id}"], // onesignal's external user id is same as our system's user id
            'headings' => ['en' => $title],
            'contents' => ['en' => $body],
        ];

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8',
            'Authorization: Basic Y2FjOThhNGEtNDgxZC00OTM2LWFlYzMtOWExMjExZGE5Yjg5']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        curl_close($ch);

        if (curl_errno($ch)) {
            return false;
        } else {
            return true;
        }
    }

    public static function sendOutbiddenNotification(Car $car, $outbiddn_user_id = '')
    {
        $title = '🛑 YOU HAVE BEEN OUTBIDDEN! 🛑';
        $body = "The auction on ({$car->details->make} {$car->details->model} {$car->details->year}) has new bids higher than yours. Come back and start bidding!";
        $fields = [
            'app_id' => '545398d4-a4bd-4202-b82a-152eed4c9e33',
            'include_external_user_ids' => ["{$outbiddn_user_id}"], // onesignal's external user id is same as our system's user id
            'headings' => ['en' => $title],
            'contents' => ['en' => $body],
        ];

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8',
            'Authorization: Basic Y2FjOThhNGEtNDgxZC00OTM2LWFlYzMtOWExMjExZGE5Yjg5']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        curl_close($ch);

        if (curl_errno($ch)) {
            return false;
        } else {
            return true;
        }
    }
}
