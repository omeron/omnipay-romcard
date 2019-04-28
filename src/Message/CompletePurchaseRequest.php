<?php

namespace ByTIC\Omnipay\Romcard\Message;

use ByTIC\Omnipay\Romcard\Message\Traits\CompletePurchaseRequestTrait;
use Omnipay\Common\Helper as OmnipayHelper;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class PurchaseResponse
 * @package ByTIC\Omnipay\Romcard\Message
 *
 * @method CompletePurchaseResponse send()
 */
class CompletePurchaseRequest extends AbstractRequest
{
    use CompletePurchaseRequestTrait {
        parseNotification as parseNotificationTrait;
    }

    /**
     * @var null|SaleRequest
     */
    protected $saleRequest = null;

    /**
     * @return bool|mixed
     */
    protected function parseNotification()
    {
        $data = $this->parseNotificationTrait();
        if (is_array($data) && count($data)) {
            $dataSale = [
                'orderId' => $data['ORDER'],
                'amount' => $data['AMOUNT'],
                'cardReference' => $data['RRN'],
                'transactionReference' => $data['INT_REF'],
            ];
            OmnipayHelper::initialize($this->getSaleRequest(), $dataSale);
            $responseSale =  $this->getSaleRequest()->send();
            return $responseSale->getData();
        }
        return [];
    }

    protected function getHttpRequestBag(): ParameterBag
    {
        return $this->httpRequest->query;
    }

    /**
     * @return null|SaleRequest
     */
    public function getSaleRequest()
    {
        return $this->saleRequest;
    }

    /**
     * @param null $saleRequest
     */
    public function setSaleRequest($saleRequest)
    {
        $this->saleRequest = $saleRequest;
    }
}
