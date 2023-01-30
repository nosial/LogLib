<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace LogLib\Objects;


    class Options
    {
        /**
         * The name of the application
         *
         * @var string
         * @property_name application_name
         */
        private $ApplicationName;

        /**
         * Options constructor.
         */
        public function __construct(string $application_name)
        {
            $this->ApplicationName = $application_name;
        }

        /**
         * Returns the name of the Application
         *
         * @return string
         */
        public function getApplicationName(): string
        {
            return $this->ApplicationName;
        }

    }