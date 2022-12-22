<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects;

    use InvalidArgumentException;
    use LogLib\Abstracts\LevelType;
    use LogLib\Classes\Utilities;
    use LogLib\Objects\FileLogging\FileHandle;

    class RuntimeOptions
    {
        /**
         * Indicates if the console output is enabled
         *
         * @var bool
         * @property_name console_output
         */
        private $ConsoleOutput;

        /**
         * Indicates if ANSI colors should be used in the console output
         *
         * @var bool
         * @property_name display_ansi
         */
        private $DisplayAnsi;

        /**
         * Indicates if LogLib should handle uncaught exceptions
         *
         * @var bool
         * @property_name handle_exceptions
         */
        private $HandleExceptions;

        /**
         * Optional. The file to write the log to.
         *
         * @var string|null
         * @property_name output_log
         */
        private $OutputLog;

        /**
         * The current log level
         *
         * @var int
         * @see LevelType
         */
        private $LogLevel;

        /**
         * @var FileHandle
         */
        private $OutputLogHandle;

        /**
         * Public Constructor
         */
        public function __construct()
        {
            $this->ConsoleOutput = Utilities::runningInCli();
            $this->DisplayAnsi = Utilities::getDisplayAnsi();
            $this->HandleExceptions = true;
            $this->OutputLog = Utilities::getOutputLogPath();
            $this->LogLevel = Utilities::getLogLevel();
        }

        /**
         * @return bool
         */
        public function isConsoleOutput(): bool
        {
            return $this->ConsoleOutput;
        }

        /**
         * @param bool $ConsoleOutput
         */
        public function setConsoleOutput(bool $ConsoleOutput): void
        {
            $this->ConsoleOutput = $ConsoleOutput;
        }

        /**
         * @return bool
         */
        public function isDisplayAnsi(): bool
        {
            return $this->DisplayAnsi;
        }

        /**
         * @param bool $DisplayAnsi
         */
        public function setDisplayAnsi(bool $DisplayAnsi): void
        {
            $this->DisplayAnsi = $DisplayAnsi;
        }

        /**
         * @return bool
         */
        public function isHandleExceptions(): bool
        {
            return $this->HandleExceptions;
        }

        /**
         * @param bool $HandleExceptions
         */
        public function setHandleExceptions(bool $HandleExceptions): void
        {
            $this->HandleExceptions = $HandleExceptions;
        }

        /**
         * @return int
         */
        public function getLogLevel(): int
        {
            return $this->LogLevel;
        }

        /**
         * @param int $LogLevel
         */
        public function setLogLevel(int $LogLevel): void
        {
            $this->LogLevel = $LogLevel;
        }

        /**
         * @return ?FileHandle
         */
        public function getOutputLogHandle(): ?FileHandle
        {
            if($this->OutputLogHandle == null)
            {
                if($this->OutputLog == null)
                    return null;

                if(is_writable($this->OutputLog) === false)
                    throw new InvalidArgumentException(sprintf('The path "%s" is not writable', $this->OutputLog));

                $this->OutputLogHandle = new FileHandle($this->OutputLog);
            }

            return $this->OutputLogHandle;
        }
    }