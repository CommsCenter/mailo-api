<?php namespace Pckg\Mailo\Api\Endpoint;

use GuzzleHttp\RequestOptions;
use Pckg\Api\Endpoint;

/**
 * Class Domain
 *
 * @package Pckg\Mailo\Api\Endpoint
 */
class Domain extends Endpoint
{

    /**
     * @var string
     */
    protected $path = 'domain';

    /**
     * @return array|mixed
     */
    public function getDKIM()
    {
        return $this->getAndDataResponse('domain/dkim', null, [
            RequestOptions::TIMEOUT => 15,
        ])->api->getApiResponse();
    }

    /**
     * @return array|mixed
     */
    public function register($domain)
    {
        return $this->postAndDataResponse([
            'domain' => $domain,
                                          ], 'domain/register', null, [
            RequestOptions::TIMEOUT => 15,
        ])->api->getApiResponse();
    }

}