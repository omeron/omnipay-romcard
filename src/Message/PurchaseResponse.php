<?php

namespace Paytic\Omnipay\Romcard\Message;

use Paytic\Omnipay\Common\Message\Traits\RedirectHtmlTrait;
use Paytic\Omnipay\Romcard\Models\Transactions\Purchase;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * PayU Purchase Response
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    use RedirectHtmlTrait;

}
