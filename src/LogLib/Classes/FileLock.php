<?php
    namespace LogLib\Classes;
    use RuntimeException;

    /**
     * Class FileLock
     *
     * This class provides functionalities to safely work with file locks and ensures
     * that concurrent write operations do not overwrite each other.
     * It offers methods to lock, unlock, and append data to a file.
     */
    class FileLock
    {
        private $fileHandle;
        private string $filePath;
        private int $retryInterval; // in microseconds
        private int $confirmationInterval; // in microseconds

        /**
         * Constructor for FileLock.
         *
         * @param string $filePath Path to the file.
         * @param int $retryInterval Time to wait between retries (in microseconds).
         * @param int $confirmationInterval Time to wait before double confirmation (in microseconds).
         */
        public function __construct(string $filePath, int $retryInterval=100000, int $confirmationInterval=50000)
        {
            $this->filePath = $filePath;
            $this->retryInterval = $retryInterval;
            $this->confirmationInterval = $confirmationInterval;

            // Create the file if it doesn't exist
            if (!file_exists($filePath))
            {
                $this->fileHandle = fopen($filePath, 'w');
                fclose($this->fileHandle);
            }
        }

        /**
         * Locks the file.
         *
         * @throws RuntimeException if unable to open or lock the file.
         */
        private function lock(): bool
        {
            $this->fileHandle = @fopen($this->filePath, 'a');
            if ($this->fileHandle === false)
            {
                return false;
            }

            // Keep trying to acquire the lock until it succeeds
            while (!flock($this->fileHandle, LOCK_EX))
            {
                usleep($this->retryInterval); // Wait for the specified interval before trying again
            }

            // Double confirmation
            usleep($this->confirmationInterval); // Wait for the specified confirmation interval
            if (!flock($this->fileHandle, LOCK_EX | LOCK_NB))
            {
                // If the lock cannot be re-acquired, release the current lock and retry
                flock($this->fileHandle, LOCK_UN);
                $this->lock();
            }

            return true;
        }

        /**
         * Unlocks the file after performing write operations.
         */
        private function unlock(): void
        {
            if ($this->fileHandle !== null)
            {
                flock($this->fileHandle, LOCK_UN); // Release the lock
                fclose($this->fileHandle); // Close the file handle
                $this->fileHandle = null; // Reset the file handle
            }
        }

        /**
         * Appends data to the file.
         *
         * @param string $data Data to append.
         * @throws RuntimeException if unable to write to the file.
         */
        public function append(string $data): void
        {
            if(!$this->lock())
            {
                // Do not proceed if the file cannot be locked
                return;
            }

            if ($this->fileHandle !== false)
            {
                if (fwrite($this->fileHandle, $data) === false)
                {
                    throw new RuntimeException("Unable to write to the file: " . $this->filePath);
                }
            }

            $this->unlock();
        }

        /**
         * Destructor to ensure the file handle is closed.
         */
        public function __destruct()
        {
            if ($this->fileHandle)
            {
                fclose($this->fileHandle);
            }
        }
    }