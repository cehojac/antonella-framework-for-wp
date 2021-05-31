# Custom Post Type (CPT)

Custom posts behave just like a page or post and allow to be extended easily using
custom fields

## How to create a CPT

```bash
php antonella make:cpt car
```

This command creates a car custom post type and register in Config.php

```php
/**
 * Custom Post Type
 * for make simple Custom PostType
 * for simple add fill the 7 frist elements
 * for avanced fill
 * https://codex.wordpress.org/Function_Reference/register_post_type
 */

public $post_types =[
	[
		"singular"      => "car",
		"plural"        => "cars",
		"slug"          => "car",
		"position"      => 99,
		"taxonomy"      => [],
		"image"         => "antonella-icon.png",
		"gutemberg"     => true
	],
	[
		"singular"      => "",
		"plural"        => "",
		"slug"          => "",
		"position"      => 12,
		"taxonomy"      => [], //['category','category2','category3'],
		"image"         => "antonella-icon.png",
		"gutemberg"     => true,
		//advanced
		/*
		'labels'        => [],
		'args'          => [],
		'rewrite'       => []
		*/
	],
];
```

[Go Back](https://github.com/cehojac/antonella-framework-for-wp/tree/2.0/docs/2.0/en-EN/readme.md)
