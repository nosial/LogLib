<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects;


    class Options
    {
        /**
         * @var string
         */
        private $application_name;

        /**
         * Options constructor.
         */
        public function __construct(string $application_name)
        {
            $this->application_name = $application_name;
        }

        /**
         * Returns the name of the Application
         *
         * @return string
         */
        public function getApplicationName(): string
        {
            return $this->application_name;
        }

    }