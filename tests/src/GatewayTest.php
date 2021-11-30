<?php

namespace Paytic\Omnipay\Romcard\Tests;

use Paytic\Omnipay\Romcard\Gateway;

class GatewayTest extends AbstractTest
{

    public function testGetSecureUrl()
    {
        $gateway = new Gateway();

        // INITIAL TEST MODE IS TRUE
        self::assertEquals(
            'https://www.activare3dsecure.ro/teste3d/cgi-bin/',
            $gateway->getEndpointUrl()
        );

        $gateway->setTestMode(true);
        self::assertEquals(
            'https://www.activare3dsecure.ro/teste3d/cgi-bin/',
            $gateway->getEndpointUrl()
        );

        $gateway->setTestMode(false);
        self::assertEquals(
            'https://www.secure11gw.ro/portal/cgi-bin/',
            $gateway->getEndpointUrl()
        );
    }
}