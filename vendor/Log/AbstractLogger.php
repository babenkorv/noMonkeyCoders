<?php

namespace vendor\Log;

/**
 * Class AbstractLogger
 *
 * Implement all default logging methods 
 *
 * @package vendor\Log
 */
abstract class AbstractLogger implements LoggerInterface
{
    abstract public function log($level, $message, $context = []);

    /**
     * Call this method if level logging message is emergency
     * 
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function emergency($message, array $context = [])
    {
        return $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Call this method if level logging message is alert
     *
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function alert($message, array $context = [])
    {
        return $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Call this method if level logging message is critical
     *
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function critical($message, array $context = [])
    {
        return $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Call this method if level logging message is error
     *
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function error($message, array $context = [])
    {
        return $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Call this method if level logging message is warning
     *
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function warning($message, array $context = [])
    {
        return $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Call this method if level logging message is notice
     *
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function notice($message, array $context = [])
    {
        return $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Call this method if level logging message is info
     *
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function info($message, array $context = [])
    {
        return $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Call this method if level logging message is debug
     *
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function debug($message, array $context = [])
    {
        return $this->log(LogLevel::DEBUG, $message, $context);
    }
}