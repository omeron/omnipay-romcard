<?php

namespace Paytic\Omnipay\Romcard\Tests\Message;

use Paytic\Omnipay\Romcard\Gateway;
use Paytic\Omnipay\Romcard\Message\CompletePurchaseRequest;
use Paytic\Omnipay\Romcard\Message\CompletePurchaseResponse;
use Paytic\Omnipay\Romcard\Tests\AbstractTest;
use Guzzle\Http\Client as HttpClient;
use Omnipay\Common\Http\Client;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class CompletePurchaseRequestTest
 * @package Paytic\Omnipay\Romcard\Tests\Message
 */
class CompletePurchaseRequestTest extends AbstractTest
{

    public function testSend()
    {
        $client = new Client();
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
