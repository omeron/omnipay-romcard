<?php

namespace ByTIC\Omnipay\Romcard\Message;

use ByTIC\Omnipay\Common\Message\Traits\DataAccessorsTrait;
use Omnipay\Common\Message\AbstractResponse as CommonAbstractResponse;

/**
 * Class Response
 * @package ByTIC\Omnipay\Romcard\Message
 */
abstract class AbstractResponse extends CommonAbstractResponse
{
    use DataAccessorsTrait;
}
