<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';
require 'objects.php';

$response ='{"responseData":{"translatedText":"hi"},"quotaFinished":true,"responseStatus":200,"responderId":"91","exceptionCode":null,"matches":[{"id":"427602294","segment":"привет"},{"id":"521931480","segment":"привет"},{"id":"531339673","segment":"привет."}]}';

$data = json_decode($response,true);

$arranger = new \Jaddek\Arranger\Arranger();
/** @var ResponseObject $object */
$object = $arranger->compose($data, ResponseObject::class);

var_dump($object);
