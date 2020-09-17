<?php
list($host, $user, $pass) = array_values(include __DIR__.'/../settings.php');

$url = '/api/v3/auth/access_token';
$headers = [
    'content-type: multipart/form-data;'
];
$body = [
    'username' => $user,
    'password' => $pass,
    'grant_type' => 'password',
];

$result = send($host.$url, $body, $headers);
var_dump($result);

function send($url, $body, $headers) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
};