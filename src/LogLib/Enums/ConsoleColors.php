<?php

    namespace LogLib\Enums;

    enum ConsoleColors : string
    {
        case BLUE = "0;34";

        case LIGHT_BLUE = "1;34";

        case GREEN = "0;32";

        case LIGHT_GREEN = "1;32";

        case CYAN = "0;36";

        case LIGHT_CYAN = "1;36";

        case RED = "0;31";

        case LIGHT_RED = "1;31";

        case PURPLE = "0;35";

        case LIGHT_PURPLE = "1;35";

        case BROWN = "0;33";

        case YELLOW = "1;33";

        case LIGHT_GRAY = "0;37";

        case WHITE = "1;37";

        case RESET = "0";

        /**
         * Represents an array of all possible supported color values.
         *
         * @var array
         */
        public const ALL = [
            self::BLUE,
            self::LIGHT_BLUE,
            self::GREEN,
            self::LIGHT_GREEN,
            self::CYAN,
            self::LIGHT_CYAN,
            self::RED,
            self::LIGHT_RED,
            self::PURPLE,
            self::LIGHT_PURPLE,
            self::BROWN,
            self::YELLOW,
            self::LIGHT_GRAY,
            self::WHITE
        ];
    }