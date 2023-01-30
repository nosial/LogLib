# LogLib

A logging library for PHP/ncc, this was quickly thrown together
to provide a simple logging interface and to test out 
NCC's capabilities for PHP.

## Table of Contents

<!-- TOC -->
* [LogLib](#loglib)
  * [Table of Contents](#table-of-contents)
  * [Installation](#installation)
  * [Compiling from source](#compiling-from-source)
  * [Usage](#usage)
  * [Changelog](#changelog)
  * [License](#license)
<!-- TOC -->


## Installation

The library can be installed using ncc:

```bash
ncc install -p "nosial/libs.log=latest@n64"
```

or by adding the following to your project.json file under
the `build.dependencies` section:

```json
{
  "name": "net.nosial.loglib",
  "version": "latest",
  "source_type": "remote",
  "source": "nosial/libs.log=latest@n64"
}
```

If you don't have the n64 source configured you can add it
by running the following command:

```bash
ncc source add --name n64 --type gitlab --host git.n64.cc
```

## Compiling from source

The library can be compiled from source using ncc:

```bash
ncc build --config release
```

or by running the following command:

```bash
make release
```


## Usage

The usage of this library is very simple, there are
multiple error levels that can be used to log messages

```php
<?php
  
  use Nosial\Libs\Log;
  
  Log::info("This is an info message");
  Log::warning("This is a warning message");
  Log::error("This is an error message");
  Log::debug("This is a debug message");
  Log::critical("This is a critical message");
```

To display the log messages, you can run your program
with the `--log-level` argument, this will display all
messages with a level equal to or higher than the one
specified.

```bash
myprogram --log-level info
```

The log level can be set to one of the following:

* `debug`, `6`, `dbg`
* `verbose`, `5`, `vrb`
* `info`, `4`, `inf`
* `warning`, `3`, `wrn`
* `error`, `2`, `err`
* `fatal`, `1`, `ftl`
* `silent`, `0`, `sil`

The default log level is `info`.

 > Note: Log messages are only displayed if the program
is run from the command line, if you are running the
program from a web server, the log messages will be shown
 

## Changelog

See [CHANGELOG.md](CHANGELOG.md)

## License

LogLib is licensed under the MIT license, see [LICENSE](LICENSE)
