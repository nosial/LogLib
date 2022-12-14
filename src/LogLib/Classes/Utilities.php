<?php

    namespace LogLib\Classes;

    use LogLib\Abstracts\LevelType;
    use LogLib\Objects\Backtrace;
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
                $results[] = Backtrace::fromArray($trace);
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

    }