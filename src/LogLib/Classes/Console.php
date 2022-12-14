<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Classes;

    use LogLib\Abstracts\ConsoleColors;
    use LogLib\Abstracts\LevelType;
    use LogLib\Objects\Event;
    use LogLib\Objects\Options;

    class Console
    {
        /**
         * @var array
         */
        private static $ApplicationColors = [];

        /**
         * Formats the application color for the console
         *
         * @param string $application
         * @return string
         */
        private static function formatAppColor(string $application): string
        {
            if(!isset(self::$ApplicationColors[$application]))
            {
                $colors = ConsoleColors::All;
                $color = $colors[array_rand($colors)];
                self::$ApplicationColors[$application] = $color;
            }
            return self::$ApplicationColors[$application];
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
            return "\033[{$color}m$text\033[0m";
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
         * Returns the formatted backtrace
         *
         * @param Event $event
         * @return string|null
         */
        private static function parseBacktrace(Event $event): ?string
        {
            $backtrace = null;
            if($event->Backtrace !== null && count($event->Backtrace) > 0)
            {
                foreach($event->Backtrace as $item)
                {
                    if($item->Class !== 'LogLib\\Log')
                    {
                        $backtrace = $item;
                        break;
                    }
                }
            }

            $backtrace_output = null;
            if($backtrace !== null)
            {
                if($backtrace->Class !== null)
                {
                    $backtrace_output = $backtrace->Class . $backtrace->Type . $backtrace->Function . '()';
                }
                else
                {
                    $backtrace_output = $backtrace->Function . '()';
                }

                if($backtrace->Line !== null)
                    $backtrace_output .= ':' . $backtrace->Line;
            }

            return $backtrace_output;
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
            // If the current level is verbose or higher, then we need to output the backtrace
            if(Validate::checkLevelType(LevelType::Verbose, $options->getOutputLevel()))
            {
                $backtrace_output = self::parseBacktrace($event);

                if($options->isConsoleAnsiColors())
                {
                    print(sprintf(
                        "%s [%s] [%s] (%s) - %s" . PHP_EOL,
                        $event->getTimestamp(),
                        self::formatAppColor($options->getApplicationName()),
                        self::colorize($event, $event->Level),
                        $backtrace_output !== null ? $backtrace_output : 'λ',
                        $event->Message
                    ));
                }
                else
                {
                    print(sprintf(
                        "%s [%s] [%s] - %s - %s" . PHP_EOL,
                        $event->getTimestamp(), $options->getApplicationName(), $event->Level,
                        $backtrace_output !== null ? $backtrace_output : 'lambda',
                        $event->Message
                    ));
                }

                if($event->Exception !== null)
                    self::outException($event->Exception);
            }
            elseif(!Validate::checkLevelType(LevelType::Fatal, $options->getOutputLevel()))
            {
                if($options->isConsoleAnsiColors())
                {
                    print(sprintf(
                        "%s [%s] [%s] - %s" . PHP_EOL,
                        $event->getTimestamp(),
                        self::formatAppColor($options->getApplicationName()),
                        self::colorize($event, $event->Level),
                        $event->Message
                    ));
                }
                else
                {
                    print(sprintf(
                        "%s [%s] [%s] - %s" . PHP_EOL,
                        $event->getTimestamp(), $options->getApplicationName(), $event->Level,
                        $event->Message
                    ));
                }

                if($event->Exception !== null)
                    self::outException($event->Exception);
            }


        }

        /**
         * Prints out the exception details
         *
         * @param array $exception
         * @return void
         */
        private static function outException(array $exception): void
        {
            $trace_header = self::color($exception['file'] . ':' . $exception['line'], ConsoleColors::Purple);
            $trace_error = self::color('error: ', ConsoleColors::Red);
            print($trace_header . ' ' . $trace_error . $exception['message'] . PHP_EOL);
            print(sprintf('Error code: %s', $exception['code']) . PHP_EOL);
            $trace = $exception['trace'];
            if(count($trace) > 1)
            {
                print('Stack Trace:' . PHP_EOL);
                foreach($trace as $item)
                {
                    print( ' - ' . self::color($item['file'], ConsoleColors::Red) . ':' . $item['line'] . PHP_EOL);
                }
            }

            if($exception['previous'] !== null)
            {
                print('Previous Exception:' . PHP_EOL);
                self::outException($exception['previous']);
            }
        }
    }