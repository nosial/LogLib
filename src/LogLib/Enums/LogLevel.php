<?php

    namespace LogLib\Enums;

    enum LogLevel : int
    {
        /**
         * Silent type.
         */
        case SILENT = 0;

        /**
         * Fatal type.
         */
        case FATAL = 1;

        /**
         * Error type.
         */
        case ERROR = 2;

        /**
         *
         */
        case WARNING = 3;

        /**
         * Information type.
         */
        case INFO = 4;

        /**
         * Verbose type.
         */
        case VERBOSE = 5;

        /**
         * Debug type.
         */
        case DEBUG = 6;

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