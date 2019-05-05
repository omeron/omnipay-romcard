<?php

namespace ByTIC\Omnipay\Romcard\Tests\Message;

use ByTIC\Omnipay\Romcard\Gateway;
use ByTIC\Omnipay\Romcard\Message\CompletePurchaseRequest;
use ByTIC\Omnipay\Romcard\Message\CompletePurchaseResponse;
use ByTIC\Omnipay\Romcard\Tests\AbstractTest;
use Guzzle\Http\Client as HttpClient;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class CompletePurchaseRequestTest
 * @package ByTIC\Omnipay\Romcard\Tests\Message
 */
class CompletePurchaseRequestTest extends AbstractTest
{

    public function testSend()
    {
        $client = new HttpClient();
        $request = HttpRequest::createFromGlobals();
        $parameters = require TEST_FIXTURE_PATH . DIRECTORY_SEPARATOR . 'completePurchaseParams.php';
        $request->query->replace($parameters);

        $gateway = new Gateway($client, $request);

        $parameters = require TEST_FIXTURE_PATH . '/enviromentParams.php';
        $request = $gateway->completePurchase($parameters);

        /** @var CompletePurchaseResponse $response */
        $response = $request->send();

        self::assertInstanceOf(CompletePurchaseResponse::class, $response);

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isPending());
        self::assertFalse($response->isCancelled());

        self::assertEquals('0', $response->getCode());
        self::assertEquals('Approved', $response->getMessage());
        self::assertEquals('400509625162', $response->getCardReference());
        self::assertEquals('1F4129EAE09A0B25', $response->getTransactionReference());
        self::assertEquals('100006', $response->getTransactionId());
    }

    /**
     * @return CompletePurchaseRequest
     */
    protected function newPurchaseRequest()
    {
        $client = new HttpClient();
        $request = HttpRequest::createFromGlobals();
        $request = new CompletePurchaseRequest($client, $request);
        return $request;
    }
}
