<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects;

    use LogLib\Abstracts\LevelType;
    use LogLib\Classes\Utilities;
    use Throwable;
    class Event
    {
        /**
         * The level of the event
         *
         * @see LevelType
         * @var string
         * @property_name level
         */
        public $Level;

        /**
         * The Unix Timestamp of when the event was created
         *
         * @var string
         */
        private $Timestamp;

        /**
         * An array of backtraces, if any, that were created when the event was created
         *
         * @var Backtrace[]|null
         */
        private $Backtrace;

        /**
         * The exception that was thrown, if any
         *
         * @var Throwable|null
         */
        public $Exception;

        /**
         * The message of the event
         *
         * @var string
         */
        public $Message;

        /**
         * Public Constructor
         */
        public function __construct()
        {
            $this->Timestamp = date('c');
        }

        /**
         * Sets an exception to the event
         *
         * @param Throwable $e
         * @return void
         */
        public function setException(Throwable $e): void
        {
            $this->Exception = Utilities::exceptionToArray($e);
        }

        /**
         * @return string
         */
        public function getTimestamp(): string
        {
            return $this->Timestamp;
        }

        /**
         * @return array|null
         */
        public function getBacktrace(): ?array
        {
            return $this->Backtrace;
        }

        /**
         * @param array|null $Backtrace
         */
        public function setBacktrace(?array $Backtrace): void
        {
            $this->Backtrace = $Backtrace;
        }

    }