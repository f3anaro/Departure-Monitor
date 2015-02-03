# Departure Monitor

A simple webpage written in PHP for displying the departure times
and wether in the flat share. The PHP script runs on a
Raspberry Pi

# Installation

First of all, you need PHP for the command line. On Debian-based
distribution, just install the follwing package:

```bash
$ sudo apt-get install php5-cli
```

## Getting composer

Each modern web script lanuage uses package managers today - even PHP. The
defacto standard package manager for PHP is [composer](https://getcomposer.org).
To install it, do the following:

```bash
# Download composer executable
$ curl -sS https://getcomposer.org/installer | php

# Make composer globally available on your system. If you do not want that, skip
# this command, but remember: you have to call './composer' in the current
# directory instead of 'composer' in the following commands.
$ mv composer.phar /usr/local/bin/composer
```

Now you can use composer to install additional PHP packages - why do you want
to program something that was already written? ;)

All required packages are listed in the [composer.json](composer.json) file of
the project. See the [offical docs](https://getcomposer.org/doc/) for further
information.

```bash
# install all required PHP packages
$ composer install
```
