<?php

    namespace LogLib\Classes;

    use LogLib\Abstracts\LevelType;

    class Validate
    {
        /**
         * Validates that the level is valid
         *
         * @param string $level
         * @return bool
         */
        public static function LevelType(string $level): bool
        {
            return in_array($level, LevelType::All);
        }

        /**
         * Checks if the input level matches the current level
         *
         * @param string $input
         * @param string $current_level
         * @return bool
         */
        public static function checkLevelType(string $input, string $current_level): bool
        {
            if($input == null)
                return false;
            if($current_level == null)
                return false;

            $input = strtolower($input);
            if(!Validate::LevelType($input))
                return false;

            $current_level = strtolower($current_level);
            if(!Validate::LevelType($current_level))
                return false;

            switch($current_level)
            {
                case LevelType::Debug:
                    $levels = [
                        LevelType::Debug,
                        LevelType::Verbose,
                        LevelType::Info,
                        LevelType::Warning,
                        LevelType::Fatal,
                        LevelType::Error
                    ];
                    if(in_array($input, $levels))
                        return true;
                    return false;

                case LevelType::Verbose:
                    $levels = [
                        LevelType::Verbose,
                        LevelType::Info,
                        LevelType::Warning,
                        LevelType::Fatal,
                        LevelType::Error
                    ];
                    if(in_array($input, $levels))
                        return true;
                    return false;

                case LevelType::Info:
                    $levels = [
                        LevelType::Info,
                        LevelType::Warning,
                        LevelType::Fatal,
                        LevelType::Error
                    ];
                    if(in_array($input, $levels))
                        return true;
                    return false;

                case LevelType::Warning:
                    $levels = [
                        LevelType::Warning,
                        LevelType::Fatal,
                        LevelType::Error
                    ];
                    if(in_array($input, $levels))
                        return true;
                    return false;

                case LevelType::Error:
                    $levels = [
                        LevelType::Fatal,
                        LevelType::Error
                    ];
                    if(in_array($input, $levels))
                        return true;
                    return false;

                case LevelType::Fatal:
                    if($input == LevelType::Fatal)
                        return true;
                    return false;

                default:
                case LevelType::Silent:
                    return false;
            }
        }
    }