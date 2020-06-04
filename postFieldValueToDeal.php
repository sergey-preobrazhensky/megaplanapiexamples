<?php

include(__DIR__ . '/Request.php');

$host = ''; // без протокола, например mp123456.megaplan.ru
$login = '';
$password = '';
$fieldName = '';
$fieldValue = '';
$dialId = '';

$request = new SdfApi_Request('', '', $host, true);
$result = $request->get(
    '/BumsCommonApiV01/User/authorize.api', array(
        'Login' => $login,
        'Password' => md5($password)
    )
);
$response = json_decode(
    $result
);
var_dump($response);

$accessId = $response->data->AccessId;
$secretKey = $response->data->SecretKey;
if (empty($accessId) || empty($secretKey)) {
    die();
}

$request = new SdfApi_Request($accessId, $secretKey, $host, true);

$data = [
    'Id' => $dialId,
    'Model['.$fieldName.']' => $fieldValue
];

$result = $request->post('/BumsTradeApiV01/Deal/save.api', $data);
print_r("\n\n");
print_r(json_decode($result));