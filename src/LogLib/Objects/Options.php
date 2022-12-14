<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects;

    use InvalidArgumentException;
    use LogLib\Abstracts\LevelType;
    use LogLib\Classes\Validate;
    use LogLib\Interfaces\HandlerInterface;

    class Options
    {
        /**
         * The name of the application
         *
         * @var string
         */
        private $ApplicationName;

        /**
         * The name of the NCC package that is using LogLib (eg; com.example.package)
         *
         * @var string|null
         */
        private $PackageName;

        /**
         * The current output level of the logger, anything below this level will not be logged
         *
         * @see LevelType
         * @var string
         */
        private $OutputLevel;

        /**
         * Indicates whether the log should be written to the console or not.
         *
         * @var bool
         */
        private $ConsoleOutput;

        /**
         * Indicates whether ansi colors should be used in the console output.
         *
         * @var bool
         */
        private $ConsoleAnsiColors;

        /**
         * Writes the log to a file located at the package data path provided by NCC's API
         * under a "logs" directory.
         *
         * @var bool
         */
        private $WriteToPackageData;

        /**
         * Indicates whether the log should be split into different files based on the file size.
         * Only applies if WriteToPackageData is true.
         *
         * @var bool
         */
        private $SplitFiles;

        /**
         * The maximum size of a log file before it is split into a new file.
         * Only applies if WriteToPackageData is true.
         *
         * @var int
         */
        private $MaxFileSize;

        /**
         * An array of handlers that wil be used to handle the log events
         * if applications want to handle the log events themselves.
         *
         * @var HandlerInterface[]
         */
        private $Handlers;

        /**
         * Options constructor.
         */
        public function __construct(string $application_name)
        {
            $this->ApplicationName = $application_name;
            $this->WriteToPackageData = true;
            $this->SplitFiles = true;
            $this->MaxFileSize = 1073741824; // 1GB
            $this->OutputLevel = LevelType::Info;
            $this->ConsoleOutput = true;
            $this->ConsoleAnsiColors = true;
            $this->Handlers = [];
        }

        /**
         * @return string|null
         */
        public function getPackageName(): ?string
        {
            return $this->PackageName;
        }

        /**
         * @param string|null $PackageName
         */
        public function setPackageName(?string $PackageName): void
        {
            $this->PackageName = $PackageName;
        }

        /**
         * @return string
         */
        public function getOutputLevel(): string
        {
            return $this->OutputLevel;
        }

        /**
         * @param string $OutputLevel
         */
        public function setOutputLevel(string $OutputLevel): void
        {
            if(!in_array($OutputLevel, LevelType::All))
                throw new InvalidArgumentException("Invalid output level provided");
            $this->OutputLevel = $OutputLevel;
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
        public function isConsoleAnsiColors(): bool
        {
            return $this->ConsoleAnsiColors;
        }

        /**
         * @param bool $ConsoleAnsiColors
         */
        public function setConsoleAnsiColors(bool $ConsoleAnsiColors): void
        {
            $this->ConsoleAnsiColors = $ConsoleAnsiColors;
        }

        /**
         * @return bool
         */
        public function isWriteToPackageData(): bool
        {
            return $this->WriteToPackageData;
        }

        /**
         * @param bool $WriteToPackageData
         */
        public function setWriteToPackageData(bool $WriteToPackageData): void
        {
            $this->WriteToPackageData = $WriteToPackageData;
        }

        /**
         * @return bool
         */
        public function isSplitFiles(): bool
        {
            return $this->SplitFiles;
        }

        /**
         * @param bool $SplitFiles
         */
        public function setSplitFiles(bool $SplitFiles): void
        {
            $this->SplitFiles = $SplitFiles;
        }

        /**
         * @return int
         */
        public function getMaxFileSize(): int
        {
            return $this->MaxFileSize;
        }

        /**
         * @param int $MaxFileSize
         */
        public function setMaxFileSize(int $MaxFileSize): void
        {
            if($MaxFileSize < 1)
                throw new InvalidArgumentException("Max file size must be greater than 0");

            $this->MaxFileSize = $MaxFileSize;
        }

        /**
         * @return HandlerInterface[]
         */
        public function getHandlers(): array
        {
            return $this->Handlers;
        }

        /**
         * @param string $level
         * @param HandlerInterface $handler
         */
        public function setHandler(string $level, HandlerInterface $handler): void
        {
            if(!Validate::LevelType($level))
                throw new InvalidArgumentException("Invalid level provided");

            if(!isset($this->Handlers[$level]))
                $this->Handlers[$level] = [];

            $this->Handlers[$level][] = $handler;
        }

        /**
         * @return void
         */
        public function clearHandlers(): void
        {
            $this->Handlers = [];
        }

        /**
         * @return string
         */
        public function getApplicationName(): string
        {
            return $this->ApplicationName;
        }
    }