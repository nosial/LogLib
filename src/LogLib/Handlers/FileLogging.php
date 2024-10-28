<?php

namespace LogLib\Handlers;

use LogLib\Classes\FileLock;
use LogLib\Classes\Utilities;
use LogLib\Classes\Validate;
use LogLib\Enums\LogHandlerType;
use LogLib\Enums\LogLevel;
use LogLib\Exceptions\LoggingException;
use LogLib\Interfaces\LogHandlerInterface;
use LogLib\Objects\Application;
use LogLib\Objects\Event;
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
            $backtrace_output = Utilities::getTraceString($event);

            $output = sprintf("[%s] [%s] [%s] %s %s" . PHP_EOL,
                self::getTimestamp(), $application->getApplicationName(), $event->getLevel()->name, $backtrace_output, $event->getMessage()
            );

            if($event->getException() !== null)
            {
                $output .= self::outException($event->getException());
            }
        }
        else if(Validate::checkLevelType(LogLevel::VERBOSE, $application->getConsoleLoggingLevel()))
        {
            $backtrace_output = Utilities::getTraceString($event);

            $output = sprintf("[%s] [%s] %s %s" . PHP_EOL, $application->getApplicationName(), $event->getLevel()->name, $backtrace_output, $event->getMessage());

            if($event->getException() !== null)
            {
                $output .= self::outException($event->getException());
            }
        }
        else
        {
            $output = sprintf("[%s] [%s] %s" . PHP_EOL, $application->getApplicationName(), $event->getLevel()->name, $event->getMessage());
        }

        self::getLogger($application)->append($output);
    }

    public static function getType(): LogHandlerType
    {
        return LogHandlerType::FILE;
    }

    private static function getLogger(Application $application): FileLock
    {
        if(!isset(self::$application_logs[$application->getApplicationName()]))
        {
            self::$application_logs[$application->getApplicationName()] = new FileLock(self::getLogFile($application));
        }

        return self::$application_logs[$application->getApplicationName()];
    }

    private static function getLogFile(Application $application): string
    {
        $logging_directory = $application->getFileLoggingDirectory();

        if(!is_writable($logging_directory))
        {
            throw new LoggingException(sprintf("Cannot write to %s due to insufficient permissions", $logging_directory));
        }

        if(!file_exists($logging_directory))
        {
            mkdir($logging_directory);
        }

        $logging_file = $logging_directory . DIRECTORY_SEPARATOR . Utilities::sanitizeFileName($application->getApplicationName()) . date('Y-m-d') . '.log';

        if(!file_exists($logging_file))
        {
            touch($logging_file);
        }

        return $logging_file;
    }

    private static function getExceptionFile(Application $application, \Throwable $e): string
    {
        $logging_directory = $application->getFileLoggingDirectory();

        if(!is_writable($logging_directory))
        {
            throw new LoggingException(sprintf("Cannot write to %s due to insufficient permissions", $logging_directory));
        }

        if(!file_exists($logging_directory))
        {
            mkdir($logging_directory);
        }

        return Utilities::sanitizeFileName($application->getApplicationName()) . '-' . Utilities::sanitizeFileName(get_class($e)) . '-' . date('d-m-Y-H-i-s') . '.json';
    }

    private static function getTimestamp(): string
    {
        return date('yd/m/y H:i');
    }

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
                $output .=  ' - ' . $item['file'] . ':' . $item['line'] . PHP_EOL;
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