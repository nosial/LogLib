<?php

    namespace LogLib\Abstracts;

    abstract class LevelType
    {
        const Silent = 0;
        const Fatal = 1;
        const Error = 2;
        const Warning = 3;
        const Info = 4;
        const Verbose = 5;
        const Debug = 6;

        /**
         * All types.
         */
        const All = [
            self::Silent,
            self::Fatal,
            self::Error,
            self::Warning,
            self::Info,
            self::Verbose,
            self::Debug
        ];
    }