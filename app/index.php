<?php
require 'vendor/autoload.php';
use Aws\Sdk;

try {
    $aws = parse_ini_file('aws.ini');
    $key = $aws['aws_access_key_id'];
    $secret = $aws['aws_secret_access_key'];
    $credentials = new Aws\Credentials\Credentials($key, $secret);

    $config = [
        'version' => 'latest',
        'region' => 'us-east-2',
        'credentials' => $credentials
    ];
    $sdk = new Sdk($config);
    $client = $sdk->createCloudWatchLogs();
    $client->createLogGroup([
        'logGroupName' => 'test_1', // REQUIRED
        'tags' => ['test' => '1'],
    ]);

    $client->createLogStream([
        'logGroupName' => 'test_1', // REQUIRED
        'logStreamName' => 'test_1a', // REQUIRED
    ]);

    $response = $client->putLogEvents([
        'logEvents' => [ // REQUIRED
                [
                    'message' => 'first log', // REQUIRED
                    'timestamp' => round(microtime(true) * 1000), // REQUIRED
        ],
        // ...
    ],
    'logGroupName' => 'test_1', // REQUIRED
    'logStreamName' => 'test_1a', // REQUIRED
]);
    var_dump($response);
} catch (Aws\CloudWatchLogs\Exception\CloudWatchLogsException $e){
    switch ($e->getAwsErrorCode()){
        case 'ResourceAlreadyExistsException':
            var_dump($e->getAwsErrorMessage());
    }
}