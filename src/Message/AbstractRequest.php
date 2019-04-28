<?php

namespace ByTIC\Omnipay\Romcard\Message;

use ByTIC\Omnipay\Common\Message\Traits\SendDataRequestTrait;
use ByTIC\Omnipay\Romcard\Traits\HasIntegrationParametersTrait;
use Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;

/**
 * Class AbstractRequest
 * @package ByTIC\Omnipay\Romcard\Message
 */
abstract class AbstractRequest extends CommonAbstractRequest
{
    use SendDataRequestTrait;
    use HasIntegrationParametersTrait;

    /**
     * @return mixed
     */
    public function getEndpointUrl()
    {
        return $this->getParameter('endpointUrl');
    }

    /**
     * @param $value
     * @return CommonAbstractRequest
     */
    public function setEndpointUrl($value)
    {
        return $this->setParameter('endpointUrl', $value);
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    /**
     * @param  string $value
     * @return mixed
     */
    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    protected function validateDataFields()
    {
        return [
            'merchant',
            'merchantName',
            'merchantEmail',
            'merchantUrl',
            'terminal',
            'key'
        ];
    }


    /**
     *
     * Generate Nonce
     * @return string $nonce
     */
    public static function generateNonce()
    {
        $return = '';
        for ($i = 0; $i < 32; $i++) {
            switch (mt_rand(0, 2)) {
                case 0:
                    $return .= chr(mt_rand(65, 90));
                    break;
                case 1:
                    $return .= chr(mt_rand(97, 122));
                    break;
                case 2:
                    $return .= chr(mt_rand(48, 57));
                    break;
            }
        }

        return $return;
    }
}
