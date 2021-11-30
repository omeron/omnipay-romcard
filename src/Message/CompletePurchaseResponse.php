<?php

namespace Paytic\Omnipay\Romcard\Message;

use Paytic\Omnipay\Common\Message\Traits\HtmlResponses\ConfirmHtmlTrait;
use Paytic\Omnipay\Romcard\Message\Traits\CompletePurchaseResponseTrait;

/**
 * Class PurchaseResponse
 * @package Paytic\Omnipay\Romcard\Message
 */
class CompletePurchaseResponse extends AbstractResponse
{
    use ConfirmHtmlTrait;
    use CompletePurchaseResponseTrait;
}
