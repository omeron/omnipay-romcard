<?php

namespace ByTIC\Omnipay\Romcard\Message\Traits;

use Exception;

/**
 * Trait ParseResponseFormTrait
 * @package ByTIC\Omnipay\Romcard\Message\Traits
 * @inspired from https://github.com/mindmagnet/ebtpay-magento1/blob/master/src/app/code/community/MindMagnet/BTPay/lib/btpaygate/btpaygate.php
 */
trait ParseResponseFormTrait
{

    public function sendData($data)
    {
        $url = $this->getEndpointUrl() . '?' . http_build_query($data);
        $httpResponse = $this->httpClient->request('GET', $url);

        $returnData = self::parseResponseHtml($httpResponse->getBody(true));

        return parent::sendData($returnData['input_values']);
    }

    /**
     * Helper method that parses HTML from gateway to identify return URL parameters and parse them into an array
     *
     *  array(
     *      'input_values' = array('name' => 'value'),
     *      'from_action'  = 'https://...',
     *      'from_method'  = 'POST'
     *  )
     *
     *
     * @param string $html
     * @return array
     */
    public static function parseResponseHtml($html)
    {
        $result = ['input_values' => [], 'from_action' => false, 'from_method' => 'POST'];

        if (empty($html)) {
            return $result;
        }

        try {
            $expectedFields = [
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
                'P_SIGN'
            ];
            preg_match(
                '%<form.*?method.*?=.*?"(.*?)".*?action.*?=.*?"(.*?)".*?>(.*?)</form>%s',
                $html,
                $html_data
            );

            //var_dump($html_data);
            if (count($html_data) != 4) {
                throw new Exception('Invalid HTML response from Gateway!', 1);
            }

            $result['from_method'] = $html_data[1];
            $result['from_action'] = $html_data[2];

            preg_match_all(
                '/<input.*?name.*?=.*?"(.*?)".*?value.*?=.*?"(.*?)".*?>/',
                $html_data[3],
                $html_input_data
            );

            //var_dump($html_input_data);
            if (count($html_input_data) != 3 || count($html_input_data[0]) <= 0) {
                throw new Exception('Invalid HTML response from Gateway!', 1);
            }

            $input_data = array();
            foreach ($html_input_data[0] as $key => $val) {
                $_input_name = $html_input_data[1][$key];
                $_input_value = $html_input_data[2][$key];

                $input_data[$_input_name] = $_input_value;
            }

            $result['input_values'] = $input_data;

            //var_dump($result);

        } catch (Exception $e) {
            $result['from_action'] = false;
        }
        return $result;
    }
}