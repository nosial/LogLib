<?php

    namespace LogLib\Classes;

    use LogLib\Abstracts\LevelType;
    use LogLib\Log;
    use LogLib\Objects\Event;
    use LogLib\Objects\FileLogging\FileHandle;
    use LogLib\Objects\Options;
    use ncc\Utilities\Functions;

    class FileLogging
    {
        /**
         * Writes the event to the file log
         *
         * @param Options $options
         * @param Event $event
         * @param FileHandle|null $fileHandle
         * @return void
         */
        public static function out(Options $options, Event $event, ?FileHandle $fileHandle=null): void
        {
            $backtrace_output = Utilities::parseBacktrace($event);
            $handle = $fileHandle ?? $options->getFileHandle();

            switch($event->Level)
            {
                // Only process Debug/Verbose events if the log level is set to Debug/Verbose
                // otherwise omit it because it could be a performance hit if there are a lot of
                // debug/verbose events being logged.
                case LevelType::Debug:
                case LevelType::Verbose:
                    if(!Validate::checkLevelType($event->Level, Log::getRuntimeOptions()->getLogLevel()))
                        return;
                    break;

                default:
                    break;
            }

            $handle->fwrite(sprintf(
                "%s [%s] [%s] (%s) - %s" . PHP_EOL,
                $event->getTimestamp(),
                $options->getApplicationName(),
                Utilities::levelToString($event->Level),
                $backtrace_output !== null ? $backtrace_output : 'lambda',
                $event->Message
            ));

            if($event->Exception !== null)
                self::dumpException($options, $event);

            if($fileHandle == null && Log::getRuntimeOptions()->getOutputLogHandle() !== null)
                self::out($options, $event, Log::getRuntimeOptions()->getOutputLogHandle());
        }

        /**
         * Dumps an exception to a file
         *
         * @param Options $options
         * @param Event $event
         * @return string|null
         */
        public static function dumpException(Options $options, Event $event): ?string
        {
            if($options->dumpExceptionsEnabled() && $options->getPackageDataPath() !== null)
                return null;

            $exceptions_path = $options->getPackageDataPath() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'exceptions';
            if(!is_dir($exceptions_path))
                mkdir($exceptions_path, 0777, true);


            $exception_type = str_replace('\\', '_', strtolower($event->Exception['type']));
            $exception_file = sprintf('%s_%s_%s.json', date('Y-m-d'), $exception_type, Functions::randomString(12));

            $handle = fopen($exception_file, 'w');
            fwrite($handle, json_encode($event->Exception, JSON_PRETTY_PRINT));
            fclose($handle);

            return $exception_file;
        }
    }