<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects;

    class Backtrace
    {
        /**
         * The function name of the backtrace
         *
         * @var string|null
         * @property_name function
         */
        public $Function;

        /**
         * The line number of the backtrace
         *
         * @var int|null
         * @property_name line
         */
        public $Line;

        /**
         * The file name of the backtrace
         *
         * @var string|null
         * @property_name file
         */
        public $File;

        /**
         * The class name, if any, of the backtrace
         *
         * @var string|null
         * @property_name class
         */
        public $Class;

        /**
         * The current call type. If a method call, "->" is returned.
         * If a static method call, "::" is returned. If a function call,
         * nothing is returned.
         *
         * @see CallType
         * @var string|null
         * @property_name type
         */
        public $Type;

        /**
         * If inside a function, this lists the functions arguments. If inside
         * an included file, this lists the included file name(s).
         *
         * @var array|null
         * @property_name args
         */
        public $Args;
    }