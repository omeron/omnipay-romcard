<?php

$parameters = isset($parameters) ? $parameters : [];
foreach (['terminal', 'merchant_name', 'merchant_url', 'merchant_email', 'key'] as $field) {
    $parameters[$field] = getenv('ROMCARD_' . strtoupper($field));
}
return $parameters;