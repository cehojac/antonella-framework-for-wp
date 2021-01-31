# Add

El comando add nos permite añadir pakages como blade ó dd, action y filter.

## Añadir packages

```bash
php antonella add blade
```

Éste comando instalará el package blade para trabajar con vistas

```bash
php antonella add dd
```

Éste comando instala d() y dd() para el manejo de funciones de depurado.

## Add Action

Permite añadir un hook de tipo action y lo registra.

```bash
php antonella add action tag:MyController@method:prioridad:num_args [--enque]
```

### Argumentos

```text
tag: Hook
MyController: Nombre del Controlador si no existe, éste es creado
method: Method static del Controlador (sino existe será creado)
prioridad: default(10) Prioridad
num_args: default(1) Numero de argumentos que acepta la function	 	
--enque: Registra el hook action en la sección $add_action[] del fichero Config.php
```

### Ejemplo de uso

```bash
php antonella add action init:MyController@action --enque
```

Out: scr/Controllers/MyController.php

```php
<?php
    
namespace CH\Controllers;
          
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

Permite añadir un hook de tipo filter y lo registra.

```bash
php antonella add filter tag:MyController@method:prioridad:num_args [--enque]
```

### Argumentos

```text
tag: Hook
MyController: Nombre del Controlador si no existe, éste es creado
method: Method static del Controlador (sino existe será creado)
prioridad: default(10) Prioridad
num_args: default(1) Numero de argumentos que acepta la function	 	
--enque: Registra el hook filter en la sección $add_filter[] del fichero Config.php
```

### Ejemplo de uso

```bash
php antonella add filter the_content:MyController@add_text_to_content:10:1 --enque
```

Out: scr/Controllers/MyController.php

```php
<?php
    
namespace CH\Controllers;
          
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

[Volver al índice](https://github.com/cehojac/antonella-framework-for-wp/tree/1.8/docs/readme.md)