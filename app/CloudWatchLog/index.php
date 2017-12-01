<?php
require '/app/vendor/autoload.php';
use Aws\Sdk;

try {
    $config = [
        'profile' => 'default',
        'version' => 'latest',
        'region' => 'us-east-2'
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
            break;
        case 'InvalidSignatureException':
            var_dump($e->getAwsErrorMessage());
            break;
        default:
            var_dump($e->getAwsErrorCode());
            break;
    }
} catch (Exception $e){
    var_dump($e->getMessage());
}