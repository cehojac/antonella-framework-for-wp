# Custom Post Type (CPT)

Los Custom Post Type no son mas que entradas personalizadas que se comportan como un page o un post y que
permiten ser extendidos facilmente mediante el uso custom field's (campos personalizados).

## Como crear un CPT

```bash
php antonella cpt car
```

Éste comando crea la entrada personalizada cars y lo registra en el fichero Config.php

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

[Volver al índice](https://github.com/cehojac/antonella-framework-for-wp/tree/1.8/docs/readme.md)