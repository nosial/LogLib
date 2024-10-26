<?php

    namespace LogLib\Classes;

    use LogLib\Abstracts\CallType;
    use LogLib\Abstracts\LevelType;
    use LogLib\Objects\Event;
    use OptsLib\Parse;
    use Throwable;

    class Utilities
    {
        /**
         * Returns a backtrace of the calling code.
         *
         * @return array An array containing backtrace information.
         */
        public static function getBacktrace(): array
        {
            if(!function_exists('debug_backtrace'))
            {
                return [];
            }

            return debug_backtrace();
        }

        /**
         * Converts a log level to its corresponding string representation.
         *
         * @param int $level The log level to convert.
         * @return string The string representation of the log level.
         */
        public static function levelToString(int $level): string
        {
            return match ($level)
            {
                LevelType::DEBUG => 'DBG',
                LevelType::VERBOSE => 'VRB',
                LevelType::INFO => 'INF',
                LevelType::WARNING => 'WRN',
                LevelType::FATAL => 'CRT',
                LevelType::ERROR => 'ERR',
                default => 'UNK',
            };
        }

        /**
         * Determines whether the application is currently running in the command line interface (CLI) mode.
         *
         * @return bool true if running in CLI mode, false otherwise.
         */
        public static function runningInCli(): bool
        {
            if(function_exists('php_sapi_name'))
            {
                /** @noinspection ConstantCanBeUsedInspection */
                return strtolower(php_sapi_name()) === 'cli';
            }

            if(defined('PHP_SAPI'))
            {
                return strtolower(PHP_SAPI) === 'cli';
            }

            return false;
        }

        /**
         * Returns the log level based on the configuration.
         *
         * @return int The log level. This value represents the severity or importance of the log messages.
         *             The returned value will be one of the constants defined in the LevelType class:
         *                 - DEBUG (6)
         *                 - VERBOSE (5)
         *                 - INFO (4)
         *                 - WARNING (3)
         *                 - ERROR (2)
         *                 - FATAL (1)
         *                 - SILENT (0)
         *             If no log level is configured or the configured level is not recognized, the INFO level (4) will be returned by default.
         */
        public static function getLogLevel(): int
        {
            $args = Parse::getArguments();

            switch(strtolower(($args['log'] ?? $args['log-level'] ?? (getenv('LOG_LEVEL') ?: 'info') ?? 'info')))
            {
                case LevelType::DEBUG:
                case 'debug':
                case '6':
                case 'dbg':
                    return LevelType::DEBUG;

                case LevelType::VERBOSE:
                case 'verbose':
                case '5':
                case 'vrb':
                    return LevelType::VERBOSE;

                default:
                case LevelType::INFO:
                case 'info':
                case '4':
                case 'inf':
                    return LevelType::INFO;

                case LevelType::WARNING:
                case 'warning':
                case '3':
                case 'wrn':
                    return LevelType::WARNING;

                case LevelType::ERROR:
                case 'error':
                case '2':
                case 'err':
                    return LevelType::ERROR;

                case LevelType::FATAL:
                case 'fatal':
                case '1':
                case 'crt':
                    return LevelType::FATAL;

                case LevelType::SILENT:
                case 'silent':
                case '0':
                case 'sil':
                    return LevelType::SILENT;
            }
        }

        /**
         * Checks if ANSI escape sequences should be displayed in the output.
         *
         * @return bool Returns true if ANSI escape sequences should be displayed, false otherwise.
         */
        public static function getDisplayAnsi(): bool
        {
            $args = Parse::getArguments();
            $display_ansi = ($args['display-ansi'] ?? $args['ansi'] ?? null);

            if($display_ansi === null)
            {
                return true;
            }

            // Strict boolean response
            return strtolower($display_ansi) === 'true' || $display_ansi === '1';
        }

        /**
         * Returns a string representation of the backtrace for the given event.
         *
         * @param Event $event The event object for which to generate the backtrace string.
         * @param bool $ansi Determines whether the output should include ANSI escape codes for colored output. Default is false.
         * @return string|null A string representation of the backtrace for the event, or null if the event has no backtrace.
         *                    The output format is: ClassName::methodName() or functionName() depending on the type of call.
         *                    If $ansi is true, the output will be colored using ANSI escape codes.
         *                    If the event has no backtrace, the constant CallType::LAMBDA_CALL will be returned.
         */
        public static function getTraceString(Event $event, bool $ansi=false): ?string
        {
            if($event->getBacktrace() === null || count($event->getBacktrace()) === 0)
            {
                return CallType::LAMBDA_CALL;
            }

            $backtrace = $event->getBacktrace()[count($event->getBacktrace()) - 1];

            // Ignore \LogLib namespace
            if(isset($backtrace['class']) && str_starts_with($backtrace['class'], 'LogLib'))
            {
                if(isset($backtrace['file']))
                {
                    return ($ansi ? "\033[1;37m" : '') . basename($backtrace['file']) . ($ansi ? "\033[0m" : '');
                }

                return basename($backtrace['file']);
            }

            if($backtrace['function'] === '{closure}')
            {
                if(isset($backtrace['file']))
                {
                    return ($ansi ? "\033[1;37m" : '') . basename($backtrace['file']) . ($ansi ? "\033[0m" : '') . CallType::STATIC_CALL . CallType::LAMBDA_CALL;
                }

                return basename($backtrace['file']) . CallType::STATIC_CALL . CallType::LAMBDA_CALL;
            }

            if($backtrace['function'] === 'eval')
            {
                if(isset($backtrace['file']))
                {
                    return ($ansi ? "\033[1;37m" : '') . basename($backtrace['file']) . ($ansi ? "\033[0m" : '') . CallType::STATIC_CALL . CallType::EVAL_CALL;
                }

                return basename($backtrace['file']) . CallType::STATIC_CALL . CallType::EVAL_CALL;
            }

            if($ansi)
            {
                $function = sprintf("\033[1;37m%s\033[0m", $backtrace['function']);
                $class = null;

                if(isset($backtrace["class"]))
                {
                    $class = sprintf("\033[1;37m%s\033[0m", $backtrace['class']);
                }
            }
            else
            {
                $function = $backtrace['function'];
                $class = $backtrace['class'] ?? null;
            }

            if($class === null)
            {
                return $function . CallType::FUNCTION_CALL;
            }

            $type = ($backtrace['type'] === CallType::METHOD_CALL ? CallType::METHOD_CALL : CallType::STATIC_CALL);
            return "{$class}{$type}{$function}" . CallType::FUNCTION_CALL;
        }


        /**
         * Converts an exception object to an array representation.
         *
         * @param Throwable $e The exception object to convert.
         * @return array An array containing the details of the exception.
         *               The array includes the exception message, code, file, line, and a formatted trace.
         *               The trace is formatted as a string containing the file, line, class, type, and function for each call in the traceback.
         */
        public static function exceptionToArray(Throwable $e): array
        {
            $trace = $e->getTrace();
            $trace_string = '';

            foreach($trace as $t)
            {
                $trace_string .= "\t{$t['file']}:{$t['line']} {$t['class']}{$t['type']}{$t['function']}()\n";
            }

            return [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $trace_string,
            ];
        }

    }