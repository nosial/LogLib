<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects;

    use InvalidArgumentException;
    use LogLib\Classes\Validate;
    use LogLib\Interfaces\HandlerInterface;
    use LogLib\Objects\FileLogging\FileHandle;
    use ncc\Exceptions\InvalidPackageNameException;
    use ncc\Exceptions\InvalidScopeException;
    use ncc\Exceptions\PackageLockException;
    use ncc\Managers\PackageLockManager;

    class Options
    {
        /**
         * The name of the application
         *
         * @var string
         * @property_name application_name
         */
        private $ApplicationName;

        /**
         * Writes the log to a file located at the package data path provided by NCC's API
         * under a "logs" directory.
         *
         * @var bool
         * @property_name write_to_package_data
         */
        private $WriteToPackageData;

        /**
         * An array of handlers that wil be used to handle the log events
         * if applications want to handle the log events themselves.
         *
         * @var HandlerInterface[]
         */
        private $Handlers;

        /**
         * The file handle to write the log to if WriteToPackageData is true
         *
         * @var FileHandle|null
         */
        private $FileHandle;

        /**
         * @var string|null
         */
        private $PackageDataPath;

        /**
         * @var bool
         */
        private $DumpExceptions;

        /**
         * Options constructor.
         */
        public function __construct(string $application_name)
        {
            $this->ApplicationName = $application_name;
            $this->WriteToPackageData = false;
            $this->DumpExceptions = false;
            $this->Handlers = [];
        }

        /**
         * @return bool
         */
        public function writeToPackageData(): bool
        {
            return $this->WriteToPackageData;
        }

        /**
         * Enables the writing of the log to a file located at the package data path provided by NCC's API
         *
         * @return void
         * @throws InvalidPackageNameException
         * @throws InvalidScopeException
         * @throws PackageLockException
         */
        public function enableWriteToPackageData(): void
        {
            if($this->WriteToPackageData)
                return;

            $package_lock = new PackageLockManager();
            $package = $package_lock->getPackageLock()->getPackage($this->ApplicationName);
            if($package == null)
                throw new InvalidArgumentException("The package data path could not be found for the package '{$this->ApplicationName}'");

            $this->WriteToPackageData = true;
            $this->PackageDataPath = $package->getDataPath();
            if($this->FileHandle !== null)
                unset($this->FileHandle);

            $this->FileHandle = new FileHandle($this->PackageDataPath);
        }

        /**
         * Disables the writing of the log to the package data path
         *
         * @return void
         */
        public function disableWriteToPackageData(): void
        {
            $this->WriteToPackageData = false;
            $this->PackageDataPath = null;
            unset($this->FileHandle);
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
         * Returns the name of the Application
         *
         * @return string
         */
        public function getApplicationName(): string
        {
            return $this->ApplicationName;
        }

        /**
         * Indicates if exceptions should be dumped to a file
         *
         * @return bool
         */
        public function dumpExceptionsEnabled(): bool
        {
            return $this->DumpExceptions;
        }

        /**
         * Enables/Disables the dumping of exceptions to the /exceptions folder of the package data path
         * WriteToPackageData must be enabled for this to work properly
         *
         * @param bool $DumpExceptions
         */
        public function setDumpExceptions(bool $DumpExceptions): void
        {
            if(!$this->WriteToPackageData)
                throw new InvalidArgumentException('Cannot dump exceptions if WriteToPackageData is disabled');
            $this->DumpExceptions = $DumpExceptions;
        }

        /**
         * @return FileHandle|null
         */
        public function getFileHandle(): ?FileHandle
        {
            return $this->FileHandle;
        }

        /**
         * @return string|null
         */
        public function getPackageDataPath(): ?string
        {
            return $this->PackageDataPath;
        }

    }