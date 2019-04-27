<?php

namespace ByTIC\Omnipay\Romcard\Message;

use ByTIC\Omnipay\Romcard\Message\Traits\CompletePurchaseRequestTrait;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class PurchaseResponse
 * @package ByTIC\Omnipay\Romcard\Message
 *
 * @method CompletePurchaseResponse send()
 */
class CompletePurchaseRequest extends AbstractRequest
{

    use CompletePurchaseRequestTrait;

    protected function getHttpRequestBag(): ParameterBag
    {
        return $this->httpRequest->query;
    }
}
