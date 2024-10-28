<?php

namespace LogLib\Enums;

enum LogHandlerType : string
{
    case CONSOLE = 'console';
    case FILE = 'file';
}
