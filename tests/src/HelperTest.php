<?php

namespace ByTIC\Omnipay\Romcard\Tests;

use ByTIC\Omnipay\Romcard\Helper;

/**
 * Class HelperTest
 * @package ByTIC\Omnipay\Romcard\Tests
 */
class HelperTest extends AbstractTest
{

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
