<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class GuzzleWrapper
{
    public function mambu()
    {
        return new Client([
            'handler'         => app('handlestack'),
            'base_uri'        => config('renmoney.mambu.base_url'),
            'auth'            => [config('renmoney.mambu.username'), config('renmoney.mambu.password')],
            'timeout'         => 60,
            'connect_timeout' => 60,
        ]);
    }

    public function bvnClient()
    {
        return new Client([
            'handler'         => app('handlestack'),
            'base_uri'        => config('renmoney.bvn.base_url'),
            'headers'         => [
                config('renmoney.auth_bearer')     => config('renmoney.auth_key'),
                config('renmoney.bvn.auth_bearer') => config('renmoney.bvn.source_app_id'),
            ],
            'timeout'         => 60,
            'connect_timeout' => 60,
        ]);
    }

    public function paystack()
    {
        return new Client([
            'handler'         => app('handlestack'),
            'base_uri'        => config('renmoney.paystack_api.url'),
            'headers'         => [
                'Authorization' => "Bearer " . config('renmoney.paystack_api.key'),
            ],
            'timeout'         => 60,
            'connect_timeout' => 60,
        ]);
    }

    public function offerLetter()
    {
        return new Client([
            'handler'         => app('handlestack'),
            'base_uri'        => config('renmoney.offer_letter.base_url'),
            'headers'         => [
                'RENMONEY-API-KEY' => config('renmoney.offer_letter.key'),
                'Cache-Control'    => 'no-cache',
            ],
            'timeout'         => 60,
            'connect_timeout' => 60,
        ]);
    }

    public function infobip()
    {
        return new Client([
            'handler'         => app('handlestack'),
            'base_uri'        => config('renmoney.infobip.base_url'),
            "headers"         => [
                "Authorization" => config('renmoney.infobip.authorization_type') . ' ' . config('renmoney.infobip.authorization_code'),
            ],
            'timeout'         => 60,
            'connect_timeout' => 60,
        ]);
    }

    public function disbursement()
    {
        return new Client([
            'handler'         => app('handlestack'),
            'base_uri'        => config('renmoney.disbursement.base_url'),
            'headers'         => [
                config('renmoney.disbursement.auth_bearer') => config('renmoney.disbursement.source_app_id'),
            ],
            'timeout'         => 60,
            'connect_timeout' => 60,
        ]);
    }

    public function bamboo()
    {
        return new Client([
            'handler'  => app('handlestack'),
            'base_uri' => config('renmoney.bamboo.base_url'),
            'auth' => [config('renmoney.bamboo.username'), config('renmoney.bamboo.password')],
            'headers'  => [
                'accept'        => 'application/json'
            ],
        ]);
    }
}
