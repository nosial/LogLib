<?php

    namespace LogLib\Abstracts;

    final class LevelType
    {
        /**
         * Silent type.
         */
        public const SILENT = 0;

        /**
         * Fatal type.
         */
        public const FATAL = 1;

        /**
         * Error type.
         */
        public const ERROR = 2;

        /**
         *
         */
        public const WARNING = 3;

        /**
         * Information type.
         */
        public const INFO = 4;

        /**
         * Verbose type.
         */
        public const VERBOSE = 5;

        /**
         * Debug type.
         */
        public const DEBUG = 6;

        /**
         * All types.
         */
        public const ALL = [
            self::SILENT,
            self::FATAL,
            self::ERROR,
            self::WARNING,
            self::INFO,
            self::VERBOSE,
            self::DEBUG
        ];
    }