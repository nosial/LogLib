<?php

namespace LogLib;

use LogLib\Exceptions\LoggingException;
use LogLib\Objects\Application;
use Throwable;

class Logger extends Application
{
    /**
     * @inheritDoc
     */
    public function __construct(string $applicationName)
    {
        parent::__construct($applicationName);
        Log::register($this, true);
    }

    /**
     * Logs an informational message.
     *
     * @param string $message The message to log.
     * @return void
     * @throws LoggingException
     */
    public function info(string $message): void
    {
        Log::info($this->getApplicationName(), $message);
    }

    /**
     * Logs a verbose message with the application name.
     *
     * @param string $message The message to be logged.
     * @return void
     * @throws LoggingException
     */
    public function verbose(string $message): void
    {
        Log::verbose($this->getApplicationName(), $message);
    }

    /**
     * Logs a debug message.
     *
     * @param string $message The debug message to log.
     * @return void
     * @throws LoggingException
     */
    public function debug(string $message): void
    {
        Log::debug($this->getApplicationName(), $message);
    }

    /**
     * Logs a warning message with the application name.
     *
     * @param string $message The warning message to log.
     * @return void
     * @throws LoggingException
     */
    public function warning(string $message): void
    {
        Log::warning($this->getApplicationName(), $message);
    }

    /**
     * Logs an error message with an optional throwable instance.
     *
     * @param string $message The error message to be logged.
     * @param Throwable|null $throwable An optional throwable instance to be logged along with the error message.
     * @return void
     * @throws LoggingException
     */
    public function error(string $message, ?Throwable $throwable=null): void
    {
        Log::error($this->getApplicationName(), $message, $throwable);
    }

    /**
     * Logs a fatal error message along with an optional throwable.
     *
     * @param string $message The fatal error message to log.
     * @param Throwable|null $throwable Optional throwable associated with the fatal error.
     * @return void
     * @throws LoggingException
     */
    public function fatal(string $message, ?Throwable $throwable=null): void
    {
        Log::fatal($this->getApplicationName(), $message, $throwable);
    }
}