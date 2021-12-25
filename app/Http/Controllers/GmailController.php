<?php

namespace App\Http\Controllers;

use App\Http\Services\Client;
use App\Http\Services\EmailData;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Dacastro4\LaravelGmail\Services\Message\Mail;

class GmailController extends Controller {

    public function index() {
        $user = 'me';
        $optParamsGet2['format'] = 'full';
        $inboxMessage = [];
        $key = 0;
        $client = Client::getClient();
        $service = new \Google\Service\Gmail($client);
        $list = $service->users_messages->listUsersMessages($user, ['q' => 'is:unread']);
//        $list = $service->users_messages->listUsersMessages($user, ['q'=>'is:unread', 'includeSpamTrash'=>'true']);

        if (count($list->getMessages()) == 0) {
            echo "No labels found.\n";
        } else {
            foreach ($list->getMessages() as $mlist) {
                //  $optParamsGet2['metadataHeaders']='Subject,References,Message-ID';
                $single_message = $service->users_messages->get('me', $mlist->id, $optParamsGet2);

                $decodedMessage = EmailData::prepareBody($single_message);
                $inboxMessage = EmailData::prepareData($mlist, $single_message, $inboxMessage, $key);

                $inboxMessage[$key] = $inboxMessage[$key] + ['body' => $decodedMessage];
                $key++;
            }

            dd($inboxMessage);
        }
    }

    public function test() {
        $key = 0;
        $emails_data = [];
        $client = Client::getClient();
        LaravelGmail::setToken($client->getAccessToken());

        foreach (LaravelGmail::message()->unread()->all() as $email) {
            $emailObj = LaravelGmail::message()->get($email->getId());
            $emails_data[$key]['subject'] = $emailObj->getSubject();
            $emails_data[$key]['date'] = $emailObj->getDate();
            $emails_data[$key]['body'] = $emailObj->getPlainTextBody();
            $emails_data[$key]['to'] = $emailObj->getTo();
            $emails_data[$key]['from'] = $emailObj->getFrom();
            $key++;
        }

        dd($emails_data);
    }

    public function sendEmail() {
        $client = Client::getClient();
        $mail = new Mail;
        $mail->using($client->getAccessToken()); //using as from
//        ->from('hobby10lm@gmail.com', 'hobby')
        $sended_email = $mail->to('hobby10lm@gmail.com', 'laravel')->subject('test transperant')->message('test test test transperant')->send();
        echo (($sended_email) ? 'true' : 'false');
    }

}
