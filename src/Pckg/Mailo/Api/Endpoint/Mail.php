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
            'multipart' => [],
        ];
        foreach ($attachments as $attachment) {
            $options['multipart'][] = [
                'name'     => $attachment['name'],
                'contents' => file_get_contents($attachment['path']),
            ];
        }

        return $this->postAndDataResponse($data, 'mail/send', 'mail', $options);
    }

}