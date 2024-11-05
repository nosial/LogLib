<?php

    namespace LogLib\Classes;

    class BacktraceParser
    {
        /**
         * Determines if the given backtrace originates from the exception handler.
         *
         * @param array $backtrace The backtrace array to inspect.
         * @return bool Returns true if the backtrace originates from the exception handler within LogLib\Runtime class, false otherwise.
         */
        public static function fromExceptionHandler(array $backtrace): bool
        {
            /** @var array $trace */
            foreach($backtrace as $trace)
            {
                if(!isset($trace['function']) || $trace['function'] != 'exceptionHandler')
                {
                    continue;
                }

                if(!isset($trace['class']) || $trace['class'] != 'LogLib\Runtime')
                {
                    continue;
                }

                return true;
            }

            return false;
        }

        /**
         * Determines if the given backtrace originates from the error handler.
         *
         * @param array $backtrace The backtrace array to inspect.
         * @return bool Returns true if the backtrace originates from the error handler within LogLib\Runtime class, false otherwise.
         */
        public static function fromErrorHandler(array $backtrace): bool
        {
            /** @var array $trace */
            foreach($backtrace as $trace)
            {
                if(!isset($trace['function']) || $trace['function'] != 'errorHandler')
                {
                    continue;
                }

                if(!isset($trace['class']) || $trace['class'] != 'LogLib\Runtime')
                {
                    continue;
                }

                return true;
            }

            return false;
        }

        /**
         * Determines if a given backtrace contains a call to the shutdownHandler method in the LogLib\Runtime class.
         *
         * @param array $backtrace The backtrace to be analyzed.
         * @return bool True if the shutdownHandler method in the LogLib\Runtime class is found in the backtrace; otherwise, false.
         */
        public static function fromShutdownHandler(array $backtrace): bool
        {
            /** @var array $trace */
            foreach($backtrace as $trace)
            {
                if(!isset($trace['function']) || $trace['function'] != 'shutdownHandler')
                {
                    continue;
                }

                if(!isset($trace['class']) || $trace['class'] != 'LogLib\Runtime')
                {
                    continue;
                }

                return true;
            }

            return false;
        }
    }