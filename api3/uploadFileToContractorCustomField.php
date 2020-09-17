<?php
/******/
$contractorId = 1037094;
$customFieldName = 'Category183CustomFieldVlozhenietest';
$url = '/api/v3/contractorCompany/'.$contractorId;
/*****/

list($host, $user, $pass, $token) = array_values(include __DIR__.'/../settings.php');

/*** UPLOAD FILE ***/
$filePath = __DIR__.'/../res/test.txt';
$boundary = uniqid('', true);
$body = makeFileBody(basename($filePath), $filePath, $boundary);
$headers = [
    'AUTHORIZATION: Bearer '.$token,
    'Content-Type: multipart/form-data; boundary='.$boundary
];
$result = send($host.'/api/file', $body, $headers);
var_dump($result);

$json = json_decode($result);
$newFileId = $json->data[0]->id;
var_dump($newFileId);
/**** END UPLOAD FILE ***/

/*** SAVE FILE TO CONTRACTOR ***/
$body = '{
    "id":"'.$contractorId.'",
    "contentType":"ContractorCompany",
    "'.$customFieldName.'":[{"id": "'.$newFileId.'", "contentType": "File"}]
}';
$result = send($host.$url, $body,
    ['AUTHORIZATION: Bearer '.$token]
);
var_dump($result);
/**** END SAVE FILE TO CONTRACTOR ***/

function makeFileBody(
    $fileName,
    $path,
    $boundary,
    $fieldName = 'files[]'
) {
    $content = file_get_contents($path);

    $fileData = [];
    $fileData['Content-Disposition'] = sprintf('form-data; name="%s"', $fieldName);
    $fileData['Content-Disposition'] .= sprintf('; filename="%s"', $fileName);
    $fileData['Content-Length'] = strlen($content);
    $fileData['Content-Type'] = mime_content_type($path);

    $body = "--$boundary\r\n";
    foreach ($fileData as $key => $value) {
        $body .= sprintf("%s: %s\r\n", $key, $value);
    }
    $body .= "\r\n";
    $body .= $content;
    $body .= "\r\n--{$boundary}--\r\n";

    return $body;
}

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
}