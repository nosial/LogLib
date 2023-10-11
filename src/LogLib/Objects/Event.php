<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects;

    use LogLib\Abstracts\LevelType;
    use LogLib\Classes\Utilities;
    use Throwable;

    class Event
    {
        /**
         * @see LevelType
         * @var string
         */
        private $level;

        /**
         * @var Backtrace[]|null
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
         * @param int $level
         * @param Throwable|null $exception
         * @param array|null $backtrace
         */
        public function __construct(string $message, int $level, ?Throwable $exception=null, ?array $backtrace=null)
        {
            $this->message = $message;
            $this->level = $level;
            $this->exception = $exception;
            $this->backtrace = $backtrace;
        }

        /**
         * Returns the level of the event
         *
         * @see LevelType
         * @return int
         */
        public function getLevel(): int
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