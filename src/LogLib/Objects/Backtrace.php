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
        private $Function;

        /**
         * The line number of the backtrace
         *
         * @var int|null
         * @property_name line
         */
        private $Line;

        /**
         * The file name of the backtrace
         *
         * @var string|null
         * @property_name file
         */
        private $File;

        /**
         * The class name, if any, of the backtrace
         *
         * @var string|null
         * @property_name class
         */
        private $Class;

        /**
         * The current call type. If a method call, "->" is returned.
         * If a static method call, "::" is returned. If a function call,
         * nothing is returned.
         *
         * @see CallType
         * @var string|null
         * @property_name type
         */
        private $Type;

        /**
         * If inside a function, this lists the functions arguments. If inside
         * an included file, this lists the included file name(s).
         *
         * @var array|null
         * @property_name args
         */
        private $Args;

        /**
         * Public Constructor
         *
         * @param array|null $backtrace
         */
        public function __construct(?array $backtrace=null)
        {
            if($backtrace === null)
                return;

            $this->Function = $backtrace['function'] ?? null;
            $this->Line = $backtrace['line'] ?? null;
            $this->File = $backtrace['file'] ?? null;
            $this->Class = $backtrace['class'] ?? null;
            $this->Type = $backtrace['type'] ?? null;
            $this->Args = $backtrace['args'] ?? null;
        }

        /**
         * @return string|null
         */
        public function getFunction(): ?string
        {
            return $this->Function;
        }

        /**
         * @param string|null $Function
         */
        public function setFunction(?string $Function): void
        {
            $this->Function = $Function;
        }

        /**
         * @return int|null
         */
        public function getLine(): ?int
        {
            return $this->Line;
        }

        /**
         * @param int|null $Line
         */
        public function setLine(?int $Line): void
        {
            $this->Line = $Line;
        }

        /**
         * @return string|null
         */
        public function getFile(): ?string
        {
            return $this->File;
        }

        /**
         * @param string|null $File
         */
        public function setFile(?string $File): void
        {
            $this->File = $File;
        }

        /**
         * @return string|null
         */
        public function getClass(): ?string
        {
            return $this->Class;
        }

        /**
         * @param string|null $Class
         */
        public function setClass(?string $Class): void
        {
            $this->Class = $Class;
        }

        /**
         * @return string|null
         */
        public function getType(): ?string
        {
            return $this->Type;
        }

        /**
         * @param string|null $Type
         */
        public function setType(?string $Type): void
        {
            $this->Type = $Type;
        }

        /**
         * @return array|null
         */
        public function getArgs(): ?array
        {
            return $this->Args;
        }

        /**
         * @param array|null $Args
         */
        public function setArgs(?array $Args): void
        {
            $this->Args = $Args;
        }
    }