<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Classes;

    use LogLib\Abstracts\ConsoleColors;
    use LogLib\Abstracts\LevelType;
    use LogLib\Log;
    use LogLib\Objects\Event;
    use LogLib\Objects\Options;
    use Throwable;

    class Console
    {
        /**
         * @var array
         */
        private static $application_colors = [];


        /**
         * @var float|int
         */
        private static $last_tick_time;

        /**
         * @var int
         */
        private static $largest_tick_length;

        /**
         * Formats the application color for the console
         *
         * @param string $application
         * @return string
         */
        private static function formatAppColor(string $application): string
        {
            if(!Log::getRuntimeOptions()->isDisplayAnsi())
                return $application;

            if(!isset(self::$application_colors[$application]))
            {
                $colors = ConsoleColors::BrightColors;
                $color = $colors[array_rand($colors)];
                self::$application_colors[$application] = $color;
            }

            return self::color($application, self::$application_colors[$application]);
        }

        /**
         * Returns a color formatted string for the console
         *
         * @param string $text
         * @param string $color
         * @return string
         */
        private static function color(string $text, string $color): string
        {
            if(!Log::getRuntimeOptions()->isDisplayAnsi())
                return $text;

            return "\033[" . $color . "m" . $text . "\033[0m";
        }

        /**
         * Colorizes a event string for the console
         *
         * @param Event $event
         * @param string $text
         * @return string
         */
        private static function colorize(Event $event, string $text): string
        {
            if(!Log::getRuntimeOptions()->isDisplayAnsi())
                return Utilities::levelToString($text);

            $color = null;
            switch($event->Level)
            {
                case LevelType::Debug:
                    $color = ConsoleColors::LightPurple;
                    break;
                case LevelType::Verbose:
                    $color = ConsoleColors::LightCyan;
                    break;
                case LevelType::Info:
                    $color = ConsoleColors::White;
                    break;
                case LevelType::Warning:
                    $color = ConsoleColors::Yellow;
                    break;
                case LevelType::Fatal:
                    $color = ConsoleColors::Red;
                    break;
                case LevelType::Error:
                    $color = ConsoleColors::LightRed;
                    break;
            }

            if($color == null)
                return Utilities::levelToString($text);

            return self::color(Utilities::levelToString($text), $color);
        }

        /**
         * Returns the current timestamp tick
         *
         * @return string
         */
        private static function getTimestamp(): string
        {
            $tick_time = (string)microtime(true);

            if(strlen($tick_time) > self::$largest_tick_length)
            {
                self::$largest_tick_length = strlen($tick_time);
            }

            if(strlen($tick_time) < self::$largest_tick_length)
            {
                /** @noinspection PhpRedundantOptionalArgumentInspection */
                $tick_time = str_pad($tick_time, (strlen($tick_time) + (self::$largest_tick_length - strlen($tick_time))), ' ', STR_PAD_RIGHT);
            }

            $fmt_tick = $tick_time;
            if(self::$last_tick_time !== null)
            {
                $timeDiff = microtime(true) - self::$last_tick_time;

                if ($timeDiff > 1.0)
                {
                    $fmt_tick = \ncc\Utilities\Console::formatColor($tick_time, \ncc\Abstracts\ConsoleColors::LightRed);
                }
                elseif ($timeDiff > 0.5)
                {
                    $fmt_tick = self::color($tick_time, ConsoleColors::Yellow);
                }
            }

            self::$last_tick_time = $tick_time;
            return $fmt_tick;
        }

        /**
         * Regular console output for the event object
         *
         * @param Options $options
         * @param Event $event
         * @return void
         */
        public static function out(Options $options, Event $event): void
        {
            if(!Utilities::runningInCli())
                return;

            if(Validate::checkLevelType(LevelType::Verbose, Log::getRuntimeOptions()->getLogLevel()))
            {
                $backtrace_output = Utilities::getTraceString($event, Log::getRuntimeOptions()->isDisplayAnsi());

                print(sprintf(
                    "%s [%s] [%s] %s %s" . PHP_EOL,
                    self::getTimestamp(),
                    self::formatAppColor($options->getApplicationName()),
                    self::colorize($event, $event->Level),
                    $backtrace_output, $event->Message
                ));

                if($event->Exception !== null)
                    self::outException($event->Exception);

                return;
            }

            print(sprintf(
                "%s [%s] [%s] %s" . PHP_EOL,
                self::getTimestamp(),
                self::formatAppColor($options->getApplicationName()),
                self::colorize($event, $event->Level),
                $event->Message
            ));
        }

        /**
         * Prints out the exception details
         *
         * @param Throwable $exception
         * @return void
         */
        private static function outException(Throwable $exception): void
        {
            $trace_header = self::color($exception->getFile() . ':' . $exception->getLine(), ConsoleColors::Purple);
            $trace_error = self::color('error: ', ConsoleColors::Red);

            print($trace_header . ' ' . $trace_error . $exception->getMessage() . PHP_EOL);
            print(sprintf('Error code: %s', $exception->getCode()) . PHP_EOL);
            $trace = $exception->getTrace();

            if(count($trace) > 1)
            {
                print('Stack Trace:' . PHP_EOL);
                foreach($trace as $item)
                {
                    print( ' - ' . self::color($item['file'], ConsoleColors::Red) . ':' . $item['line'] . PHP_EOL);
                }
            }

            if($exception->getPrevious() !== null)
            {
                print('Previous Exception:' . PHP_EOL);
                self::outException($exception['previous']);
            }
        }
    }