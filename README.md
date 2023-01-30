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
