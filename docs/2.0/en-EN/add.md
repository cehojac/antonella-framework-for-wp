# Add

This command allow us to add packages like blade or dd, action and filter

## Add packages

How to use it

```bash
php antonella add <module>
```

Examples

```bash
php antonella add blade
```

This command is going to install blade, so it can be use in templates

```bash
php antonella add dd
```

This command installs d() and () for managing debugging functions

## Add Action

Allow to add an action type hook and register in its corresponding array

Usage

```bash
php antonella add:action tag:MyController@method:priority:num_args [--enque | -e]
```

### Arguments

```text
tag: Required. Hook
MyController: Required. the name of the controller, it gets created if it doesn't exist
method: Required. Controller static method(it gets created if it doesn't exist)
priority: Optional. default(10) Priority
num_args: Optional. default(1) Number of arguments that the funtion accepts
[--enque | -e]: Optional.Register an action hook in the section $add_action[] in the file Config.php
```

### Examples

```bash
php antonella add:action init:MyController@action --enque
```

Out: scr/Controllers/MyController.php

```php
<?php

namespace Antonella\CH\Controllers;

class MyController
{

	public function __construct()
	{

	}

	public static function action() {
		// TODO
	}

} /* generated with antollena framework */
```

Config.php

```php
...
/**
 * add_action data functions
 * @input array
 * @example ['body_class','CH::function',10,1]
 * @example ['body_class',['CH','function'],10,1]
*/
public $add_action = [
	['init', [__NAMESPACE__ . '\Controllers\MyController','action'], 10, 1]
];
...
```

## Add Filter

Add and register a filter type hook

```bash
php antonella add:filter tag:MyController@method:priority:num_args [--enque | -e]
```

### Arguments

```text
tag: Required. Hook
MyController: Required. Name of the controller and it gets created if it doesn't exist
method: Required. Controller static method(it gets created if it doesn't exist)
priority: Optional. default(10) Priority
num_args: Optional. default(1) Number of arguments that the funtion accepts
[--enque | -e]: Optional. Register an action hook in the section $add_action[] in the file Config.php
```

### Examples

```bash
php antonella add:filter the_content:MyController@add_text_to_content:10:1 --enque
```

Out: scr/Controllers/MyController.php

```php
<?php

namespace Antonella\CH\Controllers;

class MyController
{

	public function __construct()
	{
  	}

	public static function action() {
		// TODO
	}

	public static function add_text_to_content(){
		//TODO
	}

} /* generated with antollena framework */
```

Config.php

```php
/**
 * add_filter data functions
 * @input array
 * @example ['body_class','CH::function',10,1]
 * @example ['body_class',['CH','function'],10,1]
 */
public $add_filter = [
	['the_content', [__NAMESPACE__ . '\Controllers\MyController','add_text_to_content'], 10, 1]
];
```

[Go Back](https://github.com/cehojac/antonella-framework-for-wp/tree/2.0/docs/2.0/en-EN/readme.md)
