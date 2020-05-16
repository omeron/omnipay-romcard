<?php

namespace ByTIC\Omnipay\Romcard;

/**
 *
 * Response validator
 * @author MindMagnet
 *
 */
class BTGatewayResponse
{
    /**
     * Action Response Posible values:
     *
     * 0 - tranzactie aprobata
     * 1 - tranzactie duplicata
     * 2 - tranzatie respinsa
     * 3 - eraore de procesare
     */
    const ACTION_APPROVED = '0';
    const ACTION_DUPLICATE = '1';
    const ACTION_REJECTED = '2';
    const ACTION_ERROR = '3';

    const TRTTYPE_PREAUTH = 0;
    const TRTTYPE_CAPTURE = 21;
    const TRTTYPE_VOID = 24;

    const TRTTYPE_PARTIAL_REFUND = 25;
    const TRTTYPE_VOID_FRAUD = 26; //anulare pe motiv de frauda

    /**
     * @var array response GET parameters received from BT Pay Gateway
     */
    protected $_response = array();

    /**
     *
     * Constructor with initializer of response (generically browser $_POST)
     * @param array $response
     * @throws Exception 1,Missing response array
     */
    public function __construct($response = null)
    {
        if (is_null($response)) {
            throw new Exception('Missing response array', 1);
        } else {
            $this->_response = $response;
        }
        return $this;
    }

    /**
     *
     * Validates PSign provided with correct PSign calculation
     * @return bool $valid
     */
    public function isValid($encryption_key)
    {
        if (!isset($this->_response['P_SIGN'])) {
            return false;
        }

        $correctpsign = '';
        foreach ($this->orderedResponse() as $_key => $_value) {
            if (is_null($_value)) {
                $correctpsign .= '-';
            } else {
                $correctpsign .= strlen($_value) . $_value;
            }
        }
        return (strtoupper(hash_hmac('sha1', $correctpsign,
                pack('H*', $encryption_key))) == $this->_response['P_SIGN']);
    }

    public function calculatePSign($encryption_key)
    {
        if (!isset($this->_response['P_SIGN'])) {
            return false;
        }

        $correctpsign = '';
        foreach ($this->orderedResponse() as $_key => $_value) {
            if (is_null($_value)) {
                $correctpsign .= '-';
            } else {
                $correctpsign .= strlen($_value) . $_value;
            }
        }
        return (strtoupper(hash_hmac('sha1', $correctpsign, pack('H*', $encryption_key))));
    }

    /**
     *
     * Helper function that returns an ordered response array for correct PSign calculation
     */
    private function orderedResponse()
    {
        $result = array();

        //return empty result if TRTYPE is not set
        if (!isset($this->_response['TRTYPE'])) {
            return $result;
        }

        switch ($this->_response['TRTYPE']) {
            case BTGatewayResponse::TRTTYPE_PREAUTH:
                $fields = array(
                    'TERMINAL',
                    'TRTYPE',
                    'ORDER',
                    'AMOUNT',
                    'CURRENCY',
                    'DESC',
                    'ACTION',
                    'RC',
                    'MESSAGE',
                    'RRN',
                    'INT_REF',
                    'APPROVAL',
                    'TIMESTAMP',
                    'NONCE'
                );
                break;
            case BTGatewayResponse::TRTTYPE_CAPTURE:
                $fields = array(
                    'ACTION',
                    'RC',
                    'MESSAGE',
                    'TRTYPE',
                    'AMOUNT',
                    'CURRENCY',
                    'ORDER',
                    'RRN',
                    'INT_REF',
                    'TIMESTAMP',
                    'NONCE'
                );
                break;
            case BTGatewayResponse::TRTTYPE_VOID:
                $fields = [
                    'ACTION',
                    'RC',
                    'MESSAGE',
                    'TRTYPE',
                    'AMOUNT',
                    'CURRENCY',
                    'ORDER',
                    'RRN',
                    'INT_REF',
                    'TIMESTAMP',
                    'NONCE'
                ];
                break;
        }

        foreach ($fields as $_field) {
            if (!isset($this->_response[$_field])) {
                continue;
            }

            $result[$_field] = $this->_response[$_field];
        }
        return $result;
    }


    /**
     *
     * Returns capture result flag
     * @return string $result
     * @throws Exception 1,Response is not a capture
     */
    public function getCaptureResult()
    {
        if (!array_key_exists('ACTION', $this->_response)) {
            throw new Exception('Response is not a capture', 1);
        }
        return $this->_response['ACTION'];
    }

    /**
     *
     * Returns capture result
     *
     * Daca in mesajul primit de la RomCard campul ACTION=0, mesajul a fost corect si efectuat cu succes.
     * In cazul in care ACTION are alta valoare, va rugam sa verificati la RomCard starea tranzactiei
     *
     * @throws Exception 1,Response is not a capture
     */
    public function isCaptured()
    {
        if (!array_key_exists('ACTION', $this->_response)) {
            throw new Exception('Response is not a capture', 1);
        }
        return $this->_response['ACTION'] == self::ACTION_APPROVED;
    }

    /**
     *
     * Returns void result flag
     * @return string $result
     * @throws Exception 1,Response is not a void
     */
    public function getVoidResult()
    {
        if (!array_key_exists('ACTION', $this->_response)) {
            throw new Exception('Response is not a void', 1);
        }
        return $this->_response['ACTION'];
    }

    /**
     *
     * Returns void result
     *
     * Daca in mesajul primit de la RomCard campul ACTION=0, mesajul a fost corect si efectuat cu succes.
     * In cazul in care ACTION are alta valoare, va rugam sa verificati la RomCard starea tranzactiei
     *
     * @throws Exception 1,Response is not a void
     */
    public function isVoided()
    {
        if (!array_key_exists('ACTION', $this->_response)) {
            throw new Exception('Response is not a void', 1);
        }
        return $this->_response['ACTION'] == self::ACTION_APPROVED;
    }

    /**
     *
     * Returns void result
     *
     * In cazul in care o tranzactie a fost autorizata (ACTION=0 si exista cod de autorizare in campul "APROVAL"),
     * comerciantul va trimite produsul/serviciul catre client.
     *
     * @throws Exception 1,Response is not valid authorize
     */
    public function isAuthorized()
    {
        if (!array_key_exists('ACTION', $this->_response) || !array_key_exists('APPROVAL', $this->_response)) {
            throw new Exception('Response is not valid authorize', 1);
        }
        return $this->_response['ACTION'] == self::ACTION_APPROVED && !empty($this->_response['APPROVAL']);
    }

    /*
     * ---------- GETTERS/SETTERS
     */

    public function getAmount()
    {
        return floatval($this->_response['AMOUNT']);
    }

    public function getCurrency()
    {
        return $this->_response['CURRENCY'];
    }

    public function getOrder()
    {
        return $this->_response['ORDER'];
    }

    public function getRrn()
    {
        return $this->_response['RRN'];
    }

    public function getIntRef()
    {
        return $this->_response['INT_REF'];
    }

    /**
     * general getter
     *
     * @param string $field
     *
     * @return mixed
     */
    public function getResponse($field = null)
    {
        if (is_null($field)) {
            return $this->_response;
        }

        return (isset($this->_response[$field]) ? $this->_response[$field] : null);
    }
}
