<?php


return [

    /*
    |--------------------------------------------------------------------------
    | For interacting with the Pipedrive API. See https://developers.pipedrive.com/docs/api/v1
    |--------------------------------------------------------------------------
    */

    'url' => env('PIPEDRIVE_BASE_URL', ''),


    /*
    |--------------------------------------------------------------------------
    | Connection details
    |--------------------------------------------------------------------------
    */

    'version' => env('PIPEDRIVE_API_VERSION', 'v1'),
    'token' => env('PIPEDRIVE_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Activities API. See https://developers.pipedrive.com/docs/api/v1/Activities
    |--------------------------------------------------------------------------
    */

    /*
    To fetch activities assigned to a specific user, set this to the user's ID.
    Otherwise, set to 0 to fetch all company resources.
    */
    'user_id' => 0,

    'activity' => [
        'inspection_appointment' => 'inspection_appintment',
    ],

];
