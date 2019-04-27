<?php

namespace ByTIC\Omnipay\Romcard\Message;

use ByTIC\Omnipay\Common\Message\Traits\RedirectHtmlTrait;
use ByTIC\Omnipay\Romcard\Models\Transactions\Purchase;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * PayU Purchase Response
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    use RedirectHtmlTrait;

}
