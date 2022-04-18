<?php

namespace Pckg\Mailo\Api\Endpoint;

use GuzzleHttp\RequestOptions;
use Pckg\Api\Endpoint;

/**
 * Class App
 *
 * @package Pckg\Mailo\Api\Endpoint
 * @property int|string $id
 */
class App extends Endpoint
{
    /**
     * @var string
     */
    protected $path = 'app';

    /**
     * @return AppKey
     */
    public function createAppKey()
    {
        /**
         * @var AppKey $appKey
         */
        $appKey = (new AppKey($this->api))->create(['app_id' => $this->id, 'valid' => true]);

        return $appKey;
    }

    /**
     * @return array|mixed
     */
    public function getAddressesOpenRates()
    {
        return $this->getAndDataResponse('app/addressesOpenRates', null, [
            RequestOptions::TIMEOUT => 15,
        ])->api->getApiResponse('addresses');
    }

    /**
     * @return array|mixed
     */
    public function getMailsOpenRates()
    {
        return $this->getAndDataResponse('app/mailsOpenRates', null, [
            RequestOptions::TIMEOUT => 15,
        ])
            ->api->getApiResponse('mails');
    }

    /**
     * @return array|mixed
     */
    public function getCampaignsOpenRates()
    {
        return $this->getAndDataResponse('app/campaignsOpenRates', null, [
            RequestOptions::TIMEOUT => 15,
        ])
            ->api->getApiResponse('campaigns');
    }
}
