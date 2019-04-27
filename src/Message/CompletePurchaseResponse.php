<?php

namespace ByTIC\Omnipay\Romcard\Message;

use ByTIC\Omnipay\Romcard\Message\Traits\CompletePurchaseResponseTrait;

/**
 * Class PurchaseResponse
 * @package ByTIC\Omnipay\Romcard\Message
 */
class CompletePurchaseResponse extends AbstractResponse
{
    use CompletePurchaseResponseTrait;
}
