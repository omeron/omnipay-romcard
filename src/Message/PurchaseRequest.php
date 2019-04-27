<?php

namespace ByTIC\Omnipay\Romcard\Message;

use ByTIC\Omnipay\Common\Message\Traits\RequestDataGetWithValidationTrait;
use ByTIC\Omnipay\Romcard\Helper;

/**
 * Class PurchaseRequest
 * @package ByTIC\Omnipay\Romcard\Message
 *
 * @method PurchaseResponse send()
 */
class PurchaseRequest extends AbstractRequest
{
    use RequestDataGetWithValidationTrait;

    /**
     * @inheritdoc
     */
    public function initialize(array $parameters = [])
    {
        $parameters['currency'] = isset($parameters['currency']) ? $parameters['currency'] : 'ron';

        return parent::initialize($parameters);
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    protected function validateDataFields()
    {
        $params = [
            'amount',
            'orderId',
            'description'
        ];
        return array_merge($params, parent::validateDataFields());
    }

    /**
     * @inheritdoc
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    protected function populateData()
    {
        $data = [
            'AMOUNT' => sprintf("%.2f", $this->getAmount()),
            'CURRENCY' => $this->getCurrency(),
            'ORDER' => $this->getOrderId(),
            'DESC' => $this->getDescription(),
            'MERCH_NAME' => $this->getMerchantName(),
            'MERCH_URL' => $this->getMerchantUrl(),
            'MERCHANT' => $this->getMerchant(),
            'TERMINAL' => $this->getTerminal(),
            'EMAIL' => $this->getMerchantEmail(),
            'TRTYPE' => Helper::TRTTYPE_PREAUTH,

            'COUNTRY' => null,
            'MERCH_GMT' => null,
            'TIMESTAMP' => gmdate('YmdHis'),
            'NONCE' => self::generateNonce(),
            'BACKREF' => $this->getReturnUrl(),
        ];

        $data['P_SIGN'] = Helper::generateSignHash($data, $this->getKey());

        $data['redirectUrl'] = $this->getEndpointUrl();

        return $data;
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
