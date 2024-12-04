<?php

namespace LogLib\Handlers;

use Exception;
use LogLib\Classes\Utilities;
use LogLib\Classes\Validate;
use LogLib\Enums\ConsoleColors;
use LogLib\Enums\LogLevel;
use LogLib\Interfaces\LogHandlerInterface;
use LogLib\Objects\Application;
use LogLib\Objects\Event;
use RuntimeException;
use Throwable;

class ConsoleLogging implements LogHandlerInterface
{
    private static array $application_colors = [];
    private static float|int|null $last_tick_time = null;
    private static ?int $largest_tick_length = null;

    /**
     * @inheritDoc
     */
    public static function handle(Application $application, Event $event): void
    {
        // Check if the application is running in a CLI environment, if not, return
        if(!Utilities::runningInCli())
        {
            return;
        }

        // Check if the event level is enabled for console logging
        if(!Validate::checkLevelType($event->getLevel(), $application->getConsoleLoggingLevel()))
        {
            return;
        }

        if(Validate::checkLevelType(LogLevel::DEBUG, $application->getConsoleLoggingLevel()))
        {
            $backtrace_output = Utilities::getTraceString($event);

            print(sprintf("[%s] [%s] [%s] %s %s" . PHP_EOL,
                self::getTimestamp(), self::formatAppColor($application->getApplicationName()), self::colorize($event),
                $backtrace_output, $event->getMessage()
            ));

            if($event->getException() !== null)
            {
                self::outException($event->getException());
            }

            return;
        }

        if(Validate::checkLevelType(LogLevel::VERBOSE, $application->getConsoleLoggingLevel()))
        {
            $backtrace_output = Utilities::getTraceString($event);

            print(sprintf("[%s] [%s] %s %s" . PHP_EOL,
                self::formatAppColor($application->getApplicationName()),
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
            self::formatAppColor($application->getApplicationName()),
            self::colorize($event),
            $event->getMessage()
        ));
    }

    /**
     * Formats the application name with a color for the console
     *
     * @param string $application_name The application name
     * @return string The formatted application name
     */
    private static function formatAppColor(string $application_name): string
    {
        if(!isset(self::$application_colors[$application_name]))
        {
            $colors = ConsoleColors::ALL;

            try
            {
                $color = $colors[random_int(0, count($colors) - 1)];
            }
            catch (Exception $e)
            {
                throw new RuntimeException(sprintf('Unable to generate random color for application "%s"', $application_name), $e->getCode(), $e);
            }

            self::$application_colors[$application_name] = $color;
        }

        return self::color($application_name, self::$application_colors[$application_name]);
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
                if(isset($item['file']) && isset($item['line']))
                {
                    print( ' - ' . self::color($item['file'], ConsoleColors::RED) . ':' . $item['line'] . PHP_EOL);
                }
            }
        }

        if($exception->getPrevious() !== null)
        {
            print('Previous Exception:' . PHP_EOL);
            self::outException($exception->getPrevious());
        }
    }
}