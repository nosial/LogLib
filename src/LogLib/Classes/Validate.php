<?php

    namespace LogLib\Classes;

    use LogLib\Abstracts\LevelType;

    class Validate
    {
        /**
         * Checks if the given level is a valid level type.
         *
         * @param string $level The level to check.
         * @return bool Returns true if the level is valid
         */
        public static function LevelType(string $level): bool
        {
            return in_array($level, LevelType::ALL);
        }

        /**
         * Checks if the given input level is valid for the current level.
         *
         * @param string $input The input level to check.
         * @param string $current_level The current level to compare against.
         * @return bool Returns true if the input level is valid for the current level, false otherwise.
         */
        public static function checkLevelType(string $input, string $current_level): bool
        {
            $input = strtolower($input);
            if(!self::LevelType($input))
            {
                return false;
            }

            $current_level = strtolower($current_level);
            if(!self::LevelType($current_level))
            {
                return false;
            }

            switch($current_level)
            {
                case LevelType::DEBUG:
                    $levels = [
                        LevelType::DEBUG,
                        LevelType::VERBOSE,
                        LevelType::INFO,
                        LevelType::WARNING,
                        LevelType::FATAL,
                        LevelType::ERROR
                    ];

                    return in_array($input, $levels, true);

                case LevelType::VERBOSE:
                    $levels = [
                        LevelType::VERBOSE,
                        LevelType::INFO,
                        LevelType::WARNING,
                        LevelType::FATAL,
                        LevelType::ERROR
                    ];

                    return in_array($input, $levels, true);

                case LevelType::INFO:
                    $levels = [
                        LevelType::INFO,
                        LevelType::WARNING,
                        LevelType::FATAL,
                        LevelType::ERROR
                    ];

                    return in_array($input, $levels, true);

                case LevelType::WARNING:
                    $levels = [
                        LevelType::WARNING,
                        LevelType::FATAL,
                        LevelType::ERROR
                    ];

                    return in_array($input, $levels, true);

                case LevelType::ERROR:
                    $levels = [
                        LevelType::FATAL,
                        LevelType::ERROR
                    ];

                    return in_array($input, $levels, true);

                case LevelType::FATAL:
                    return $input === LevelType::FATAL;

                default:
                case LevelType::SILENT:
                    return false;
            }
        }
    }