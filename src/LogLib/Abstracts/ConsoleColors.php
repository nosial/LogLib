<?php

    namespace LogLib\Abstracts;

    abstract class ConsoleColors
    {
        const Black = "0;30";
        const DarkGray = "1;30";
        const Blue = "0;34";
        const LightBlue = "1;34";
        const Green = "0;32";
        const LightGreen = "1;32";
        const Cyan = "0;36";
        const LightCyan = "1;36";
        const Red = "0;31";
        const LightRed = "1;31";
        const Purple = "0;35";
        const LightPurple = "1;35";
        const Brown = "0;33";
        const Yellow = "1;33";
        const LightGray = "0;37";
        const White = "1;37";
        const Reset = "0";

        const All = [
            self::Black,
            self::DarkGray,
            self::Blue,
            self::LightBlue,
            self::Green,
            self::LightGreen,
            self::Cyan,
            self::LightCyan,
            self::Red,
            self::LightRed,
            self::Purple,
            self::LightPurple,
            self::Brown,
            self::Yellow,
            self::LightGray,
            self::White
        ];

        /**
         * A list of random usable bright colors
         */
        const BrightColors = [
            self::LightBlue,
            self::LightGreen,
            self::LightCyan,
            self::LightRed,
            self::LightPurple,
        ];
    }