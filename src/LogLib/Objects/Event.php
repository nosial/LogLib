<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects;

    use LogLib\Classes\Utilities;
    use LogLib\Enums\LogLevel;
    use Throwable;

    class Event
    {
        /**
         * @var LogLevel
         */
        private $level;

        /**
         * @var array|null
         */
        private $backtrace;

        /**
         * @var Throwable|null
         */
        private $exception;

        /**
         * @var string
         */
        private $message;

        /**
         * Event constructor.
         *
         * @param string $message
         * @param LogLevel $level
         * @param Throwable|null $exception
         * @param array|null $backtrace
         */
        public function __construct(string $message, LogLevel $level, ?Throwable $exception=null, ?array $backtrace=null)
        {
            $this->message = $message;
            $this->level = $level;
            $this->exception = $exception;
            $this->backtrace = $backtrace;
        }

        /**
         * Returns the level of the event
         *
         * @return LogLevel
         * @see LogLevel
         */
        public function getLevel(): LogLevel
        {
            return $this->level;
        }

        /**
         * Returns the message of the event
         *
         * @return string
         */
        public function getMessage(): string
        {
            return $this->message;
        }

        /**
         * Optional. Returns the exception to the event
         *
         * @return Throwable|null
         */
        public function getException(): ?Throwable
        {
            return $this->exception;
        }

        /**
         * Sets an exception to the event
         *
         * @param Throwable $e
         * @return void
         */
        public function setException(Throwable $e): void
        {
            $this->exception = Utilities::exceptionToArray($e);
        }

        /**
         * Returns the backtrace of the event
         *
         * @return array|null
         */
        public function getBacktrace(): ?array
        {
            return $this->backtrace;
        }

        /**
         * Sets the backtrace of the event
         *
         * @param array|null $backtrace
         */
        public function setBacktrace(?array $backtrace): void
        {
            $this->backtrace = $backtrace;
        }
    }