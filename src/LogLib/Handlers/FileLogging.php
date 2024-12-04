<?php

namespace LogLib\Handlers;

use LogLib\Classes\FileLock;
use LogLib\Classes\Utilities;
use LogLib\Classes\Validate;
use LogLib\Enums\LogLevel;
use LogLib\Interfaces\LogHandlerInterface;
use LogLib\Objects\Application;
use LogLib\Objects\Event;
use RuntimeException;
use Throwable;

class FileLogging implements LogHandlerInterface
{
    private static array $application_logs = [];

    /**
     * @inheritDoc
     */
    public static function handle(Application $application, Event $event): void
    {
        if(!Validate::checkLevelType($event->getLevel(), $application->getFileLoggingLevel()))
        {
            return;
        }

        if(Validate::checkLevelType(LogLevel::DEBUG, $application->getConsoleLoggingLevel()))
        {
            $backtrace_output = Utilities::getTraceString($event, false);
            $output = sprintf("[%s] [%s] [%s] %s %s" . PHP_EOL,
                self::getTimestamp(), $application->getApplicationName(), $event->getLevel()->name, $backtrace_output, $event->getMessage()
            );
        }
        else if(Validate::checkLevelType(LogLevel::VERBOSE, $application->getConsoleLoggingLevel()))
        {
            $backtrace_output = Utilities::getTraceString($event, false);
            $output = sprintf("[%s] [%s] [%s] %s %s" . PHP_EOL, self::getTimestamp(), $application->getApplicationName(), $event->getLevel()->name, $backtrace_output, $event->getMessage());
        }
        else
        {
            $output = sprintf("[%s] [%s] [%s] %s" . PHP_EOL, self::getTimestamp(), $application->getApplicationName(), $event->getLevel()->name, $event->getMessage());
        }

        if($event->getException() !== null)
        {
            $output .= self::outException($event->getException());
        }

        self::getLogger($application)->append($output);
    }

    /**
     * Retrieves the logger instance associated with the given application.
     * If the logger does not exist, it initializes a new one and stores it.
     *
     * @param Application $application The application for which the logger is to be retrieved.
     * @return FileLock The logger instance associated with the specified application.
     */
    private static function getLogger(Application $application): FileLock
    {
        if(!isset(self::$application_logs[$application->getApplicationName()]))
        {
            self::$application_logs[$application->getApplicationName()] = new FileLock(self::getLogFile($application));
        }

        return self::$application_logs[$application->getApplicationName()];
    }

    /**
     * Retrieves the log file path for the specified application.
     *
     * @param Application $application The application instance for which the log file is to be retrieved.
     * @return string The full path of the log file.
     */
    private static function getLogFile(Application $application): string
    {
        $logging_directory = $application->getFileLoggingDirectory();

        if(!file_exists($logging_directory))
        {
            if(!mkdir($logging_directory))
            {
                throw new RuntimeException(sprintf("Cannot write to %s due to insufficient permissions", $logging_directory));
            }
        }

        $logging_file = $logging_directory . DIRECTORY_SEPARATOR . Utilities::sanitizeFileName($application->getApplicationName()) . '-' . date('Y-m-d') . '.log';

        if(!file_exists($logging_file))
        {
            touch($logging_file);
        }

        return $logging_file;
    }

    /**
     * Retrieves the current timestamp formatted as "yd/m/y H:i".
     *
     * @return string The formatted current timestamp.
     */
    private static function getTimestamp(): string
    {
        return date('yd/m/y H:i');
    }

    /**
     * Generates a detailed string representation of a given Throwable object, including its message, code,
     * file, line of occurrence, stack trace, and any previous exceptions.
     *
     * @param Throwable|null $exception The throwable object to process. If null, an empty string is returned.
     * @return string A detailed string representation of the throwable object.
     */
    private static function outException(?Throwable $exception=null): string
    {
        if($exception === null)
        {
            return '';
        }

        $output = '';
        $trace_header = $exception->getFile() . ':' . $exception->getLine();
        $trace_error = 'error: ';

        $output .= $trace_header . ' ' . $trace_error . $exception->getMessage() . PHP_EOL;
        $output .= sprintf('Error code: %s', $exception->getCode() . PHP_EOL);
        $trace = $exception->getTrace();

        if(count($trace) > 1)
        {
            $output .= 'Stack Trace:' . PHP_EOL;
            foreach($trace as $item)
            {
                if(isset($item['file']) && isset($item['line']))
                {
                    $output .=  ' - ' . $item['file'] . ':' . $item['line'] . PHP_EOL;
                }
            }
        }

        if($exception->getPrevious() !== null)
        {
            $output .= 'Previous Exception:' . PHP_EOL;
            $output .= self::outException($exception->getPrevious());
        }

        return $output;
    }
}