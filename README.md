# Departure Monitor

A simple webpage written in PHP for displying the departure times
and wether in the flat share. The PHP script runs on a
Raspberry Pi

## Development installation

This section describes how you get a running development platform for this
project. For the deplyment look the section below. 


### Requirements

 * git - amazing version control system
 * node.js - JavaScript backend
 * Bower - Web assets package manager
 * Grunt - JavaScript Task runner
 * Composer - PHP package manager


First of all, you need git and PHP for the command line. On Debian-based
distribution, just install the follwing package:

```bash
$ sudo apt-get install git-core php5-cli
```

We use [Bower](http://bower.io/) - another package manager for our web assets like
Bootstrap and jQuery - and [Grunt](http://gruntjs.com/) - a JavaScript task runner
for build processes. Both programs are written in JavaScript and require
[node.js](http://nodejs.org/).

```
# Add PPA for node.js
$ curl -sL https://deb.nodesource.com/setup | sudo bash -
# Install node.js from apt
$ sudo apt-get install nodejs

# Now you can install Bower and Grunt globally
$ sudo npm install -g bower
$ sudo npm install -g grunt-cli
```

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


### Get the source code

Now it is time to get the source code!

```bash
# Get the source code
$ git clone git@github.com:f3anaro/departure-monitor.git

$ cd departure-monitor/
```

### Install PHP packages and web assets

Now you can use composer to install additional PHP packages - why do you want
to program something that was already written? ;)

All required packages are listed in the [composer.json](composer.json) file of
the project. See the [offical docs](https://getcomposer.org/doc/) for further
information.

```bash
# install all required PHP packages
$ composer install
```

After the PHP packages, we use `npm` to install the required JavaScript packages
and Bower to install the web assets

```bash
# JavaScript packages
$ npm install

# Web assets
$ bower install
```


## Development server

For development, you can use PHP's [builtin webserver](http://php.net/manual/en/features.commandline.webserver.php)

```bash
# Now you can call all scripts relativly to the working directory of the server,
# for example:
# 
#     http://localhost:8000/index.php
# 
$ php -S localhost:8000
```


## Deployment

The deployment can be done very easily. You just need to get all the required files - like
the web assets, PHP and CSS files on the Server. You can achive this with `rsync` for example.

That means you can use Bower and Composer locally on your development machine and just copy all
the required stuff to the server via SSH. Of course you can also install Composer and Bower 
on your remote server and install the packages this way.

```bash
$ rsync --delete --recursive . user@server:/var/www/departure-monitor
```
