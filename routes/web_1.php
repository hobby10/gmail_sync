<?php

use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/mail', [\App\Http\Controllers\GmailController::class, 'index']);

//Route::resource('/mail', GmailController::class);
Route::get('/test', function () {

    
    

    $mailbox = new Dacastro4\LaravelGmail\LaravelGmailClass(config(), 'hobby10lm@gmail.com');
    
    dd($mailbox);
    
    $user = 'me';
    $list = $service->users_messages->listUsersMessages($user);

//    \Dacastro4\LaravelGmail\Facade\LaravelGmail::message()->unread()->all()


    if (count($list->getMessages()) == 0) {
        print "No labels found.\n";
    } else {
        print "Labels:\n";
        foreach ($list->getMessages() as $label) {
            printf("- %s\n", $label->threadId);
        }
    }
    dd('111');

    $results = $service->users_labels->listUsersLabels($user);
//    dd($list);

    if (count($results->getLabels()) == 0) {
        print "No labels found.\n";
    } else {
        print "Labels:\n";
        foreach ($results->getLabels() as $label) {
            printf("- %s\n", $label->getName());
        }
    }
});

Route::get('/gmail', function () {

    LaravelGmail::setUserId('hobby10lm@gmail.com')->makeToken();
    $mailbox = new Dacastro4\LaravelGmail\LaravelGmailClass(config(), 'hobby10lm@gmail.com');
    
    
    dd(\Dacastro4\LaravelGmail\Facade\LaravelGmail::setUserId('hobby10lm@gmail.com')->makeToken());
    dd($mailbox);
    dd($mailbox->message()->unread()->all());
    dd(\Dacastro4\LaravelGmail\Facade\LaravelGmail::message()->unread()->all());
    dd(
            LaravelGmail::message()
                    ->from('hobby10lm@gmail.com')
//                ->unread()
//                ->in('TRASH')
//                ->hasAttachment()
                    ->all()  
            );
    $messages = LaravelGmail::message()->subject('test')->unread()->preload()->all();
    foreach ($messages as $message) {
        $body = $message->getHtmlBody();
        $subject = $message->getSubject();
        echo $subject . '<br>';
    }

    echo (LaravelGmail::check()) ? 'exist' : 'not' . '   oooo';
});

Route::get('/', function () {

//    $client = new Google\Client();
//    $client->setApplicationName("gmail_test");
//
//    $client->setScopes(\Google\Service\Gmail::GMAIL_READONLY);
//    $client->setClientId('481369326622-dgnu37r42bsk4282cdovjudqm62e6t2a.apps.googleusercontent.com');
//    $client->setClientSecret('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');
////    $client->setAuthConfig('credentials.json');
//    $client->setAccessType('offline');
//
////    $client->setDeveloperKey("AIzaSyBK_rGRmgcsirRBTE1YlDQtf-hbZ57zzCo");
////
////    $client->authenticate("481369326622-dgnu37r42bsk4282cdovjudqm62e6t2a.apps.googleusercontent.com");
//
//    $client->fetchAccessTokenWithRefreshToken("1//0de0MSiM3277VCgYIARAAGA0SNwF-L9IrjdB5wpApaZ_k1H08T3If2xnDudUjrvDDk8YD4NW9hkLgFKyBL-4Ao2eBZhLRae0UM9A");

    $scope = array('email', 'https://mail.google.com/');
    // 'https://www.googleapis.com/auth/gmail.modify',
    //'https://www.googleapis.com/auth/gmail.metadata'

    $application_name = 'gconnect';
//    $client_secret = 'VN8aWlI5Si4IUXJLvgsh8qo8';
//$client_id = '307233500909-utcca196up6sio4hnucad47jc55shprb.apps.googleusercontent.com';
//    $client_id = '319964126915-q7ep4h4rntj85uatii2iqob3434mkmma.apps.googleusercontent.com';
//$application_name='gmail-connect';
//    $client_secret = 'G9IKG-98x8YIOZXNThmxt3Ti';
    $client = new Google\Client();
    $client->setApplicationName($application_name);
//$client->setAuthConfig('tsec.json');
    //var_dump($client);
//$redirect = filter_var('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
//FILTER_SANITIZE_URL);
////die($redirect);
//$client->setRedirectUri($redirect);
//    $client->setClientId($client_id);
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');
//$client->setAccessToken($key['access_token']);
    $client->setScopes($scope);
    //$client->setScopes(Google_Service_Gmail::GMAIL_READONLY);
//$client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);
//    $client->setClientSecret($client_secret);
    //$client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    $client->setAuthConfig(base_path('credentials.json'));
//    $client->setAccessToken("ya29.a0ARrdaM9R0XdCXEBn-p0GXAbqmiCFefnrqgvEIDNp_2Q6nEiDoy08369Dny3M54DSbPUE1JT4E2kuFukJyNLBRJjCvL08fTnP-DtP99k9EhukTcQN3R8QLNa7i595kOuuz8-qzx1kI-c8qDePDjLNAicTJ03U");
//    if ($client->isAccessTokenExpired()) {
//
////     Refresh the token if possible, else fetch a new one.
//        if ($client->getRefreshToken()) {
//            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
//            dd('1');
//        }
//    }
//    dd('2');
//
//    if (!$client->getAccessToken()) {
//        dd("fail");
//    }
//
//    if ($client->getAccessToken()) {
//        $service = new \Google\Service\Gmail($client);
//
////        dd($service);
////        $list = $service->users_messages->listUsersMessages('me');
////        dd($list);
//    }


    $service = new Google\Service\Books($client);
    $query = 'Henry David Thoreau';
    $optParams = [
        'filter' => 'free-ebooks',
    ];
    $results = $service->volumes->listVolumes($query, $optParams);

    foreach ($results->getItems() as $item) {
        echo $item['volumeInfo']['title'], "<br /> \n";
    }

    dd('cc');

    return view('welcome');
});

Route::get('/oauth/gmail', function () {
    return LaravelGmail::redirect();
});

Route::get('/oauth/gmail/callback', function () {
    LaravelGmail::makeToken();
    return redirect()->to('/');
});

Route::get('/oauth/gmail/logout', function () {
    LaravelGmail::logout(); //It returns exception if fails
    return redirect()->to('/');
});
