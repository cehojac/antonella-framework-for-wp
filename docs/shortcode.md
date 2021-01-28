# Crear un ShortCode

Para crear un shortcode ejecute el siguiente comando

```bash
php antonella shortcode tag:MyController@method [--enque]
```

## Argumentos

```text
tag: Nombre del ShortCode
MyController: Nombre del Controlador si no existe, éste es creado
method: Method static del Controlador (sino existe será creado) 	 	
--enque: Registra el shortcode en la sección $shortcodes[] del fichero Config.php
```

Si el method es omitido se creará el method static short_code() dentro de la clase MyController.php

### Ejemplo

```bash
php antonella shortcode saluda:MyController@short_code --enque
```

Out: src/Controllers/MyController.php

```php
<?php
    
namespace CH\Controllers;
          
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
 * @example [['example','CH\ExampleController::example_shortcode']]
 */
public $shortcodes = [
	['saluda', __NAMESPACE__ . '\Controllers\MyController::short_code'],
	['example','CH\ExampleController::example_shortcode']
];

...
```

[Volver al índice](https://github.com/d3turnes/antonella-framework-for-wp/tree/1.8/docs/readme.md)