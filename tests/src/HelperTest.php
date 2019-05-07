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
     * @param $formated
     */
    public function testFormatOrderId($id, $formated)
    {
        self::assertEquals($formated, Helper::formatOrderId($id));
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
