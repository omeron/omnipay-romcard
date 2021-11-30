<?php

require 'init.php';

print_r($_GET);

$gateway = new \Paytic\Omnipay\Romcard\Gateway();

$parameters = require TEST_FIXTURE_PATH . 'saleParams.php';
$parameters = require TEST_FIXTURE_PATH . 'enviromentParams.php';

$request = $gateway->sale($parameters);
$response = $request->send();

print_r($response->isSuccessful());
