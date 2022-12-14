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
        public $Backtrace;

        /**
         * The exception that was thrown, if any
         *
         * @var array|null
         */
        public $Exception;

        /**
         * The message of the event
         *
         * @var string
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
         * Returns an array representation of the event
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                'level' => ($this->Level ?? null),
                'timestamp' => ($this->Timestamp ?? null),
                'backtrace' => $this->Backtrace,
                'exception' => $this->Exception,
                'message' => ($this->Message ?? null)
            ];
        }

        /**
         * Constructs a new event from an array representation
         *
         * @param array $data
         * @return Event
         */
        public static function fromArray(array $data): Event
        {
            $event = new Event();
            $event->Level = ($data['level'] ?? null);
            $event->Timestamp = ($data['timestamp'] ?? null);
            $event->Backtrace = ($data['backtrace'] ?? null);
            $event->Exception = ($data['exception'] ?? null);
            $event->Message = ($data['message'] ?? null);
            return $event;
        }

        /**
         * @return string
         */
        public function getTimestamp(): string
        {
            return $this->Timestamp;
        }

    }