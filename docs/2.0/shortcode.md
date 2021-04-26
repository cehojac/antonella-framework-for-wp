# Crear un ShortCode

Para crear un shortcode ejecute el siguiente comando

```bash
php antonella make:shortcode tag:MyController@method [--enque | -e]
```

## Argumentos

```text
tag: Requerido. Nombre del ShortCode
MyController: Requerido. Nombre del Controlador si no existe, éste es creado
method: Requerido. Method static del Controlador (sino existe será creado) 	 	
--enque: Requerido. Registra el shortcode en la sección $shortcodes[] del fichero Config.php
```

Si el method es omitido se creará el method static short_code() dentro de la clase MyController.php

### Ejemplo

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

[Volver al índice](https://github.com/cehojac/antonella-framework-for-wp/tree/2.0/docs/2.0/readme.md)