<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Classes;

    use Exception;
    use LogLib\Abstracts\ConsoleColors;
    use LogLib\Abstracts\LevelType;
    use LogLib\Log;
    use LogLib\Objects\Event;
    use LogLib\Objects\Options;
    use RuntimeException;
    use Throwable;

    class Console
    {
        /**
         * @var array
         */
        private static $application_colors = [];


        /**
         * @var float|int|null
         */
        private static $last_tick_time;

        /**
         * @var int|null
         */
        private static $largest_tick_length;

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
         * @param string $color The ANSI color code to apply to the text.
         * @return string The text with the specified color applied.
         */
        private static function color(string $text, string $color): string
        {
            if(!Log::getRuntimeOptions()->displayAnsi())
            {
                return $text;
            }

            return "\033[" . $color . "m" . $text . "\033[0m";
        }

        /**
         * Applies a specified color to the given text, based on the event level, using ANSI escape sequences.
         *
         * @param Event $event The event object.
         * @param string $text The text to apply the color to.
         * @return string The text with the specified color applied.
         */
        private static function colorize(Event $event, string $text): string
        {
            if(!Log::getRuntimeOptions()->displayAnsi())
            {
                return Utilities::levelToString($text);
            }

            $color = match($event->getLevel())
            {
                LevelType::DEBUG => ConsoleColors::LIGHT_PURPLE,
                LevelType::VERBOSE => ConsoleColors::LIGHT_CYAN,
                LevelType::INFO => ConsoleColors::WHITE,
                LevelType::WARNING => ConsoleColors::YELLOW,
                LevelType::FATAL => ConsoleColors::RED,
                LevelType::ERROR => ConsoleColors::LIGHT_RED,
                default => null,
            };

            if($color === null)
            {
                return Utilities::levelToString($text);
            }

            return self::color(Utilities::levelToString($text), $color);
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

            if(Validate::checkLevelType(LevelType::VERBOSE, Log::getRuntimeOptions()->getLoglevel()))
            {
                $backtrace_output = Utilities::getTraceString($event, Log::getRuntimeOptions()->displayAnsi());

                print(sprintf("[%s] [%s] [%s] %s %s" . PHP_EOL,
                    self::getTimestamp(),
                    self::formatAppColor($options->getApplicationName()),
                    self::colorize($event, $event->getLevel()),
                    $backtrace_output, $event->getMessage()
                ));

                if($event->getException() !== null)
                {
                    /** @noinspection NullPointerExceptionInspection */
                    self::outException($event->getException());
                }

                return;
            }

            print(sprintf("[%s] [%s] [%s] %s" . PHP_EOL,
                self::getTimestamp(),
                self::formatAppColor($options->getApplicationName()),
                self::colorize($event, $event->getLevel()),
                $event->getMessage()
            ));
        }

        /**
         * Prints information about the given exception, including the error message, error code,
         * and stack trace.
         *
         * @param Throwable $exception The exception to print information about.
         * @return void
         */
        private static function outException(Throwable $exception): void
        {
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

                /** @noinspection NullPointerExceptionInspection */
                self::outException($exception->getPrevious());
            }
        }
    }