<?php

namespace App\Http\Services;

class EmailData {

    public static function prepareData($mlist, $single_message, $inboxMessage, $key) {
        $message_id = $mlist->id;
        $thread_id = $mlist->threadId;
        $headers = $single_message->getPayload()->getHeaders();
        $snippet = $single_message->getSnippet();

        $message_Cc = '';
        foreach ($headers as $single) {
            if ($single->getName() == 'Subject') {
                $message_subject = $single->getValue();
            } else if ($single->getName() == 'Date') {
                $message_date = $single->getValue();
                $message_date = date('M jS Y h:i A', strtotime($message_date));
            } else if ($single->getName() == 'From') {
                $message_sender = $single->getValue();
//                        echo "<br>sender =" . $message_sender;
                preg_match('/\s*"?([^"]*)"?\s+<(.+)>/', $message_sender, $matches);
                $message_from = str_replace('"', '', $message_sender);
                $message_sender_email = $matches[2];
                if (empty(trim($message_sender_email)))
                    $message_sender_email = trim($message_sender);
            } else if ($single->getName() == 'Delivered-To') {
                $message_receiver = $single->getValue();
                $message_receiver_email = str_replace('"', '', $message_receiver);
            } else if ($single->getName() == 'Cc') {
                $message_receiver = $single->getValue();
                $message_Cc = str_replace('"', '', $message_receiver);
            }
        }
        $inboxMessage[$key] = [
//                        'branch_id' => $value['branch_id'],
            'thread_id' => $thread_id,
            'messageId' => $message_id,
            'message_Cc' => $message_Cc,
            'messageSnippet' => $snippet,
            'messageSubject' => $message_subject ?? null,
            'messageDate' => $message_date ?? null,
            'messageFromEmail' => $message_sender_email ?? null,
            'messageToEmail' => $message_receiver_email ?? null,
//                    'body' => $decodedMessage
        ];

        return $inboxMessage;
    }

    public static function prepareBody($single_message) {
        $parts = $single_message->getPayload()->getParts();
//        $body = $parts[1]['body'];
        $body = (isset($parts[1]['body'])) ? $parts[1]['body'] : null;

        $rawData = ($body) ? $body->data : '';
        if (empty($rawData)) {
            $body = (isset($parts[0]['parts'][1])) ? $parts[0]['parts'][1]['body'] : null;
            $rawData = ($body) ? $body->data : '';
        }

        if (empty(trim($rawData))) {
            $rawData = $single_message->payload->body->data;
            echo "here";
        }
        if (empty(trim($rawData))) {
            $rawData = $parts[0]['parts'][0]['parts'][1]['body']->data;
        }
        if (empty(trim($rawData))) {
            $rawData = $parts[0]['body']->data;
        }

        $sanitizedData = strtr($rawData, '-_', '+/');
        $decodedMessage = base64_decode($sanitizedData);

        return $decodedMessage;
    }

}
