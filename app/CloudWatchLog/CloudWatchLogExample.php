<?php
namespace CloudWatchLog;

use Aws\Sdk;

class CloudWatchLogExample
{

    /**
     * @var \Aws\CloudWatchLogs\CloudWatchLogsClient|null
     */
    protected $client = null;

    /**
     * @var null
     */
    protected $nextSequenceToken = null;

    /**
     * @var string
     */
    protected $logGroupName = 'logGroup_example';

    /**
     * @var array
     */
    protected $tags = ['example' => 'logGroup'];

    /**
     * @var string
     */
    protected $logStream = '' ;

    /**
     * @var null
     */
    protected static $instance = null;

    /**
     * @param Sdk $sdk
     * @return CloudWatchLogExample
     */
    static function getInstance(Sdk $sdk){
        if (self::$instance == null){
            self::$instance = new self($sdk);
        }
        return self::$instance;
    }

    /**
     * CloudWatchLogExample constructor.
     * @param Sdk $sdk
     */
    protected function __construct(Sdk $sdk)
    {
        try {
            $this->client = $sdk->createCloudWatchLogs();
            $this->createLogGroup();
            $this->createLogStream();
        } catch (\Exception $e){
            var_dump($e->getMessage());
        }
    }

    /**
     * @param String $message
     * @return \Aws\Result
     */
    public function putLogs(String $message){
        try{
            $config = [
                'logEvents' => [
                    [
                        'message' => $message,
                        'timestamp' => round(microtime(true) * 1000),
                    ],
                ],
                'logGroupName' => $this->logGroupName,
                'logStreamName' => $this->logStream
            ];

            if ($this->nextSequenceToken){
                $config['sequenceToken'] = $this->nextSequenceToken;
            }
            var_dump($config);
            $result = $this->client->putLogEvents($config);
            $this->nextSequenceToken = $result->get('nextSequenceToken');
            return $result;
        } catch (\Exception $e){
            var_dump($e->getMessage());
        }
    }

    protected function createLogGroup(){
        if (!apcu_exists('LogGroupCreated')){
            $result = $this->client->createLogGroup([
                'logGroupName' => $this->logGroupName,
                'tags' => $this->tags,
            ]);
            if ($result->get('statusCode') == '200'){
                apcu_add('LogGroupCreated', true);
            }
        }
    }

    protected function createLogStream(){
        $this->logStream = date("Y/m/d/") . '[$LATEST]' . md5(date("H:i:s:u"));
        $this->client->createLogStream([
            'logGroupName' => $this->logGroupName,
            'logStreamName' => $this->logStream,
        ]);
    }
}