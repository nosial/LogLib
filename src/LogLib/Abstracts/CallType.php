<?php

    namespace LogLib\Abstracts;

    final class CallType
    {
        /**
         * Represents a method call.
         *
         * @var string METHOD_CALL
         */
        public const METHOD_CALL = '->';

        /**
         * Represents a static method call.
         *
         * @var string STATIC_CALL
         */
        public const STATIC_CALL = '::';

        /**
         * Represents a function call.
         *
         * @var string FUNCTION_CALL
         */
        public const FUNCTION_CALL = '()';

        /**
         * Represents a lambda function call.
         *
         * @var string LAMBDA_CALL
         */
        public const LAMBDA_CALL = 'λ';

        /**
         * Represents an eval() call.
         *
         * @var string EVAL_CALL
         */
        public const EVAL_CALL = 'eval()';
    }