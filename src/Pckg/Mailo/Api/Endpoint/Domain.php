<?php namespace Pckg\Mailo\Api\Endpoint;

use GuzzleHttp\RequestOptions;
use Pckg\Api\Endpoint;
use Pckg\Database\Obj;

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
    protected $path = 'domains';

    /**
     * @return array|mixed
     */
    public function getDKIM($domain, $selector = 'mailo')
    {
        return $this->api->postApi('domains/' . $domain . '/dkim/' . $selector)->getApiResponse('dkim');
    }

}
