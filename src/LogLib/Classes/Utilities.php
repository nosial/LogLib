<?php

    namespace LogLib\Classes;

    use LogLib\Abstracts\LevelType;
    use LogLib\Objects\Backtrace;
    use LogLib\Objects\Event;
    use OptsLib\Parse;
    use Properties\Prop;
    use Throwable;

    class Utilities
    {
        /**
         * Returns the current backtrace
         *
         * @return Backtrace[]
         */
        public static function getBacktrace(): array
        {
            if(!function_exists('debug_backtrace'))
                return [];

            $backtrace = debug_backtrace();
            $results = [];

            foreach($backtrace as $trace)
            {
                $results[] = Prop::fromArray($trace);
            }

            return $results;
        }

        /**
         * @param Throwable $e
         * @return array
         */
        public static function exceptionToArray(Throwable  $e): array
        {
            $results = [
                'hash' => spl_object_hash($e),
                'type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ];

            if($e->getPrevious() !== null)
            {
                $results['previous'] = self::exceptionToArray($e->getPrevious());
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

            $selected_level = ($args['log'] ?? $args['log-level'] ?? null);

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

                default:
                    return LevelType::Info;
            }
        }

        /**
         * Returns the output log path from the command line arguments
         *
         * @return string|null
         */
        public static function getOutputLogPath(): ?string
        {
            $args = Parse::getArguments();
            $path = ($args['log-path'] ?? $args['log-file'] ?? null);

            if($path === null)
                return null;

            return $path;
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
        public static function getLogFilename()
        {
           return date('Y-m-d') . '.log';
        }

        /**
         * Returns the formatted backtrace
         *
         * @param Event $event
         * @return string|null
         */
        public static function parseBacktrace(Event $event): ?string
        {
            $backtrace = null;
            if ($event->Backtrace !== null && count($event->Backtrace) > 0)
            {
                foreach ($event->Backtrace as $item)
                {
                    if ($item->Class !== 'LogLib\\Log')
                    {
                        $backtrace = $item;
                        break;
                    }
                }
            }

            $backtrace_output = null;
            if ($backtrace !== null)
            {
                if ($backtrace->Class !== null)
                {
                    $backtrace_output = $backtrace->Class . $backtrace->Type . $backtrace->Function . '()';
                }
                else
                {
                    $backtrace_output = $backtrace->Function . '()';
                }

                if ($backtrace->Line !== null)
                    $backtrace_output .= ':' . $backtrace->Line;
            }

            return $backtrace_output;
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

    }