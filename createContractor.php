<?php

include(__DIR__ . '/Request.php');

if (file_exists(__DIR__.'/credentials.php')) {
    list($host, $login, $password) = include __DIR__.'/credentials.php';
} else {
    $host = ''; // без протокола, например mp123456.megaplan.ru
    $login = '';
    $password = '';
}

var_dump([$host]);

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
    'Model[TypePerson]' => 'company',
    'Model[CompanyName]' => 'My company',
    'Model[Phones][]' => 'ph_m+79000000001	test_api_phone',
];

$result = $request->post('/BumsCrmApiV01/Contractor/save.api', $data);
print_r("\n\n");
print_r(json_decode($result));