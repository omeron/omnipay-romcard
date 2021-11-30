<?php

namespace Paytic\Omnipay\Romcard\Tests;

use Paytic\Omnipay\Romcard\Helper;

/**
 * Class HelperTest
 * @package Paytic\Omnipay\Romcard\Tests
 */
class HelperTest extends AbstractTest
{
    public function testGenerateSignHash()
    {
        $data = require TEST_FIXTURE_PATH . '/CompletePuchases/request0.php';
        $params = Helper::orderedResponse(
            $data,
            Helper::TRANSACTION_TYPE_PREAUTH
        );
        $hmac = Helper::generateSignHash($params, getenv('ROMCARD_KEY'));
        self::assertSame($data['P_SIGN'], $hmac);
    }

    /**
     * @dataProvider dataFormatAmount
     * @param $amount
     * @param $formatted
     */
    public function testFormatAmount($amount, $formatted)
    {
        self::assertEquals($formatted, Helper::formatAmount($amount));
    }

    /**
     * @return array
     */
    public function dataFormatAmount()
    {
        return [
            [1, '1.00'],
            [1.00, '1.00'],
            ['1.00', '1.00'],
            ['1.23', '1.23'],
        ];
    }

    /**
     * @dataProvider dataFormatOrderId
     * @param $id
     * @param $formatted
     */
    public function testFormatOrderId($id, $formatted)
    {
        self::assertEquals($formatted, Helper::formatOrderId($id));
    }

    /**
     * @return array
     */
    public function dataFormatOrderId()
    {
        return [
            [1, '000001'],
            [123456, '123456'],
        ];
    }
}
