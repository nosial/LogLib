<?php

namespace LogLib\Objects;

use InvalidArgumentException;
use LogLib\Classes\Utilities;
use LogLib\Enums\LogLevel;
use LogLib\Handlers\ConsoleLogging;
use LogLib\Handlers\FileLogging;
use LogLib\Interfaces\LogHandlerInterface;

class Application
{
    private string $applicationName;
    private bool $handleExceptions;
    private bool $consoleLoggingEnabled;
    private string $consoleLoggingHandler;
    private LogLevel $consoleLoggingLevel;
    private bool $fileLoggingEnabled;
    private string $fileLoggingHandler;
    private LogLevel $fileLoggingLevel;
    private string $fileLoggingDirectory;

    /**
     * Constructor for initializing the application logging and exception handling.
     *
     * @param string $applicationName The name of the application.
     * @return void
     */
    public function __construct(string $applicationName)
    {
        $this->applicationName = $applicationName;
        $this->handleExceptions = true;
        $this->consoleLoggingEnabled =  Utilities::getConsoleLoggingEnabled();
        $this->consoleLoggingHandler = ConsoleLogging::class;
        $this->consoleLoggingLevel = Utilities::getConsoleLoggingLevel();
        $this->fileLoggingEnabled = Utilities::getFileLoggingEnabled();
        $this->fileLoggingHandler = FileLogging::class;
        $this->fileLoggingLevel = Utilities::getFileLoggingLevel();
        $this->fileLoggingDirectory = Utilities::getFileLoggingDirectory();
    }

    /**
     *
     * @return string
     */
    public function getApplicationName(): string
    {
        return $this->applicationName;
    }

    /**
     * Checks if exceptions are being handled.
     *
     * @return bool
     */
    public function isHandleExceptions(): bool
    {
        return $this->handleExceptions;
    }

    /**
     * Sets the flag to handle exceptions.
     *
     * @param bool $handleExceptions Flag indicating whether to handle exceptions.
     * @return void
     */
    public function setHandleExceptions(bool $handleExceptions): void
    {
        $this->handleExceptions = $handleExceptions;
    }

    /**
     * Checks if console logging is enabled.
     *
     * @return bool True if console logging is enabled, false otherwise.
     */
    public function isConsoleLoggingEnabled(): bool
    {
        return $this->consoleLoggingEnabled;
    }

    /**
     *
     * @param bool $consoleLoggingEnabled Indicates whether console logging is enabled.
     * @return void
     */
    public function setConsoleLoggingEnabled(bool $consoleLoggingEnabled): void
    {
        $this->consoleLoggingEnabled = $consoleLoggingEnabled;
    }

    /**
     * Gets the current console logging handler.
     *
     * @return LogHandlerInterface|string The console logging handler currently set.
     */
    public function getConsoleLoggingHandler(): LogHandlerInterface|string
    {
        return new $this->consoleLoggingHandler;
    }

    /**
     * Sets the console logging handler.
     *
     * @param LogHandlerInterface|string $consoleLoggingHandler The console logging handler to set.
     * @return void
     */
    public function setConsoleLoggingHandler(LogHandlerInterface|string $consoleLoggingHandler): void
    {
        if($consoleLoggingHandler instanceof LogHandlerInterface)
        {
            $this->consoleLoggingHandler = get_class($consoleLoggingHandler);
            return;
        }

        if(!class_exists($consoleLoggingHandler))
        {
            throw new InvalidArgumentException("The class $consoleLoggingHandler does not exist.");
        }

        if(!in_array(LogHandlerInterface::class, class_implements($consoleLoggingHandler)))
        {
            throw new InvalidArgumentException("The class $consoleLoggingHandler does not implement LogHandlerInterface.");
        }

        $this->consoleLoggingHandler = $consoleLoggingHandler;
    }

    /**
     * Gets the current console logging level.
     *
     * @return LogLevel The current logging level for the console.
     */
    public function getConsoleLoggingLevel(): LogLevel
    {
        return $this->consoleLoggingLevel;
    }

    /**
     * Sets the logging level for console output.
     *
     * @param LogLevel $consoleLoggingLevel The logging level to set for console output.
     * @return void
     */
    public function setConsoleLoggingLevel(LogLevel $consoleLoggingLevel): void
    {
        $this->consoleLoggingLevel = $consoleLoggingLevel;
    }

    /**
     * Checks if file logging is enabled.
     *
     * @return bool Returns true if file logging is enabled, false otherwise.
     */
    public function isFileLoggingEnabled(): bool
    {
        return $this->fileLoggingEnabled;
    }

    /**
     * Enables or disables file logging.
     *
     * @param bool $fileLoggingEnabled True to enable file logging, false to disable.
     * @return void
     */
    public function setFileLoggingEnabled(bool $fileLoggingEnabled): void
    {
        $this->fileLoggingEnabled = $fileLoggingEnabled;
    }

    /**
     * @return LogHandlerInterface|string
     */
    public function getFileLoggingHandler(): LogHandlerInterface|string
    {
        return $this->fileLoggingHandler;
    }

    /**
     * Sets the file logging handler.
     *
     * @param string $fileLoggingHandler The file logging handler to be set.
     * @return void
     */
    public function setFileLoggingHandler(string $fileLoggingHandler): void
    {
        if($fileLoggingHandler instanceof LogHandlerInterface)
        {
            $this->consoleLoggingHandler = get_class($fileLoggingHandler);
            return;
        }

        if(!class_exists($fileLoggingHandler))
        {
            throw new InvalidArgumentException("The class $fileLoggingHandler does not exist.");
        }

        if(!in_array(LogHandlerInterface::class, class_implements($fileLoggingHandler)))
        {
            throw new InvalidArgumentException("The class $fileLoggingHandler does not implement LogHandlerInterface.");
        }

        $this->consoleLoggingHandler = $fileLoggingHandler;
    }

    /**
     * Retrieves the logging level for file outputs.
     *
     * @return LogLevel The current file logging level.
     */
    public function getFileLoggingLevel(): LogLevel
    {
        return $this->fileLoggingLevel;
    }

    /**
     * Sets the logging level for file-based logging.
     *
     * @param LogLevel $fileLoggingLevel The logging level to set for file logging.
     * @return void
     */
    public function setFileLoggingLevel(LogLevel $fileLoggingLevel): void
    {
        $this->fileLoggingLevel = $fileLoggingLevel;
    }

    /**
     * Get the directory path for file logging.
     *
     * @return string The directory path where log files are stored.
     */
    public function getFileLoggingDirectory(): string
    {
        return $this->fileLoggingDirectory;
    }

    /**
     * Sets the directory for file logging.
     *
     * @param string $fileLoggingDirectory The path to the directory where log files will be stored.
     * @return void
     */
    public function setFileLoggingDirectory(string $fileLoggingDirectory): void
    {
        $this->fileLoggingDirectory = $fileLoggingDirectory;
    }
}