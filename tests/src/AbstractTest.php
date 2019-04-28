<?php

namespace ByTIC\Omnipay\Romcard\Tests;

use PHPUnit\Framework\TestCase;

use Guzzle\Http\Client as HttpClient;

/**
 * Class AbstractTest
 */
abstract class AbstractTest extends TestCase
{
    protected $object;

    /**
     * @return HttpClient
     */
    public function generateNoSSLClient()
    {
        $client = new HttpClient();
        $client->setConfig(
            [
                'curl.CURLOPT_SSL_VERIFYHOST' => false,
                'curl.CURLOPT_SSL_VERIFYPEER' => false,
                HttpClient::SSL_CERT_AUTHORITY => 'system'
            ]
        );
        $client->setSslVerification(false, false);
        return $client;
    }
}
