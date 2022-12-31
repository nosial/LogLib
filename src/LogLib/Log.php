<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib;

    use InvalidArgumentException;
    use LogLib\Abstracts\LevelType;
    use LogLib\Classes\Console;
    use LogLib\Classes\FileLogging;
    use LogLib\Classes\Utilities;
    use LogLib\Classes\Validate;
    use LogLib\Objects\Event;
    use LogLib\Objects\Options;
    use LogLib\Objects\RuntimeOptions;
    use Properties\Exceptions\ReconstructException;
    use Throwable;

    class Log
    {

        /**
         * @var Options[]
         */
        private static $Applications;

        /**
         * @var RuntimeOptions
         */
        private static $RuntimeOptions;

        /**
         * Registers a new application logger
         *
         * @param Options $options The options for the application
         * @return bool
         */
        public static function register(Options $options): bool
        {
            if(self::isRegistered($options->getApplicationName()))
                return false;

            self::$Applications[$options->getApplicationName()] = $options;
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
            if(isset(self::$Applications[$application]))
                unset(self::$Applications[$application]);
        }

        /**
         * Determines if the given application is registered
         *
         * @param string $application
         * @return bool
         */
        private static function isRegistered(string $application): bool
        {
            return isset(self::$Applications[$application]);
        }

        /**
         * @param string $application
         * @return Options
         */
        public static function getApplication(string $application): Options
        {
            if(!self::isRegistered($application))
                throw new InvalidArgumentException("The application '$application' is not registered");

            return self::$Applications[$application];
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

            return self::$Applications[$application_name];
        }

        /**
         * @param string $application_name The name of the application
         * @param string $level The level of the event
         * @param string|null $message The message of the event
         * @param Throwable|null $throwable The exception that was thrown, if any
         * @return void
         * @throws ReconstructException
         */
        private static function log(string $application_name, string $level=LevelType::Info, ?string $message=null, ?Throwable $throwable=null): void
        {
            $application = self::getOptions($application_name);

            if(!Validate::checkLevelType($level, self::getRuntimeOptions()->getLogLevel()))
                return;

            if($message == null)
                throw new InvalidArgumentException('Message cannot be null');
            if($level == null || !Validate::levelType($level))
                throw new InvalidArgumentException('Invalid logging level');

            $event = new Event();
            $event->Level = $level;
            $event->Message = $message;
            $event->Exception = $throwable;

            if($event->getBacktrace() == null)
                $event->setBacktrace(Utilities::getBacktrace());

            if(self::getRuntimeOptions()->isConsoleOutput())
                Console::out($application, $event);

            if($application->writeToPackageData())
                FileLogging::out($application, $event);

            foreach($application->getHandlers() as $event_level => $handlers)
            {
                if(Validate::checkLevelType($event_level, $level))
                {
                    foreach($handlers as $handler)
                        $handler->handle($event);
                }
            }
        }

        /**
         * @param string $application_name The name of the application
         * @param string $message The message of the event
         * @return void
         */
        public static function info(string $application_name, string $message): void
        {
            self::log($application_name, LevelType::Info, $message);
        }

        /**
         * @param string $application_name The name of the application
         * @param string $message The message of the event
         * @return void
         */
        public static function verbose(string $application_name, string $message): void
        {
            self::log($application_name, LevelType::Verbose, $message);
        }

        /**
         * @param string $application_name The name of the application
         * @param string $message The message of the event
         * @return void
         */
        public static function debug(string $application_name, string $message): void
        {
            self::log($application_name, LevelType::Debug, $message);
        }

        /**
         * @param string $application_name The name of the application
         * @param string $message The message of the event
         * @param Throwable|null $throwable The exception that was thrown, if any
         * @return void
         */
        public static function warning(string $application_name, string $message, ?Throwable $throwable=null): void
        {
            self::log($application_name, LevelType::Warning, $message, $throwable);
        }

        /**
         * @param string $application_name The name of the application
         * @param string $message The message of the event
         * @param Throwable|null $throwable The exception that was thrown, if any
         * @return void
         */
        public static function error(string $application_name, string $message, ?Throwable $throwable=null): void
        {
            self::log($application_name, LevelType::Error, $message, $throwable);
        }

        /**
         * @param string $application_name The name of the application
         * @param string $message The message of the event
         * @param Throwable|null $throwable The exception that was thrown, if any
         * @return void
         */
        public static function fatal(string $application_name, string $message, ?Throwable $throwable=null): void
        {
            self::log($application_name, LevelType::Fatal, $message, $throwable);
        }

        /**
         * Registers LogLib as a exception handler
         *
         * @return void
         */
        public static function registerExceptionHandler(): void
        {
            set_exception_handler(function(Throwable $throwable) {
                self::error('Exception', $throwable->getMessage(), $throwable);
            });
        }

        /**
         * Unregisters all applications
         *
         * @return void
         */
        public static function unregisterExceptionHandler(): void
        {
            set_exception_handler(null);
        }

        /**
         * @return RuntimeOptions
         */
        public static function getRuntimeOptions(): RuntimeOptions
        {
            if(self::$RuntimeOptions == null)
            {
                self::$RuntimeOptions = new RuntimeOptions();
            }
            return self::$RuntimeOptions;
        }

    }