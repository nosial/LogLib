<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib;

    use InvalidArgumentException;
    use LogLib\Abstracts\LevelType;
    use LogLib\Classes\Console;
    use LogLib\Classes\Utilities;
    use LogLib\Classes\Validate;
    use LogLib\Objects\Event;
    use LogLib\Objects\Options;
    use LogLib\Objects\RuntimeOptions;
    use Throwable;

    class Log
    {
        /**
         * @var Options[]|null
         */
        private static $applications;

        /**
         * @var RuntimeOptions|null
         */
        private static $runtime_options;

        /**
         * Registers a new application logger
         *
         * @param Options $options The options for the application
         * @return bool
         */
        public static function register(Options $options): bool
        {
            if(self::isRegistered($options->getApplicationName()))
            {
                return false;
            }

            if(self::$applications === null)
            {
                self::$applications = [];
            }

            self::$applications[$options->getApplicationName()] = $options;
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
         * @param string $application
         * @return Options
         */
        public static function getApplication(string $application): Options
        {
            if(!self::isRegistered($application))
            {
                throw new InvalidArgumentException("The application '$application' is not registered");
            }

            return self::$applications[$application];
        }

        /**
         * @param string $application_name The name of the application
         * @return Options The options for the application
         */
        public static function getOptions(string $application_name): Options
        {
            if(!self::isRegistered($application_name))
            {
                self::register(new Options($application_name));
            }

            return self::$applications[$application_name];
        }

        /**
         * Logs a message with a specified application name, level, optional message, and optional throwable.
         *
         * @param string $application_name The name of the application
         * @param int $level The level type of the log (default is LevelType::INFO)
         * @param string|null $message The message of the log event
         * @param Throwable|null $throwable The exception that was thrown, if any
         * @return void
         * @throws InvalidArgumentException If the provided level type is invalid or a message is null
         */
        private static function log(string $application_name, int $level=LevelType::INFO, ?string $message=null, ?Throwable $throwable=null): void
        {
            $application = self::getOptions($application_name);

            if(!Validate::levelType($level))
            {
                throw new InvalidArgumentException(sprintf('Invalid level type: %s', $level));
            }

            if(!Validate::checkLevelType($level, self::getRuntimeOptions()->getLoglevel()))
            {
                return;
            }

            if($message === null)
            {
                throw new InvalidArgumentException('Message cannot be null');
            }

            $event = new Event($message, $level, $throwable);

            if($event->getBacktrace() === null)
            {
                $event->setBacktrace(Utilities::getBacktrace());
            }

            if(self::getRuntimeOptions()->isConsoleOutput())
            {
                Console::out($application, $event);
            }
        }

        /**
         * @param string $application_name The name of the application
         * @param string $message The message of the event
         * @return void
         */
        public static function info(string $application_name, string $message): void
        {
            self::log($application_name, LevelType::INFO, $message);
        }

        /**
         * @param string $application_name The name of the application
         * @param string $message The message of the event
         * @return void
         */
        public static function verbose(string $application_name, string $message): void
        {
            self::log($application_name, LevelType::VERBOSE, $message);
        }

        /**
         * @param string $application_name The name of the application
         * @param string $message The message of the event
         * @return void
         */
        public static function debug(string $application_name, string $message): void
        {
            self::log($application_name, LevelType::DEBUG, $message);
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
            self::log($application_name, LevelType::WARNING, $message, $throwable);
        }

        /**
         * Logs an error message.
         *
         * @param string $application_name The name of the application.
         * @param string $message The error message.
         * @param Throwable|null $throwable The optional throwable object associated with the error.
         * @return void
         **/
        public static function error(string $application_name, string $message, ?Throwable $throwable=null): void
        {
            self::log($application_name, LevelType::ERROR, $message, $throwable);
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
            self::log($application_name, LevelType::FATAL, $message, $throwable);
        }

        /**
         * Registers an exception handler that logs any uncaught exceptions as errors.
         *
         * @return void
         */
        public static function registerExceptionHandler(): void
        {
            set_exception_handler(static function(Throwable $throwable) {
                self::error('Runtime', $throwable->getMessage(), $throwable);
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

        /**
         * Gets the runtime options.
         *
         * @return RuntimeOptions The runtime options.
         */
        public static function getRuntimeOptions(): RuntimeOptions
        {
            if(self::$runtime_options === null)
            {
                self::$runtime_options = new RuntimeOptions();
            }

            return self::$runtime_options;
        }

    }