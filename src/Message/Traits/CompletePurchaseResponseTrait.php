<?php

namespace ByTIC\Omnipay\Romcard\Message\Traits;

use ByTIC\Omnipay\Common\Message\Traits\GatewayNotificationResponseTrait;
use ByTIC\Omnipay\Romcard\Helper;
use DateTime;

/**
 * Class CompletePurchaseResponseTrait
 * @package ByTIC\Omnipay\Romcard\Message\Traits
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
