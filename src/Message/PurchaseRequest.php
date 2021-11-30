<?php

namespace Paytic\Omnipay\Romcard\Message;

use Paytic\Omnipay\Common\Message\Traits\RequestDataGetWithValidationTrait;
use Paytic\Omnipay\Romcard\Helper;

/**
 * Class PurchaseRequest
 * @package Paytic\Omnipay\Romcard\Message
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
            'AMOUNT' => Helper::formatAmount($this->getAmount()),
            'CURRENCY' => $this->getCurrency(),
            'ORDER' => Helper::formatOrderId($this->getOrderId()),
            'DESC' => Helper::formatDescription($this->getDescription()),
            'MERCH_NAME' => $this->getMerchantName(),
            'MERCH_URL' => $this->getMerchantUrl(),
            'MERCHANT' => $this->getMerchant(),
            'TERMINAL' => $this->getTerminal(),
            'EMAIL' => $this->getMerchantEmail(),
            'TRTYPE' => Helper::TRANSACTION_TYPE_PREAUTH,

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
}
