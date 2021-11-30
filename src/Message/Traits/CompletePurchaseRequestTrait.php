<?php

namespace Paytic\Omnipay\Romcard\Message\Traits;

use Paytic\Omnipay\Common\Message\Traits\GatewayNotificationRequestTrait;
use Paytic\Omnipay\Romcard\Helper;

/**
 * Class CompletePurchaseRequestTrait
 * @package Paytic\Omnipay\Romcard\Message\Traits
 */
trait CompletePurchaseRequestTrait
{
    use GatewayNotificationRequestTrait;

    /**
     * @return mixed
     */
    public function isValidNotification()
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
            foreach ($data as $key=>$value) {
                $this->setDataItem($key, $value);
            }
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
            Helper::TRANSACTION_TYPE_PREAUTH
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
