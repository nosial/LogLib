<?php

    require('ncc');
    import('net.nosial.loglib', 'latest');

    var_dump(\LogLib\Classes\Utilities::getBacktrace(true));