<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects;

    use LogLib\Classes\Utilities;
    use LogLib\Enums\LogLevel;

    class RuntimeOptions
    {
        private $consoleOutput;
        private bool $displayAnsi;
        private bool $handleExceptions;
        private LogLevel $logLevel;
        private bool $fileLoggingEnabled;
        private LogLevel $fileLoggingLevel;

        /**
         *
         * @return void
         */
        public function __construct()
        {
            $this->consoleOutput = Utilities::runningInCli();
            $this->displayAnsi = Utilities::getDisplayAnsi();
            $this->logLevel = Utilities::getLogLevel();
            $this->fileLoggingEnabled = true;
            $this->fileLoggingLevel = LogLevel::ERROR;
            $this->handleExceptions = true;
        }

        /**
         * Checks if console output is enabled.
         *
         * @return bool Returns true if console output is enabled, false otherwise.
         */
        public function isConsoleOutput(): bool
        {
            return $this->consoleOutput;
        }

        /**
         * Set the console output flag.
         *
         * @param bool $consoleOutput Indicates whether to enable or disable console output.
         * @return void
         */
        public function setConsoleOutput(bool $consoleOutput): void
        {
            $this->consoleOutput = $consoleOutput;
        }

        /**
         * Determines if ANSI display is enabled.
         *
         * @return bool Returns true if ANSI display is enabled, false otherwise.
         */
        public function displayAnsi(): bool
        {
            return $this->displayAnsi;
        }

        /**
         * Sets whether to display ANSI colors in the console output.
         *
         * @param bool $displayAnsi A boolean value indicating whether ANSI colors should be displayed.
         * @return void
         */
        public function setDisplayAnsi(bool $displayAnsi): void
        {
            $this->displayAnsi = $displayAnsi;
        }

        /**
         * Get the flag indicating whether exceptions are being handled.
         *
         * @return bool True if exceptions are being handled
         */
        public function handleExceptions(): bool
        {
            return $this->handleExceptions;
        }

        /**
         * Set the exception handling behavior.
         *
         * @param bool $handleExceptions A boolean value indicating whether to handle exceptions.
         * @return void
         */
        public function setHandleExceptions(bool $handleExceptions): void
        {
            $this->handleExceptions = $handleExceptions;
        }

        /**
         * Returns the current log level.
         *
         * @return LogLevel The current log level.
         */
        public function getLoglevel(): LogLevel
        {
            return $this->logLevel;
        }

        /**
         * Sets the log level for logging operations.
         *
         * @param LogLevel $logLevel The log level to be set.
         * @return void
         */
        public function setLoglevel(LogLevel $logLevel): void
        {
            $this->logLevel = $logLevel;
        }

        /**
         * Checks if file logging is enabled.
         *
         * @return bool True if file logging is enabled, false otherwise.
         */
        public function isFileLoggingEnabled(): bool
        {
            return $this->fileLoggingEnabled;
        }

        /**
         * Enables or disables file logging.
         *
         * @param bool $fileLoggingEnabled Indicates whether file logging should be enabled.
         * @return void
         */
        public function setFileLoggingEnabled(bool $fileLoggingEnabled): void
        {
            $this->fileLoggingEnabled = $fileLoggingEnabled;
        }

        /**
         * Gets the current file logging level.
         *
         * @return LogLevel The file logging level.
         */
        public function getFileLoggingLevel(): LogLevel
        {
            return $this->fileLoggingLevel;
        }

        /**
         * Set the logging level for file output.
         *
         * @param LogLevel $fileLoggingLevel The logging level to be used for file output.
         * @return void
         */
        public function setFileLoggingLevel(LogLevel $fileLoggingLevel): void
        {
            $this->fileLoggingLevel = $fileLoggingLevel;
        }
    }