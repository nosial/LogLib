<?php

    use LogLib\Log;
    use LogLib\Objects\Options;

    require('ncc');
    import('net.nosial.loglib', 'latest');

    $options = new Options('net.nosial.optslib');
    Log::register($options);

    Log::debug('net.nosial.optslib', 'This is a debug message');
    Log::verbose('net.nosial.optslib', 'This is a verbose message');
    Log::info('net.nosial.optslib', 'This is an info message');
    Log::warning('net.nosial.optslib', 'This is a warning message');
    Log::error('net.nosial.optslib', 'This is an error message');
    Log::fatal('net.nosial.optslib', 'This is a fatal message');


    class test
    {
        public function testLogging(): void
        {
            Log::debug('net.nosial.optslib', 'This is a debug message');
            Log::verbose('net.nosial.optslib', 'This is a verbose message');
            Log::info('net.nosial.optslib', 'This is an info message');
            Log::warning('net.nosial.optslib', 'This is a warning message');
            Log::error('net.nosial.optslib', 'This is an error message');
            Log::fatal('net.nosial.optslib', 'This is a fatal message');
        }
    }

    $test = new test();
    $test->testLogging();

    eval('\LogLib\Log::debug(\'net.nosial.optslib\', \'This is a debug message\');');
    eval('\LogLib\Log::verbose(\'net.nosial.optslib\', \'This is a verbose message\');');

    $callable = static function()
    {
        Log::info('net.nosial.optslib', 'This is an info message');
        Log::warning('net.nosial.optslib', 'This is a warning message');
        Log::error('net.nosial.optslib', 'This is an error message');
        Log::fatal('net.nosial.optslib', 'This is a fatal message');
    };

    $callable();


    class test2
    {
        public function testEval(): void
        {
            eval('\LogLib\Log::debug(\'net.nosial.optslib\', \'This is a debug message\');');
            eval('\LogLib\Log::verbose(\'net.nosial.optslib\', \'This is a verbose message\');');
        }

        public function testCallable(): void
        {
            $b = static function()
            {
                Log::info('net.nosial.optslib', 'This is an info message');
                Log::warning('net.nosial.optslib', 'This is a warning message');
                Log::error('net.nosial.optslib', 'This is an error message');
                Log::fatal('net.nosial.optslib', 'This is a fatal message');
            };

            $b();
        }
    }

    $test2 = new test2();
    $test2->testEval();
    $test2->testCallable();