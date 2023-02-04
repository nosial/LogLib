<?php

    namespace LogLib\Classes;

    use LogLib\Abstracts\LevelType;
    use LogLib\Objects\Backtrace;
    use LogLib\Objects\Event;
    use OptsLib\Parse;
    use Throwable;

    class Utilities
    {
        /**
         * Returns the current backtrace
         *
         * @param bool $full
         * @return array
         */
        public static function getBacktrace(bool $full=false): array
        {
            if(!function_exists('debug_backtrace'))
                return [];

            $backtrace = debug_backtrace();
            $results = [];

            foreach($backtrace as $trace)
            {
                if(isset($trace['class'] ) && str_contains($trace['class'], 'LogLib') && !$full)
                    continue;

                $results[] = new Backtrace($trace);
            }

            return $results;
        }

        /**
         * Returns the current level type as a string
         *
         * @param int $level
         * @return string
         */
        public static function levelToString(int $level): string
        {
            return match ($level)
            {
                LevelType::Debug => 'DBG',
                LevelType::Verbose => 'VRB',
                LevelType::Info => 'INF',
                LevelType::Warning => 'WRN',
                LevelType::Fatal => 'CRT',
                LevelType::Error => 'ERR',
                default => 'UNK',
            };
        }

        /**
         * A simple method to determine if the current environment is a CLI environment
         *
         * @return bool
         */
        public static function runningInCli(): bool
        {
            if(function_exists('php_sapi_name'))
            {
                return strtolower(php_sapi_name()) === 'cli';
            }

            if(defined('PHP_SAPI'))
            {
                return strtolower(PHP_SAPI) === 'cli';
            }

            return false;
        }

        /**
         * Attempts to determine the current log level from the command line arguments
         *
         * @return int
         */
        public static function getLogLevel(): int
        {
            $args = Parse::getArguments();

            $selected_level = ($args['log'] ?? $args['log-level'] ?? (getenv('LOG_LEVEL') ?: null) ?? null);

            if($selected_level === null)
                return LevelType::Info;

            switch(strtolower($selected_level))
            {
                case LevelType::Debug:
                case 'debug':
                case '6':
                case 'dbg':
                    return LevelType::Debug;

                case LevelType::Verbose:
                case 'verbose':
                case '5':
                case 'vrb':
                    return LevelType::Verbose;

                default:
                case LevelType::Info:
                case 'info':
                case '4':
                case 'inf':
                    return LevelType::Info;

                case LevelType::Warning:
                case 'warning':
                case '3':
                case 'wrn':
                    return LevelType::Warning;

                case LevelType::Error:
                case 'error':
                case '2':
                case 'err':
                    return LevelType::Error;

                case LevelType::Fatal:
                case 'fatal':
                case '1':
                case 'crt':
                    return LevelType::Fatal;

                case LevelType::Silent:
                case 'silent':
                case '0':
                case 'sil':
                    return LevelType::Silent;
            }
        }

        /**
         * @return bool
         */
        public static function getDisplayAnsi(): bool
        {
            $args = Parse::getArguments();
            $display_ansi = ($args['display-ansi'] ?? $args['ansi'] ?? null);

            if($display_ansi === null)
                return true;

            // Strict boolean response
            return strtolower($display_ansi) === 'true' || $display_ansi === '1';
        }

        /**
         * Returns the current active log file name, the current value can
         * change depending on the date/time, if it has changed; close the
         * old file and open a new one.
         *
         * @return string
         */
        public static function getLogFilename(): string
        {
           return date('Y-m-d') . '.log';
        }

        /**
         * Returns a random string of characters
         *
         * @param int $length
         * @return string
         */
        public static function randomString(int $length = 32): string
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++)
            {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        /**
         * @param Event $event
         * @param bool $ansi
         * @return string|null
         */
        public static function getTraceString(Event $event, bool $ansi=false): ?string
        {
            if($event->getBacktrace() == null)
                return 'λ';

            $backtrace = $event->getBacktrace()[0];
            $function = $backtrace->getFunction();
            $class = $backtrace->getClass();

            if($ansi)
            {
                $function = "\033[1;37m$function\033[0m";
                $class = "\033[1;37m$class\033[0m";
            }

            if($class == null)
                return "{$function}()";

            $type = ($backtrace->getType() == '->' ? '->' : '::');
            return "{$class}{$type}{$function}()";
        }

        /**
         * Returns an array representation of a throwable exception
         *
         * @param Throwable $e
         * @return array
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