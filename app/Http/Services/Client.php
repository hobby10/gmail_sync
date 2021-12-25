<?php

namespace App\Http\Services;

class Client {

    public static function getClient() {
        $client = new \Google_Client();
        $client->setApplicationName('Gmail API PHP Quickstart');
        $client->setScopes([
            'readonly',
            'modify',
            'compose',
            'send',
        ]);
//    $client->setScopes(Google_Service_Gmail::GMAIL_READONLY);
        $client->setAuthConfig(base_path('credentials.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $tokenPath = base_path('token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {

            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }

            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }

//        \Illuminate\Support\Facades\Storage::put('token.json', json_encode($client->getAccessToken()));
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        return $client;
    }

}
