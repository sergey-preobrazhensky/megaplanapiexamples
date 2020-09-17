<?php
/** Добавляет сделку со скидкой  */

include(__DIR__ . '/Request.php');

$host = ''; // без протокола, например mp123456.megaplan.ru
$login = '';
$password = '';

$host = 'ra.testoplan.ru'; // без протокола, например mp123456.megaplan.ru
$login = 'magellan';
$password = 'staff_multipass';

$marginType = 'percent';
$marginValue = 11;
$dialId = 1682;
$rate = 1;
$currencyId = 1;

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
    'ProgramId' => 37,
    'Positions' => [[
        'Name' => 'test',
        'Price' => [
            'Value' => 111
        ]
    ]],
    'MarginType' => $marginType, // 'percent' | 'delta'
    'MarginValue' => [
        'Value' => $marginValue,
        'Rate' => $rate,
        'Currency' => $currencyId,
    ]
];

$result = $request->post('/BumsTradeApiV01/Deal/save.api', $data);
print_r("\n\n");
print_r(json_decode($result));