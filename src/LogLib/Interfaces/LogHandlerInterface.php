<?php

namespace LogLib\Interfaces;

use LogLib\Enums\LogHandlerType;
use LogLib\Exceptions\LoggingException;
use LogLib\Objects\Application;
use LogLib\Objects\Event;
use LogLib\Objects\Options;

interface LogHandlerInterface
{
    /**
     * Outputs the event details based on the given options.
     *
     * @param Application $application The options used to configure the output
     * @param Event $event The event to be output
     * @return void
     * @throws LoggingException If an error occurs while handling the event
     */
    public static function handle(Application $application, Event $event): void;

    /**
     * Returns the type of log handler.
     *
     * @return LogHandlerType
     */
    public static function getType(): LogHandlerType;
}