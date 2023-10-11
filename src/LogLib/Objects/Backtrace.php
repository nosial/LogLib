<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects;

    class Backtrace
    {
        /**
         * @var string|null
         */
        private $function;

        /**
         * @var int|null
         */
        private $line;

        /**
         * @var string|null
         */
        private $file;

        /**
         * @var string|null
         */
        private $class;

        /**
         * @see CallType
         * @var string|null
         */
        private $type;

        /**
         * @var array|null
         */
        private $args;

        /**
         * Public Constructor
         *
         * @param array|null $backtrace
         */
        public function __construct(?array $backtrace=null)
        {
            if($backtrace === null)
            {
                return;
            }

            $this->function = $backtrace['function'] ?? null;
            $this->line = $backtrace['line'] ?? null;
            $this->file = $backtrace['file'] ?? null;
            $this->class = $backtrace['class'] ?? null;
            $this->type = $backtrace['type'] ?? null;
            $this->args = $backtrace['args'] ?? null;
        }

        /**
         * Optional. Returns the function name of the backtrace
         *
         * @return string|null
         */
        public function getFunction(): ?string
        {
            return $this->function;
        }

        /**
         * Sets the function name of the backtrace
         *
         * @param string|null $function
         */
        public function setFunction(?string $function): void
        {
            $this->function = $function;
        }

        /**
         * Optional. Returns the line number of the backtrace
         *
         * @return int|null
         */
        public function getLine(): ?int
        {
            return $this->line;
        }

        /**
         * Sets the line number of the backtrace
         *
         * @param int|null $line
         */
        public function setLine(?int $line): void
        {
            $this->line = $line;
        }

        /**
         * Optional. Returns the file name of the backtrace
         *
         * @return string|null
         */
        public function getFile(): ?string
        {
            return $this->file;
        }

        /**
         * Sets the file name of the backtrace
         *
         * @param string|null $file
         */
        public function setFile(?string $file): void
        {
            $this->file = $file;
        }

        /**
         * Optional. Returns the class name, if any, of the backtrace
         *
         * @return string|null
         */
        public function getClass(): ?string
        {
            return $this->class;
        }

        /**
         * Sets the class name, if any, of the backtrace
         *
         * @param string|null $class
         */
        public function setClass(?string $class): void
        {
            $this->class = $class;
        }

        /**
         * Optional. Returns the current call type. If a method call, "->" is returned.
         *
         * @return string|null
         */
        public function getType(): ?string
        {
            return $this->type;
        }

        /**
         * Sets the current call type. If a method call, "->" is returned.
         *
         * @param string|null $type
         */
        public function setType(?string $type): void
        {
            $this->type = $type;
        }

        /**
         * Optional. Return the functions arguments or included file name(s)
         *
         * @return array|null
         */
        public function getArgs(): ?array
        {
            return $this->args;
        }

        /**
         * Sets the function arguments or included file name(s)
         *
         * @param array|null $args
         */
        public function setArgs(?array $args): void
        {
            $this->args = $args;
        }
    }