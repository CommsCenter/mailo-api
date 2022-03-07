<?php namespace Pckg\Mailo\Api\Endpoint;

use GuzzleHttp\RequestOptions;
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
        if ($attachments) {
            $options = [
                'multipart' => [],
            ];

            $mail['attachments'] = [];
            foreach ($attachments as $i => $attachment) {
                $key = 'upload' . $i;
                $mail['attachments'][] = [
                    'name' => $attachment['name'],
                    'key' => $key,
                ];
                $options['multipart'][] = [
                    'name' => $key,
                    'filename' => $attachment['name'],
                    'contents' => file_get_contents($attachment['path']),
                ];
            }

            // add original body
            $options['multipart'][] = [
                'name' => 'body',
                'filename' => 'body',
                'contents' => json_encode($mail),
            ];

            return $this->postAndDataResponse([], 'mail/send', 'mail', $options);
        }

        return $this->postAndDataResponse($mail, 'mail/send', 'mail');
    }

    public function sendMultiple($mails)
    {
        return $this->postAndDataResponse(['mails' => $mails], 'mail/send-multiple', 'mails',
                                          [RequestOptions::TIMEOUT => 120]);
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
