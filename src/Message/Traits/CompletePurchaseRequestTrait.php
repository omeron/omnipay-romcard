<?php

namespace ByTIC\Omnipay\Romcard\Message\Traits;

use ByTIC\Omnipay\Common\Message\Traits\GatewayNotificationRequestTrait;
use ByTIC\Omnipay\Romcard\Helper;

/**
 * Class CompletePurchaseRequestTrait
 * @package ByTIC\Omnipay\Romcard\Message\Traits
 */
trait CompletePurchaseRequestTrait
{
    use GatewayNotificationRequestTrait;

    /**
     * @return mixed
     */
    protected function isValidNotification()
    {
        return $this->hasGet('TERMINAL', 'TRTYPE', 'P_SIGN');
    }

    /**
     * @return bool|mixed
     */
    protected function parseNotification()
    {
        $data = $this->httpRequest->query->all();
        if ($this->validateHash()) {
            return $data;
        }
        return [];
    }

    /**
     * @return []
     */
    public function generateHashData()
    {
        return Helper::orderedResponse(
            $this->httpRequest->query->all(),
            Helper::TRTTYPE_PREAUTH
        );
    }

    /**
     * @return mixed
     */
    public function getModelIdFromRequest()
    {
        return $this->httpRequest->request->get('ORDER');
    }

    /**
     * @return boolean
     */
    protected function validateHash()
    {
        $hash = $this->httpRequest->query->get('P_SIGN');
        $hmac = $this->generateHmac($this->generateHashData());

        if ($hmac == $hash) {
            return true;
        }

        return false;
    }

    /**
     * @param $data
     * @return string
     */
    protected function generateHmac($data)
    {
        $key = $this->getKey();

        return Helper::generateSignHash($data, $key);
    }
}
