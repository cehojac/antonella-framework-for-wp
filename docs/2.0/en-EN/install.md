# Requerimientos

PHP 7 or higher
Composer
PHPZip for Linux

To work in local test environment
MySQL or MariaDB
node
NPM

In the case of Windows: PHP node and (MySQL/MariaDB) must be able to be run from console from any location.

# Installation

The first step will be to clone our repository in our local hard disk, to do this run the following command.

```bash
composer create-project --prefer-dist cehojac/antonella-framework-for-wp:dev-master my-awesome-plugin
```

Where `my-awesome-plugin` is the name of our plugin

The next step is to access the directory of our newly created project.

```bash
cd my-awesome-plugin
```

If our plugin is going to make use of Gutenberg it is necessary to install the dependencies using node.
To make sure you have node and NPM installed.

```bash
npm install
```

This command, among other dependencies, will install @wordpress/scripts to work with modern javascript (jsx).

## How to create a local WordPress environment from [Antonella Framework](https://antonellaframework.com/documentacion/) easily.

[![Create a Local Server](http://i3.ytimg.com/vi/An4t8LKX2-I/maxresdefault.jpg)](https://www.youtube.com/watch?v=An4t8LKX2-I)

Remember that in order to create a local server from antonella you must fill in the .env file with your credentials,
to do this run the following commands.

```bash
cp -r .env-example .env
```

Edit the .env file and fill in with your credentials

```text
DBUSER=root
DBNAME=
DBPASS=

TEST_DIR=wp-test
PORT=8010
LOCALE=en_US
```

It is now possible to change both the port(8010) and the locale(en_US) to any other

```test
DBUSER=root
DBNAME=
DBPASS=

TEST_DIR=wp-test
PORT=8080
LOCALE=es_ES
```

Then run

```bash
php antonella serve
php antonella --port 8081 --force
```

This command will create a local server inside the wp-test folder accessible from port 8010 (default), the one you have configured in your .env file (if any) or given as a parameter.
You have configured in your .env file (if any) or the one given as a parameter.

```bash
php antonella refresh
```

Unlike the previous command, this one applies the new changes (packaging and reinstalling the plugin).

[http://localhost:8010](http://localhost:8010)

To access the administration panel you can log in using the following URL

[http://localhost:8010/wp-login.php](http://localhost:8010/wp-login.php)

> **Username:** `test`  
> **Password:** `test`

To avoid conflicts with other plugins make sure to change the Antonella namespace Antonella\CH (default) to different one.

```bash
php antonella namespace FOO
```

Where Antonella\FOO is the new namespace name or

```bash
php antonella namespace
```

Where Antonella will automatically generate and assign a new namespace

```bash
php antonella namespace --namesapce
```

To get the actual namespace name

[Go Back](https://github.com/cehojac/antonella-framework-for-wp/tree/2.0/docs/2.0/en-EN/readme.md)
