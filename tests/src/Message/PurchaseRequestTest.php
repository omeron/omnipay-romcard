<?php

namespace ByTIC\Omnipay\Romcard\Tests\Message;

use ByTIC\Omnipay\Romcard\Message\PurchaseRequest;
use ByTIC\Omnipay\Romcard\Tests\AbstractTest;
use Guzzle\Http\Client as HttpClient;
use Omnipay\Common\Http\Client;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class PurchaseRequestTest
 * @package ByTIC\Omnipay\Romcard\Tests\Message
 */
class PurchaseRequestTest extends AbstractTest
{

    /**
     * @param $description
     * @param $output
     * @dataProvider dataDescProcessing
     */
    public function testDescProcessing($description, $output)
    {
        $request = $this->newPurchaseRequest();

        $parameters = ['amount' => 9.0, 'orderId' => 8];
        $parameters = require TEST_FIXTURE_PATH . '/enviromentParams.php';

        $request->initialize($parameters);
        $request->setDescription($description);

        $data = $request->getData();
        static::assertSame($output, $data['DESC']);
    }

    public function dataDescProcessing()
    {
        return [
            [
                '1283723791398123798392712381273912379812379813798129729387923838',
                '128372379139812379839271238127391237981237981379812972'
            ],
        ];
    }


    /**
     * @return PurchaseRequest
     */
    protected function newPurchaseRequest()
    {
        $client = new Client();
        $request = HttpRequest::createFromGlobals();
        $request = new PurchaseRequest($client, $request);
        return $request;
    }
}