<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Classes;

    use Exception;
    use LogLib\Enums\ConsoleColors;
    use LogLib\Enums\LogLevel;
    use LogLib\Log;
    use LogLib\Objects\Event;
    use LogLib\Objects\Options;
    use RuntimeException;
    use Throwable;

    class Console
    {
        private static array $application_colors = [];
        private static float|int|null $last_tick_time;
        private static ?int $largest_tick_length;

        /**
         * Formats the application name with a color for the console
         *
         * @param string $application The application name
         * @return string The formatted application name
         * @throws RuntimeException If unable to generate a random color for the application
         */
        private static function formatAppColor(string $application): string
        {
            if(!Log::getRuntimeOptions()->displayAnsi())
            {
                return $application;
            }

            if(!isset(self::$application_colors[$application]))
            {
                $colors = ConsoleColors::BRIGHT_COLORS;

                try
                {
                    $color = $colors[random_int(0, count($colors) - 1)];
                }
                catch (Exception $e)
                {
                    throw new RuntimeException(sprintf('Unable to generate random color for application "%s"', $application), $e->getCode(), $e);
                }

                self::$application_colors[$application] = $color;
            }

            return self::color($application, self::$application_colors[$application]);
        }

        /**
         * Applies a specified color to the given text, using ANSI escape sequences.
         *
         * @param string $text The text to apply the color to.
         * @param ConsoleColors $color The ANSI color code to apply to the text.
         * @return string The text with the specified color applied.
         */
        private static function color(string $text, ConsoleColors $color): string
        {
            if(!Log::getRuntimeOptions()->displayAnsi())
            {
                return $text;
            }

            return "\033[" . $color->value . "m" . $text . "\033[0m";
        }

        /**
         * Colorizes the log message based on the event level using ANSI escape sequences.
         *
         * @param Event $event The log event to colorize.
         * @return string The colorized log message.
         */
        private static function colorize(Event $event): string
        {
            if(!Log::getRuntimeOptions()->displayAnsi())
            {
                return Utilities::levelToString($event->getLevel());
            }

            $color = match($event->getLevel())
            {
                LogLevel::DEBUG => ConsoleColors::LIGHT_PURPLE,
                LogLevel::VERBOSE => ConsoleColors::LIGHT_CYAN,
                LogLevel::INFO => ConsoleColors::WHITE,
                LogLevel::WARNING => ConsoleColors::YELLOW,
                LogLevel::FATAL => ConsoleColors::RED,
                LogLevel::ERROR => ConsoleColors::LIGHT_RED,
                default => null,
            };

            if($color === null)
            {
                return Utilities::levelToString($event->getLevel());
            }

            return self::color(Utilities::levelToString($event->getLevel()), $color);
        }

        /**
         * Retrieves the current timestamp as a formatted string.
         *
         * @return string The current timestamp.
         */
        private static function getTimestamp(): string
        {
            $tick_time = (string)microtime(true);

            if(!is_null(self::$largest_tick_length) && strlen($tick_time) > (int)self::$largest_tick_length)
            {
                self::$largest_tick_length = strlen($tick_time);
            }

            if(strlen($tick_time) < self::$largest_tick_length)
            {
                $tick_time = str_pad($tick_time, (strlen($tick_time) + (self::$largest_tick_length - strlen($tick_time))));
            }

            $fmt_tick = $tick_time;
            if(self::$last_tick_time !== null)
            {
                $timeDiff = microtime(true) - self::$last_tick_time;

                if ($timeDiff > 1.0)
                {
                    $fmt_tick = self::color($tick_time, ConsoleColors::LIGHT_RED);
                }
                elseif ($timeDiff > 0.5)
                {
                    $fmt_tick = self::color($tick_time, ConsoleColors::YELLOW);
                }
            }

            self::$last_tick_time = $tick_time;
            return $fmt_tick;
        }

        /**
         * Outputs a log event to the console.
         *
         * @param Options $options The options configuration object.
         * @param Event $event The log event to output.
         * @return void
         */
        public static function out(Options $options, Event $event): void
        {
            if(!Utilities::runningInCli())
            {
                return;
            }

            if(Validate::checkLevelType(LogLevel::DEBUG, Log::getRuntimeOptions()->getLoglevel()))
            {
                $backtrace_output = Utilities::getTraceString($event, Log::getRuntimeOptions()->displayAnsi());

                print(sprintf("[%s] [%s] [%s] %s %s" . PHP_EOL,
                    self::getTimestamp(),
                    self::formatAppColor($options->getApplicationName()),
                    self::colorize($event),
                    $backtrace_output, $event->getMessage()
                ));

                if($event->getException() !== null)
                {
                    self::outException($event->getException());
                }

                return;
            }

            if(Validate::checkLevelType(LogLevel::VERBOSE, Log::getRuntimeOptions()->getLoglevel()))
            {
                $backtrace_output = Utilities::getTraceString($event, Log::getRuntimeOptions()->displayAnsi());

                print(sprintf("[%s] [%s] %s %s" . PHP_EOL,
                    self::formatAppColor($options->getApplicationName()),
                    self::colorize($event),
                    $backtrace_output, $event->getMessage()
                ));

                if($event->getException() !== null)
                {
                    self::outException($event->getException());
                }

                return;
            }

            print(sprintf("[%s] [%s] %s" . PHP_EOL,
                self::formatAppColor($options->getApplicationName()),
                self::colorize($event),
                $event->getMessage()
            ));
        }

        /**
         * Prints information about the given exception, including the error message, error code,
         * and stack trace.
         *
         * @param Throwable|null $exception The exception to print information about.
         * @return void
         */
        private static function outException(?Throwable $exception=null): void
        {
            if($exception === null)
            {
                return;
            }

            $trace_header = self::color($exception->getFile() . ':' . $exception->getLine(), ConsoleColors::PURPLE);
            $trace_error = self::color('error: ', ConsoleColors::RED);

            print($trace_header . ' ' . $trace_error . $exception->getMessage() . PHP_EOL);
            print(sprintf('Error code: %s', $exception->getCode()) . PHP_EOL);
            $trace = $exception->getTrace();

            if(count($trace) > 1)
            {
                print('Stack Trace:' . PHP_EOL);
                foreach($trace as $item)
                {
                    print( ' - ' . self::color($item['file'], ConsoleColors::RED) . ':' . $item['line'] . PHP_EOL);
                }
            }

            if($exception->getPrevious() !== null)
            {
                print('Previous Exception:' . PHP_EOL);

                self::outException($exception->getPrevious());
            }
        }
    }