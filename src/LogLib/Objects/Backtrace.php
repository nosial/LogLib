<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects;

    class Backtrace
    {
        /**
         * The function name of the backtrace
         *
         * @var string|null
         */
        public $Function;

        /**
         * The line number of the backtrace
         *
         * @var int|null
         */
        public $Line;

        /**
         * The file name of the backtrace
         *
         * @var string|null
         */
        public $File;

        /**
         * The class name, if any, of the backtrace
         *
         * @var string|null
         */
        public $Class;

        /**
         * The current call type. If a method call, "->" is returned.
         * If a static method call, "::" is returned. If a function call,
         * nothing is returned.
         *
         * @see CallType
         * @var string|null
         */
        public $Type;

        /**
         * If inside a function, this lists the functions arguments. If inside
         * an included file, this lists the included file name(s).
         *
         * @var array|null
         */
        public $Args;

        /**
         * Returns an array representation of the backtrace
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                'function' => $this->Function,
                'line' => $this->Line,
                'file' => $this->File,
                'class' => $this->Class,
                'type' => $this->Type,
                'args' => $this->Args
            ];
        }

        /**
         * Constructs a new DebugBacktrace object from an array representation
         *
         * @param array $array
         * @return Backtrace
         */
        public static function fromArray(array $array): Backtrace
        {
            $backtrace = new Backtrace();
            $backtrace->Function = ($array['function'] ?? null);
            $backtrace->Line = ($array['line'] ?? null);
            $backtrace->File = ($array['file'] ?? null);
            $backtrace->Class = ($array['class'] ?? null);
            $backtrace->Type = ($array['type'] ?? null);
            $backtrace->Args = ($array['args'] ?? null);
            return $backtrace;
        }
    }