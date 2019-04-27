<?php

namespace ByTIC\Omnipay\Romcard;

use ByTIC\Omnipay\Romcard\Message\CompletePurchaseRequest;
use ByTIC\Omnipay\Romcard\Message\PurchaseRequest;
use ByTIC\Omnipay\Romcard\Message\ServerCompletePurchaseRequest;
use ByTIC\Omnipay\Romcard\Traits\HasIntegrationParametersTrait;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class Gateway
 * @package ByTIC\Omnipay\Romcard
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
     */
    public function completePurchase(array $parameters = []): RequestInterface
    {
        return $this->createRequest(
            CompletePurchaseRequest::class,
            array_merge($this->getDefaultParameters(), $parameters)
        );
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
