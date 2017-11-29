<?php
require 'vendor/autoload.php';
use Aws\Sdk;

$config = [
    'version'   => 'latest',
    'region'    =>  'us-east-2'
];
$sdk = new Sdk($config);
$client = $sdk->createS3();
echo '<hr><pre>';
echo 'File :: ' . __FILE__ . ' (Line ' . __LINE__ . ')' . PHP_EOL;
var_dump($client->listBuckets());
echo '</pre><hr>';
exit();