# Creating a ShortCode

To create a Shortcode run the following command

```bash
php antonella make:shortcode tag:MyController@method [--enque | -e]
```

## Arguments

```text
tag: Required. Name of the SHortcode
MyController: Required. Name of the Controller if it does not exist, it is generated.
method: Required. Static Method of the Controller (if it does not exist, it will be generated)
--enque: Required.  Register the shortcode in the $shortcodes[] section of the Config.php file.
```

If the method is omitted, the static short_code() method will be created inside the MyController.php class.

### Example

```bash
php antonella make:shortcode saluda:MyController@short_code --enque
```

Out: src/Controllers/MyController.php

```php
<?php

namespace Antonella\CH\Controllers;

class MyController
{

	public function __construct()
	{
	}

	public static function short_code() {
		// TODO
	}

} /* generated with antollena framework */
```

Out: src/Config.php

```php
<?php

...

/**
 * add custom shortcodes
 * @input array
 * @example [['example','Antonella\CH\ExampleController::example_shortcode']]
 */
public $shortcodes = [
	['saluda', __NAMESPACE__ . '\Controllers\MyController::short_code'],
	['example','Antonella\CH\ExampleController::example_shortcode']
];

...
```

[Go Back](https://github.com/cehojac/antonella-framework-for-wp/tree/2.0/docs/2.0/en-EN/readme.md)
