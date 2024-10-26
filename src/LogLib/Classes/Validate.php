<?php

    namespace LogLib\Classes;

    use LogLib\Enums\LogLevel;

    class Validate
    {
        /**
         * Checks if the given input level is valid for the current level.
         *
         * @param LogLevel $input The input level to check.
         * @param LogLevel $current_level The current level to compare against.
         * @return bool Returns true if the input level is valid for the current level, false otherwise.
         */
        public static function checkLevelType(LogLevel $input, LogLevel $current_level): bool
        {
            switch($current_level)
            {
                case LogLevel::DEBUG:
                    $levels = [
                        LogLevel::DEBUG,
                        LogLevel::VERBOSE,
                        LogLevel::INFO,
                        LogLevel::WARNING,
                        LogLevel::FATAL,
                        LogLevel::ERROR
                    ];

                    return in_array($input, $levels, true);

                case LogLevel::VERBOSE:
                    $levels = [
                        LogLevel::VERBOSE,
                        LogLevel::INFO,
                        LogLevel::WARNING,
                        LogLevel::FATAL,
                        LogLevel::ERROR
                    ];

                    return in_array($input, $levels, true);

                case LogLevel::INFO:
                    $levels = [
                        LogLevel::INFO,
                        LogLevel::WARNING,
                        LogLevel::FATAL,
                        LogLevel::ERROR
                    ];

                    return in_array($input, $levels, true);

                case LogLevel::WARNING:
                    $levels = [
                        LogLevel::WARNING,
                        LogLevel::FATAL,
                        LogLevel::ERROR
                    ];

                    return in_array($input, $levels, true);

                case LogLevel::ERROR:
                    $levels = [
                        LogLevel::FATAL,
                        LogLevel::ERROR
                    ];

                    return in_array($input, $levels, true);

                case LogLevel::FATAL:
                    return $input == LogLevel::FATAL;

                case LogLevel::SILENT:
                    return false;
            }

            return false;
        }
    }