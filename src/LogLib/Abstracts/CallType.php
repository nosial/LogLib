<?php

    namespace LogLib\Abstracts;

    abstract class CallType
    {
        const MethodCall = '->';
        const StaticCall = '::';
        const FunctionCall = ' ';
    }