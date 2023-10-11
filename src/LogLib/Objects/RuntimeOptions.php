<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects;

    use LogLib\Abstracts\LevelType;
    use LogLib\Classes\Utilities;

    class RuntimeOptions
    {
        /**
         * Indicates if the console output is enabled
         *
         * @var bool
         * @property_name console_output
         */
        private $console_output;

        /**
         * Indicates if ANSI colors should be used in the console output
         *
         * @var bool
         * @property_name display_ansi
         */
        private $display_ansi;

        /**
         * Indicates if LogLib should handle uncaught exceptions
         *
         * @var bool
         * @property_name handle_exceptions
         */
        private $handle_exceptions;

        /**
         * The current log level
         *
         * @var int
         * @see LevelType
         */
        private $log_level;

        /**
         * Public Constructor
         */
        public function __construct()
        {
            $this->console_output = Utilities::runningInCli();
            $this->display_ansi = Utilities::getDisplayAnsi();
            $this->log_level = Utilities::getLogLevel();
            $this->handle_exceptions = true;
        }

        /**
         * @return bool
         */
        public function isConsoleOutput(): bool
        {
            return $this->console_output;
        }

        /**
         * @param bool $console_output
         */
        public function setConsoleOutput(bool $console_output): void
        {
            $this->console_output = $console_output;
        }

        /**
         * @return bool
         */
        public function displayAnsi(): bool
        {
            return $this->display_ansi;
        }

        /**
         * @param bool $display_ansi
         */
        public function setDisplayAnsi(bool $display_ansi): void
        {
            $this->display_ansi = $display_ansi;
        }

        /**
         * @return bool
         */
        public function handleExceptions(): bool
        {
            return $this->handle_exceptions;
        }

        /**
         * @param bool $handle_exceptions
         */
        public function setHandleExceptions(bool $handle_exceptions): void
        {
            $this->handle_exceptions = $handle_exceptions;
        }

        /**
         * @return int
         */
        public function getLoglevel(): int
        {
            return $this->log_level;
        }

        /**
         * @param int $log_level
         */
        public function setLoglevel(int $log_level): void
        {
            $this->log_level = $log_level;
        }
    }