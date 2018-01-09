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

    public function send($data = [], $attachments = [])
    {
        $options = [
            //'multipart' => [],
        ];
        $data['attachments'] = [];
        foreach ($attachments as $attachment) {
            $data['attachments'][] = [
                'name'    => $attachment['name'],
                'content' => file_get_contents($attachment['path']),
            ];
        }

        return $this->postAndDataResponse($data, 'mail/send', 'mail', $options);
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