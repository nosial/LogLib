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
         * @property_name timestamp
         */
        private $Timestamp;

        /**
         * An array of backtraces, if any, that were created when the event was created
         *
         * @var Backtrace[]|null
         * @property_name backtrace
         */
        public $Backtrace;

        /**
         * The exception that was thrown, if any
         *
         * @var array|null
         * @property_name exception
         */
        public $Exception;

        /**
         * The message of the event
         *
         * @var string
         * @property_name message
         */
        public $Message;

        public function __construct()
        {
            $this->Timestamp = date('Y-m-dTH:i:s.v') . (date('p') == 'Z' ? 'Z' : 'L');
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

    }