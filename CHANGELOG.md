# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.7] - 2025-01-13

This update introduces a minor fix

### Fixed
 - Fixed FileLogging issue by setting the write permission to 0666 for the log file if it doesn't exist.



## [2.0.6] - 2025-01-10

This update introduces a minor change

### Changed
 - File logging is disabled for web environments due to instability in file locking, until a better solution is found.


## [2.0.5] - 2025-01-09

This update introduces a minor bug fix

### Fixed
 - Refactor file locking to return status and handle failure.


## [2.0.4] - 2024-12-04

This update introduces a minor bug fix


## [2.0.3] - 2024-11-05

This update introduces a minor bug fix


## [2.0.2] - 2024-10-30

This update introduces minor improvements

### Changed
 - Refactored exception handling in FileLogging where it will always attempt to print the exception no matter
   the log level for as long as the log level isn't silent
 - Implement enhanced error and exception handling


## [2.0.1] - 2024-10-29

This update introduces a critical bug fix where Console logging was enabled in web environments


## [2.0.0] - 2024-10-29

This update introduces some additional features & bug fixes

### Added
 - Added File Logging support
 - Added LogHandlerInterface & refactored Application settings to allow for use of custom logging handlers
 - Added Logging type for logging type handling
 - Added a Logger object for creating a logging instance for Applications

### Changed
 - All abstract classes are now enum classes
 - Refactored Console Logging to use the new LogHandlerInterface

### Fixed
 - Set default log level to 'info' in Utilities. instead of using `null` due to deprecation error

### Removed
- Removed Unused BRIGHT_COLORS constant from ConsoleColors
- Removed Unused Options object
- Removed Unused RuntimeOptions object
- Removed unused Console class


## [1.1.1] - 2024-10-13

Update build system



## [1.1.0] - 2023-10-12

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