<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects\FileLogging;

    use InvalidArgumentException;
    use LogLib\Classes\Utilities;

    class FileHandle
    {
        /**
         * The file handle of the file
         *
         * @var resource
         */
        private $resource;

        /**
         * The current out path where all the logs are being written to
         *
         * @var string
         */
        private $path;

        /**
         * The current file path of the log file
         *
         * @var string
         */
        private $current_file;

        /**
         * Public constructor
         *
         * @param string $path
         */
        public function __construct(string $path)
        {
            if(is_writable($path) === false)
                throw new InvalidArgumentException(sprintf('The path "%s" is not writable', $path));

            $this->path = $path . DIRECTORY_SEPARATOR . 'logs';
            $this->current_file = Utilities::getLogFilename();

            if(!file_exists($this->current_file))
            {
                touch($this->current_file);
                chmod($this->current_file, 0777);
            }

            $this->resource = fopen($this->path . DIRECTORY_SEPARATOR . $this->current_file, 'a');

            if(!is_dir($this->path))
                mkdir($this->path, 0777, true);
        }

        /**
         * Writes to the file
         *
         * @param string $string
         * @return int
         */
        public function fwrite(string $string): int
        {
            $current_file = Utilities::getLogFilename();

            if ($current_file !== $this->current_file)
            {
                fclose($this->resource);
                $this->current_file = $current_file;
                if(!file_exists($this->current_file))
                {
                    touch($this->current_file);
                    chmod($this->current_file, 0777);
                }

                $this->resource = fopen($this->current_file, 'a');
            }

            return fwrite($this->resource, $string);
        }

        /**
         * Closes the file handle
         */
        public function __destruct()
        {
            fclose($this->resource);
        }

        /**
         * @return false|resource
         */
        public function resource()
        {
            return $this->resource;
        }
    }