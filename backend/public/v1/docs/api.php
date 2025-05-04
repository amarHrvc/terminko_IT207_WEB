<?php
require($_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php");
$openapi = \OpenApi\Generator::scan([$_SERVER['DOCUMENT_ROOT'] . '/../app/Controller']);
header('Content-Type: application/jsonl');
echo $openapi->toJSON();