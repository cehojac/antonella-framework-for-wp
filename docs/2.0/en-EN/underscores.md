# Creating a Theme

It is now possible to create a theme based on \_s ([underscores](https://developer.wordpress.org/cli/commands/scaffold/_s/))

For more information visit the [official git repository](https://github.com/automattic/_s)

## Examples

```bash
php antonella make:theme <slug> [--activate|-a] [--enable-network|-n] [--theme_name=<title>] [--author=<full-name>] [--author_uri=<uri>] [--sassify] [--woocommerce] [--force]
```

This command is just a wrapper for `wp scaffold <command>`, you are free to use one or another.

Note: Don't forget to pass the --path=wp-test argument to indicate where your WP installation is located.

```bash
php wp-cli.phar scaffold _s sample-theme --theme_name="Sample Theme" --path=wp-test`
```

## Options

```bash
<slug>
	The slug for the new theme, and used to prefix functions (to avoid conflicts) and as a key for the textdomain (translations).
[--activate]
	Activate the newly downloaded theme.
[--enable-network]
	Enables the newly downloaded theme for the entire network
[--theme_name=<title>]
	Set the Theme Name inside style.css
[--author=<full-name>]
	Set the Theme Author inside style.css
[--author_uri=<uri>]
	Set the Author Uri inside style.css
[--sassify]
	Includes styles as sass
[--woocommerce]
	If we want our theme to be compatible with woocommerce
[--debug]
	Displays the command to be run without executing it
```

### Examples

```bash
php antonella make:theme sample-theme --theme_name="Sample Theme" --author="Carlos Herrera"
```

Generate the theme named "Sample Theme" and author "Carlos Herrera".

style.css

```css
/*!
Theme Name: Sample Theme
Theme URI: http://underscores.me/
Author: Carlos Herrera
Author URI: http://underscores.me/
Description: Custom theme: Sample Theme, developed by Carlos Herrera
Version: 1.0.0
Tested up to: 5.4
Requires PHP: 5.6
License: GNU General Public License v2 or later
License URI: LICENSE
Text Domain: sample-theme
Tags: custom-background, custom-logo, custom-menu, featured-images, threaded-comments, translation-ready

This theme, like WordPress, is licensed under the GPL.
Use it to make something cool, have fun, and share what you've learned.

Sample Theme is based on Underscores https://underscores.me/, (C) 2012-2020 Automattic, Inc.
Underscores is distributed under the terms of the GNU GPL v2 or later.

Normalizing styles have been helped along thanks to the fine work of
Nicolas Gallagher and Jonathan Neal https://necolas.github.io/normalize.css/
*/
```

# Working with the themes

Now is possible to list and delete a theme

## List themes

```bash
php antonella theme:list
```

### Options

```bash
[--filter=key1,value1:key2,value2]
	Allows filtering by different key,value. --filter=status,inactive:update,none
	This command generates the following output. --status=inactive --update=none
[--field=<field>]
	Prints the value of a single field for each theme
[--fields=<fields>]
	Prints only the specific fields. Example --fields=name,status,version
[--format=table|csv|json|count|yaml]
	Renders the output to one of the following formats. Default is table
```

Displays all installed themes (active or not)

### Examples

```bash
php antonella theme:list --filter=status,inactive:update,none
php antonella theme:list --filter=status,inactive --field=name
php antonella theme:list --filter=status,inactive --fields=name,status --format=json
```

## Deleting a theme

```bash
php antonella theme:delete <theme>
```

### Options

```bash
[<theme>[,<theme>]]
	One or more themes to delete. List of themes separated by comma
[-all]
	If `--all` is present, all themes except the active theme will be removed.
	Is exclusive of [<theme>[,<theme>]]]
[--force]
	If `--force` is present, the active theme will also be removed.
	Is exclusive of [<theme>[,<theme>]]]
	Only valid if the --all option is present.
```

### Examples

```bash
php antonella theme:delete twentytwenty,twentytwentyone
```

Deletes the themes twentytwenty y twentytwentyone

```bash
php antonella theme:delete --all
```

Removes all themes except the active theme

```bash
php antonella theme:delete --all --force
```

Deletes all themes including the active theme

[Go Back](https://github.com/cehojac/antonella-framework-for-wp/tree/2.0/docs/2.0/en-EN/readme.md)
