<?php

namespace ByTIC\Omnipay\Romcard\Message;

use ByTIC\Omnipay\Common\Message\Traits\RequestDataGetWithValidationTrait;
use ByTIC\Omnipay\Romcard\Helper;
use ByTIC\Omnipay\Romcard\Message\Traits\ParseResponseFormTrait;

/**
 * Class SaleRequest
 * @package ByTIC\Omnipay\Romcard\Message
 *
 * @method SaleResponse send()
 */
class SaleRequest extends AbstractRequest
{
    use RequestDataGetWithValidationTrait;
    use ParseResponseFormTrait;

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
            'cardReference',
            'transactionReference'
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
            'ORDER' => $this->getOrderId(),
            'AMOUNT' => sprintf("%.2f", $this->getAmount()),
            'CURRENCY' => $this->getCurrency(),
            'RRN' => $this->getCardReference(),
            'INT_REF' => $this->getTransactionReference(),
            'TRTYPE' => Helper::TRANSACTION_TYPE_SALE,
            'TERMINAL' => $this->getTerminal(),
            'TIMESTAMP' => gmdate('YmdHis'),
            'NONCE' => self::generateNonce(),
            'BACKREF' => $this->getReturnUrl(),
        ];
        $data['BACKREF'] = empty($data['BACKREF']) ? 'http://localhost' : $data['BACKREF'];

        $data['P_SIGN'] = Helper::generateSignHash($data, $this->getKey());
        return $data;
    }
}
