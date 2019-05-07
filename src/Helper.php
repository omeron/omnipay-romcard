<?php

namespace ByTIC\Omnipay\Romcard;

/**
 * Class Helper
 * @package ByTIC\Omnipay\Romcard
 */
class Helper
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

    const TRANSACTION_TYPE_PREAUTH = 0;
    const TRANSACTION_TYPE_SALE = 21;
    const TRANSACTION_TYPE_REVERSAL = 24;

    const TRANSACTION_TYPE_PARTIAL_REFUND = 25;
    const TRANSACTION_TYPE_FRAUD_FRAUD = 26; //anular e pe motiv de frauda

    /**
     * @param $description
     * @return bool|string
     */
    public static function formatDescription($description)
    {
        // LIMIT DESCRIPTION TO MAX 55 CHARS
        return substr($description, 0, 54);
    }

    /**
     * ORDER NEEDS TO BE BETWEEN 6 AND 19 CHARACTERS LONG
     *
     * @param $id
     * @return bool|string
     */
    public static function formatOrderId($id)
    {
        $len = strlen($id);
        if ($len < 6) {
            $id = str_pad($id, 6, '0', STR_PAD_LEFT);
        }

        return $id;
    }


    /**
     * @param array $params
     * @param string $encryptionKey
     * @return string
     */
    public static function generateSignHash(array $params, string $encryptionKey)
    {

        $res = '';
        foreach ($params as $_key => $_value) {
            if (is_null($_value)) {
                $res .= '-';
            } else {
                $res .= strlen($_value).$_value;
            }
        }

        return strtoupper(hash_hmac('sha1', $res, pack('H*', $encryptionKey)));
    }

    public static function orderedResponse($params, $transactionType)
    {
        $result = [];

        //return empty result if TRTYPE is not set
        if (!isset($transactionType)) {
            return $result;
        }

        $fields = static::getResponseTypeParams($transactionType);

        foreach ($fields as $_field) {
            if (!isset($params[$_field])) {
                continue;
            }

            $result[$_field] = $params[$_field];
        }

        return $result;
    }

    public static function getResponseTypeParams($transactionType)
    {
        switch ($transactionType) {
            case self::TRANSACTION_TYPE_PREAUTH:
                return [
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
                    'NONCE',
                ];
                break;
            case self::TRANSACTION_TYPE_SALE:
                return [
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
                    'NONCE',
                ];
                break;
            case self::TRANSACTION_TYPE_REVERSAL:
                return [
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
                    'NONCE',
                ];
                break;
        }

        return [];
    }
}
