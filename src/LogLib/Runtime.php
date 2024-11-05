<?php

    namespace LogLib;

    use ErrorException;
    use Exception;
    use Throwable;

    class Runtime
    {
        /**
         * Registers an exception handler that logs any uncaught exceptions as errors.
         *
         * @return void
         */
        public static function registerExceptionHandler(): void
        {
            set_exception_handler([__CLASS__, 'exceptionHandler']);
            set_error_handler([__CLASS__, 'errorHandler']);
            register_shutdown_function([__CLASS__, 'shutdownHandler']);
        }

        /**
         * Handles uncaught exceptions by logging them with a fatal error level.
         *
         * @param Throwable $throwable The exception or error that was thrown.
         * @return void
         */
        public static function exceptionHandler(Throwable $throwable): void
        {
            try
            {
                Log::Fatal('Runtime', $throwable->getMessage(), $throwable);
            }
            catch(Exception)
            {
                return;
            }
        }

        /**
         * Handles PHP errors by converting them to exceptions and logging appropriately.
         *
         * @param int $errno The level of the error raised.
         * @param string $errstr The error message.
         * @param string $errfile The filename that the error was raised in.
         * @param int $errline The line number the error was raised at.
         * @return bool True to prevent PHP's internal error handler from being invoked.
         */
        public static function errorHandler(int $errno, string $errstr, string $errfile = '', int $errline = 0): bool
        {
            try
            {
                // Convert error to exception for consistent handling
                $exception = new ErrorException($errstr, 0, $errno, $errfile, $errline);

                // Handle different error types
                switch ($errno)
                {
                    case E_ERROR:
                    case E_PARSE:
                    case E_CORE_ERROR:
                    case E_COMPILE_ERROR:
                    case E_USER_ERROR:
                        Log::error('Runtime', $errstr, $exception);
                        break;

                    case E_USER_DEPRECATED:
                    case E_DEPRECATED:
                    case E_USER_NOTICE:
                    case E_NOTICE:
                    case E_USER_WARNING:
                    case E_WARNING:
                    default:
                        Log::warning('Runtime', $errstr, $exception);
                        break;
                }
            }
            catch(Exception)
            {
                return false;
            }

            // Return true to prevent PHP's internal error handler
            return true;
        }

        /**
         * Handles script shutdown by checking for any fatal errors and logging them.
         *
         * This method is designed to be registered with the `register_shutdown_function`,
         * and it inspects the last error that occurred using `error_get_last`. If a fatal
         * error is detected, it logs the error details.
         *
         * @return void
         */
        public static function shutdownHandler(): void
        {
            $error = error_get_last();

            if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR]))
            {
                try
                {
                    $exception = new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
                    Log::error('Fatal Error', $error['message'], $exception);
                }
                catch(Exception)
                {
                    return;
                }
            }
        }

        /**
         * Unregisters the currently registered exception handler.
         *
         * @return void
         */
        public static function unregisterExceptionHandler(): void
        {
            set_exception_handler(null);
        }
    }