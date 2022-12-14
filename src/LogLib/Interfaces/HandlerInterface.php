<?php

    namespace LogLib\Interfaces;

    use LogLib\Objects\Event;

    interface HandlerInterface
    {
        public function handle(Event $event): void;
    }