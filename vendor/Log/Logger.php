<?php

namespace vendor\Log;

class Logger extends AbstractLogger
{
    /**
     * Path to log file
     * @var string
     */
    private $pathToFile;

    /**
     * Flag (print message on screen or not)
     * @var bool
     */
    private $loggedOnScreen;

    /**
     * Logger constructor.
     *
     * @param bool $loggedOnScreen set true if you want output message on screen.
     * @param bool $loggedInFile set true if you want output message in log file (log file path set in config file ['pathToLogFile' => 'path/to/log/file']).
     * @param string $datetime set your time zone.
     */
    public  function __construct($loggedOnScreen = true, $loggedInFile = false, $datetime = 'Europe/Kiev')
    {
        date_default_timezone_set($datetime);

        if($loggedInFile) {
            $this->pathToFile = require '/config/app.php';
            $this->pathToFile = $this->pathToFile['pathToLogFile'];
        } else {
            $this->pathToFile = false;
        }

        $this->loggedOnScreen = $loggedOnScreen;
    }

    /**
     * This method print message on screen or in the log file
     * @param $level
     * @param $message
     * @param array $context
     */
    public function log($level, $message, $context = [])
    {
        $message = "$level"  . $this->messageBuild($message, $context);

        if($this->loggedOnScreen) {
            print '<br>' . str_replace($level, "<strong> $level : </strong>", $message) . '<br>';
        }

        if($this->pathToFile) {
            $logFile = fopen($this->pathToFile, 'a');
            fwrite($logFile, date_format(new \DateTime('now'), 'Y-m-d H:i:s') . ' : ' . $message  . PHP_EOL);
            fclose($logFile);
        }
    }

    /**
     * Replace in message placeholder on context
     * @param $message
     * @param array $context
     * @return mixed
     */
    public function messageBuild($message, $context = []) 
    {
        if(empty($context)) {
            return $message;
        }

        foreach ($context as $key=>$value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }

        return $message;
    }
}