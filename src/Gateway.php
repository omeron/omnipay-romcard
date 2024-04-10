<?php

namespace Paytic\Omnipay\Romcard;

use Paytic\Omnipay\Romcard\Message\CompletePurchaseRequest;
use Paytic\Omnipay\Romcard\Message\PurchaseRequest;
use Paytic\Omnipay\Romcard\Message\SaleRequest;
use Paytic\Omnipay\Romcard\Message\ServerCompletePurchaseRequest;
use Paytic\Omnipay\Romcard\Traits\HasIntegrationParametersTrait;
use Paytic\Omnipay\Common\Gateway\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class Gateway
 * @package Paytic\Omnipay\Romcard
 *
 * @method RequestInterface authorize(array $options = [])
 * @method RequestInterface completeAuthorize(array $options = [])
 * @method RequestInterface capture(array $options = [])
 * @method RequestInterface refund(array $options = [])
 * @method RequestInterface void(array $options = [])
 * @method RequestInterface createCard(array $options = [])
 * @method RequestInterface updateCard(array $options = [])
 * @method RequestInterface deleteCard(array $options = [])

 */
class Gateway extends AbstractGateway
{
    use HasIntegrationParametersTrait;

    /**
     * @var string
     */
    protected $endpointSandbox = 'https://www.activare3dsecure.ro/teste3d/cgi-bin/';

    /**
     * @var string
     */
    protected $endpointLive = 'https://www.secure11gw.ro/portal/cgi-bin/';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Romcard';
    }



    // ------------ REQUESTS ------------ //

    /**
     * @inheritdoc
     * @return PurchaseRequest
     */
    public function purchase(array $parameters = []): RequestInterface
    {
        $parameters['endpointUrl'] = $this->getEndpointUrl();

        return $this->createRequest(
            PurchaseRequest::class,
            array_merge($this->getDefaultParameters(), $parameters)
        );
    }

    /**
     * @inheritdoc
     * @return SaleRequest
     */
    public function sale(array $parameters = []): RequestInterface
    {
        $parameters['endpointUrl'] = $this->getEndpointUrl();

        return $this->createRequest(
            SaleRequest::class,
            array_merge($this->getDefaultParameters(), $parameters)
        );
    }

    /**
     * @inheritdoc
     */
    public function completePurchase(array $parameters = []): RequestInterface
    {
        /** @var CompletePurchaseRequest $request */
        $request = $this->createRequest(
            CompletePurchaseRequest::class,
            array_merge($this->getDefaultParameters(), $parameters)
        );
        $request->setSaleRequest($this->sale($parameters));
        return $request;
    }

    // ------------ PARAMETERS ------------ //

    /** @noinspection PhpMissingParentCallCommonInspection
     *
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return [
            'testMode' => true, // Must be the 1st in the list!
            'card' => [
                'first_name' => ''
            ], //Add in order to generate the Card Object
        ];
    }


    /**
     * Get live- or testURL.
     */
    public function getEndpointUrl()
    {
        $defaultUrl = $this->getTestMode() === false
            ? $this->endpointLive
            : $this->endpointSandbox;

        return $this->parameters->get('endpointUrl', $defaultUrl);
    }

    /**
     * @param  boolean $value
     * @return $this|AbstractGateway
     */
    public function setTestMode($value)
    {
        $this->parameters->remove('endpointUrl');

        return parent::setTestMode($value);
    }

    // ------------ Getter'n'Setters ------------ //
}
