<?php

namespace Paytic\Omnipay\Romcard\Tests;

use GuzzleHttp\Client as HttpClient;
use Omnipay\Common\Http\Client;

/**
 * Class AbstractTest
 */
abstract class AbstractTest extends \Omnipay\Tests\TestCase
{
    protected $object;

    /**
     * @return HttpClient
     */
    public function generateNoSSLClient()
    {
        $client = new Client();
//        $client->setConfig(
//            [
//                'curl.CURLOPT_SSL_VERIFYHOST' => false,
//                'curl.CURLOPT_SSL_VERIFYPEER' => false,
//                HttpClient::SSL_CERT_AUTHORITY => 'system'
//            ]
//        );
//        $client->setSslVerification(false, false);
        return $client;
    }
}
