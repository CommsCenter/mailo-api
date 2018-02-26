<?php namespace Pckg\Mailo\Api;

use GuzzleHttp\RequestOptions;
use Pckg\Api\Api as PckgApi;
use Pckg\Mailo\Api\Endpoint\App;
use Pckg\Mailo\Api\Endpoint\AppKey;
use Pckg\Mailo\Api\Endpoint\Mail;

/**
 * Class Api
 *
 * @package Pckg\Mailo\Api
 */
class Api extends PckgApi
{

    /**
     * Api constructor.
     *
     * @param $endpoint
     * @param $apiKey
     */
    public function __construct($endpoint, $apiKey)
    {
        $this->endpoint = $endpoint;
        $this->apiKey = $apiKey;

        $this->requestOptions = [
            RequestOptions::HEADERS => [
                'X-Mailo-Api-Key' => $this->apiKey,
            ],
            RequestOptions::TIMEOUT => 15,
        ];
    }

    /**
     * @param array $data
     *
     * @return Mail
     */
    public function mail($data = [])
    {
        return new Mail($this, $data);
    }

    /**
     * @return App
     */
    public function app()
    {
        return new App($this);
    }

    /**
     * @return AppKey
     */
    public function appKey()
    {
        return new AppKey($this);
    }

}