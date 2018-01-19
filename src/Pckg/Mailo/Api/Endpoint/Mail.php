<?php namespace Pckg\Mailo\Api\Endpoint;

use Pckg\Api\Endpoint;

/**
 * Class Mail
 *
 * @package Pckg\Mailo\Api\Endpoint
 */
class Mail extends Endpoint
{

    /**
     * @var string
     */
    protected $path = 'mail';

    public function send($mail = [], $attachments = [])
    {
        $mail['attachments'] = [];
        foreach ($attachments as $attachment) {
            $mail['attachments'][] = [
                'name'    => $attachment['name'],
                'content' => file_get_contents($attachment['path']),
            ];
        }

        return $this->postAndDataResponse($mail, 'mail/send', 'mail');
    }

    public function sendMultiple($mails)
    {
        return $this->postAndDataResponse(['mails' => $mails], 'mail/send-multiple', 'mails');
    }

    public function fake($data = [])
    {
        return $this->postAndDataResponse($data, 'mail/fake', 'mail');
    }

    public function readAt($datetime)
    {
        return $this->postAndDataResponse(['at' => $datetime], 'mail/' . $this->id . '/read', 'mail');
    }

}