<?php


return [

    /*
    |--------------------------------------------------------------------------
    | For interacitng with the Pipedrive API. See https://developers.pipedrive.com/docs/api/v1
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

    'activity' => [
      'inspection_appointment' => 'inspection_appintment',
    ],


];
