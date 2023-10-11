# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - Unreleased

Updated loglib to work with ncc 2.+.

### Changed

 - Various code improvements and optimizations
 - Removed unused code


## [1.0.2] - 2023-07-06

### Changed 
 * Changed the Timestamp format to display micro time instead of a date format
 * Timestamp Formats can now display in red or yellow to indicate performance impacts between log entries

### Fixed
 * Fixed mistake in `\LogLib\Classes > Console > outException()` where the function attempts to print out a previous
   exception by calling `getPrevious()` as an array instead of a function call.


## [1.0.1] - 2023-02-10

### Added
 * Added PSR compatible LoggerInterface implementation (\LogLib\Psr)
 * Added new option `--log-level` to set the log level (Can also be applied via the environment variable `LOG_LEVEL`)


## [1.0.0] - 2023-01-29

### Added
 * First Release