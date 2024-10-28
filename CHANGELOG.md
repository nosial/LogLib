# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - Ongoing

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