<?php

    namespace LogLib\Exceptions;

    use Exception;
    use Throwable;

    class ConfigurationException extends Exception
    {
        /**
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
            $this->message = $message;
            $this->code = $code;
        }
    }