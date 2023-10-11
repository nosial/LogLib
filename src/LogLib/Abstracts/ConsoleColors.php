<?php

    namespace LogLib\Abstracts;

    final class ConsoleColors
    {
        public const BLACK = "0;30";

        public const DARK_GRAY = "1;30";

        public const BLUE = "0;34";

        public const LIGHT_BLUE = "1;34";

        public const GREEN = "0;32";

        public const LIGHT_GREEN = "1;32";

        public const CYAN = "0;36";

        public const LIGHT_CYAN = "1;36";

        public const RED = "0;31";

        public const LIGHT_RED = "1;31";

        public const PURPLE = "0;35";

        public const LIGHT_PURPLE = "1;35";

        public const BROWN = "0;33";

        public const YELLOW = "1;33";

        public const LIGHT_GRAY = "0;37";

        public const WHITE = "1;37";

        public const RESET = "0";

        /**
         * Represents an array of all possible supported color values.
         *
         * @var array
         */
        public const ALL = [
            self::BLACK,
            self::DARK_GRAY,
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

        /**
         * A list of random usable bright colors
         */
        public const BRIGHT_COLORS = [
            self::LIGHT_BLUE,
            self::LIGHT_GREEN,
            self::LIGHT_CYAN,
            self::LIGHT_RED,
            self::LIGHT_PURPLE,
        ];
    }