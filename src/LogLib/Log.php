<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib;

    use ErrorException;
    use Exception;
    use InvalidArgumentException;
    use LogLib\Classes\Utilities;
    use LogLib\Enums\LogLevel;
    use LogLib\Objects\Application;
    use LogLib\Objects\Event;
    use Throwable;

    class Log
    {
        /**
         * @var Application[]|null
         */
        private static $applications;

        /**
         * Registers a new application logger
         *
         * @param Application $application The options for the application
         * @param bool $overwrite
         * @return bool
         */
        public static function register(Application $application, bool $overwrite=false): bool
        {
            if(self::isRegistered($application->getApplicationName()))
            {
                if($overwrite)
                {
                    self::$applications[$application->getApplicationName()] = $application;
                    return true;
                }

                return false;
            }

            if(self::$applications === null)
            {
                self::$applications = [];
            }

            self::$applications[$application->getApplicationName()] = $application;
            return true;
        }

        /**
         * Removes a registered application logger
         *
         * @param string $application The name of the application
         * @return void
         */
        public static function unregister(string $application): void
        {
            if(self::$applications === null)
            {
                return;
            }

            if(isset(self::$applications[$application]))
            {
                unset(self::$applications[$application]);
            }
        }

        /**
         * Determines if the given application is registered
         *
         * @param string $application
         * @return bool
         */
        private static function isRegistered(string $application): bool
        {
            return isset(self::$applications[$application]);
        }

        /**
         * Retrieves the application options. If the application is not registered, it optionally creates and registers a new one.
         *
         * @param string $application The name of the application.
         * @param bool $create (Optional) Whether to create the application if it is not registered. Default is true.
         * @return Application The options for the specified application.
         */
        public static function getApplication(string $application, bool $create=true): Application
        {
            if(!self::isRegistered($application))
            {
                if(!$create)
                {
                    throw new InvalidArgumentException("The application '$application' is not registered");
                }

                self::register(new Application($application));
            }

            return self::$applications[$application];
        }

        /**
         * Logs a message with a specified application name, level, optional message, and optional throwable.
         *
         * @param string|null $application_name The name of the application
         * @param LogLevel $level The level type of the log (default is LevelType::INFO)
         * @param string|null $message The message of the log event
         * @param Throwable|null $throwable The exception that was thrown, if any
         * @return void
         */
        private static function log(?string $application_name, LogLevel $level=LogLevel::INFO, ?string $message=null, ?Throwable $throwable=null): void
        {
            $application = self::getApplication($application_name);

            if($message === null)
            {
                throw new InvalidArgumentException('Message cannot be null');
            }

            $event = new Event($message, $level, $throwable);

            if($event->getBacktrace() === null)
            {
                $event->setBacktrace(Utilities::getBacktrace());
            }

            if($application->isConsoleLoggingEnabled())
            {
                $application->getConsoleLoggingHandler()::handle($application, $event);
            }

            if($application->isFileLoggingEnabled())
            {
                $application->getFileLoggingHandler()::handle($application, $event);
            }
        }

        /**
         * @param string $application_name The name of the application
         * @param string $message The message of the event
         * @return void
         */
        public static function info(string $application_name, string $message): void
        {
            self::log($application_name, LogLevel::INFO, $message);
        }

        /**
         * @param string $application_name The name of the application
         * @param string $message The message of the event
         * @return void
         */
        public static function verbose(string $application_name, string $message): void
        {
            self::log($application_name, LogLevel::VERBOSE, $message);
        }

        /**
         * @param string $application_name The name of the application
         * @param string $message The message of the event
         * @return void
         */
        public static function debug(string $application_name, string $message): void
        {
            self::log($application_name, LogLevel::DEBUG, $message);
        }

        /**
         * Logs a warning message.
         *
         * @param string $application_name The name of the application.
         * @param string $message The warning message to log.
         * @param Throwable|null $throwable (Optional) The throwable object associated with the warning.
         * @return void
         */
        public static function warning(string $application_name, string $message, ?Throwable $throwable=null): void
        {
            self::log($application_name, LogLevel::WARNING, $message, $throwable);
        }

        /**
         * Logs an error message.
         *
         * @param string $application_name The name of the application.
         * @param string $message The error message.
         * @param Throwable|null $throwable The optional throwable object associated with the error.
         * @return void
         */
        public static function error(string $application_name, string $message, ?Throwable $throwable=null): void
        {
            self::log($application_name, LogLevel::ERROR, $message, $throwable);
        }

        /**
         * Logs a fatal message.
         *
         * @param string $application_name The name of the application.
         * @param string $message The fatal message to log.
         * @param Throwable|null $throwable (Optional) The throwable object associated with the fatal message.
         * @return void
         */
        public static function fatal(string $application_name, string $message, ?Throwable $throwable=null): void
        {
            self::log($application_name, LogLevel::FATAL, $message, $throwable);
        }

        /**
         * Registers an exception handler that logs any uncaught exceptions as errors.
         *
         * @return void
         */
        public static function registerExceptionHandler(): void
        {
            set_exception_handler(static function(Throwable $throwable)
            {
                try
                {
                    self::error('Runtime', $throwable->getMessage(), $throwable);
                }
                catch(Exception)
                {
                    return;
                }
            });

            // Register error handler
            set_error_handler(static function($errno, $errstr, $errfile, $errline)
            {
                // Convert error to exception and throw it
                try
                {
                    self::warning('Runtime', sprintf("%s:%s (%s) %s", $errfile, $errline, $errno, $errstr));
                }
                catch(Exception)
                {
                    return;
                }
            });

            register_shutdown_function(static function()
            {
                $error = error_get_last();
                if ($error !== null && ($error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR)))
                {
                    // Convert fatal error to exception and handle it
                    $exception = new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
                    self::error('Fatal', $exception->getMessage(), $exception);
                }
            });
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