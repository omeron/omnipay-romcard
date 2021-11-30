<?php

namespace Paytic\Omnipay\Romcard\Message\Traits;

use Paytic\Omnipay\Common\Message\Traits\GatewayNotificationResponseTrait;
use Paytic\Omnipay\Romcard\Helper;
use DateTime;

/**
 * Class CompletePurchaseResponseTrait
 * @package Paytic\Omnipay\Romcard\Message\Traits
 */
trait CompletePurchaseResponseTrait
{
    use GatewayNotificationResponseTrait;
    use BaseParamsResponseTrait;


    public function isError()
    {

    }


    /** @noinspection PhpMissingParentCallCommonInspection
     * @return bool
     */
    protected function canProcessModel()
    {
        return $this->hasDataProperty('notification');
    }
}
