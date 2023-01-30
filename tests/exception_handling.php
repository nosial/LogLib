<?php

    use LogLib\Abstracts\LevelType;
    use LogLib\Log;
    use LogLib\Objects\Options;

    require('ncc');
    import('net.nosial.loglib', 'latest');

    $options = new Options('net.nosial.optslib');
    Log::register($options);
    Log::registerExceptionHandler();

    Log::debug('net.nosial.optslib', 'This is a debug message');
    Log::verbose('net.nosial.optslib', 'This is a verbose message');
    Log::info('net.nosial.optslib', 'This is an info message');
    Log::warning('net.nosial.optslib', 'This is a warning message');
    Log::error('net.nosial.optslib', 'This is an error message');
    Log::fatal('net.nosial.optslib', 'This is a fatal message');

    throw new Exception('This is an exception');