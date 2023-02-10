<?php

    namespace LogLib;

    use Psr\Log\LoggerInterface;
    use Psr\Log\LogLevel;

    class Psr implements LoggerInterface
    {
        /**
         * The name of the application
         *
         * @var string
         */
        private string $application;

        /**
         * Public Constructor
         *
         * @param string $application
         */
        public function __construct(string $application)
        {
            $this->application = $application;
        }

        /**
         * Handles the emergency log level
         *
         * @param $message
         * @param array $context
         * @return void
         */
        public function emergency($message, array $context = array()): void
        {
            Log::fatal($this->application, $message);
        }

        /**
         * Handles the alert log level
         *
         * @param $message
         * @param array $context
         * @return void
         */
        public function alert($message, array $context = array()): void
        {
            Log::warning($this->application, $message);
        }

        /**
         * Handles the critical log level
         *
         * @param $message
         * @param array $context
         * @return void
         */
        public function critical($message, array $context = array()): void
        {
            Log::fatal($this->application, $message);
        }

        /**
         * Handles the error log level
         *
         * @param $message
         * @param array $context
         * @return void
         */
        public function error($message, array $context = array()): void
        {
            Log::error($this->application, $message);
        }

        /**
         * Handles the warning log level
         *
         * @param $message
         * @param array $context
         * @return void
         */
        public function warning($message, array $context = []): void
        {
            Log::warning($this->application, $message);
        }

        /**
         * Handles the notice log level
         *
         * @param $message
         * @param array $context
         * @return void
         */
        public function notice($message, array $context = array()): void
        {
            Log::info($this->application, $message);
        }

        /**
         * Handles the info log level
         *
         * @param $message
         * @param array $context
         * @return void
         */
        public function info($message, array $context = array()): void
        {
            Log::info($this->application, $message);
        }

        /**
         * Handles the debug log level
         *
         * @param $message
         * @param array $context
         * @return void
         */
        public function debug($message, array $context = array()): void
        {
            Log::debug($this->application, $message);
        }

        /**
         * Handles a logging event
         *
         * @param $level
         * @param $message
         * @param array $context
         * @return void
         */
        public function log($level, $message, array $context = array()): void
        {
            switch($level)
            {
                case LogLevel::CRITICAL:
                case LogLevel::ALERT:
                case LogLevel::EMERGENCY:
                    Log::fatal($this->application, $message);
                    break;
                case LogLevel::ERROR:
                    Log::error($this->application, $message);
                    break;
                case LogLevel::WARNING:
                    Log::warning($this->application, $message);
                    break;
                case LogLevel::INFO:
                case LogLevel::NOTICE:
                    Log::info($this->application, $message);
                    break;
                case LogLevel::DEBUG:
                    Log::debug($this->application, $message);
                    break;
            }
        }
    }