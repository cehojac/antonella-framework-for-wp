---
sidebar_position: 2
---

# Configuración

Para configurar tu plugin con Antonella Framework, necesitas modificar algunos archivos clave. La configuración se realiza principalmente a través del archivo `config.php` y el archivo principal del plugin.

## Configuración básica del plugin

### Nombre del plugin

Para asignar el nombre del plugin, modifica el archivo `antonella-framework.php` en la sección Plugin Name:

```php
<?php
/**
 * Plugin Name: Mi Awesome Plugin
 * Description: Descripción de mi plugin
 * Version: 1.0.0
 * Author: Tu Nombre
 */
```

## Archivo config.php

El archivo `config.php` contiene un sistema de arrays para configurar tu plugin de manera fácil y organizada.

### Variable Options

Esta variable permite introducir las opciones que deseas almacenar en WordPress:

```php
/**
 * Plugins option
 * storage in database the option value
 * Array ('option_name'=>'default value')
 * @example ["example_data" => 'foo',]
 * @return void
 */
public $plugin_options = [
    "version" => "1.0",
    "valor1" => "algun valor",
    "mi_opcion" => "valor por defecto",
];
```

### Variable language_name

Puedes indicar una única palabra para la traducción. Por defecto es "antonella" pero puedes cambiarla:

```php
/**
 * Language Option
 * define a unique word for translate call
 */
public $language_name = 'mi_plugin';
```

### Variable plugin_prefix

Indica una única palabra para identificar el plugin. Por defecto es "ch_nella" pero puedes cambiarla:

```php
/**
 * Plugin text prefix
 * define a unique word for this plugin
 */
public $plugin_prefix = 'mi_plugin';
```

## Manejo de datos POST y GET

### Variable data_post

Controla la llegada de datos POST. Puedes indicar que si dentro de un POST está una variable que te interesa, se invoque una función:

```php
/**
 * POST data process
 * get the post data and execute the function
 * @example ['post_data'=>'MiClase::miFuncion']
 */
public $post = [
    "admin" => "MiClase::miFuncion",
    "guardar_datos" => "MiClase::guardarDatos"
];
```

### Variable data_get

Similar al POST, maneja variables GET:

```php
/**
 * GET data process
 * get the get data and execute the function
 * @example ['get_data'=>'MiClase::miFuncion']
 */
public $get = [
    "mi_variable_get" => "MiClase::miFuncion"
];
```

## Hooks y Filtros de WordPress

### Variable add_filter

Aquí puedes agregar [filtros de WordPress](https://developer.wordpress.org/reference/functions/add_filter/) con prioridad y parámetros:

```php
/**
 * add_filter data functions
 * @input array
 * @example ['body_class','MiClase::miFuncion',10,2]
 * @example ['body_class',['MiClase','miFuncion'],10,2]
 */
public $add_filter = [
    ["init", "MiClase::miFuncion", 10, 1],
    ["body_class", ["MiClase", "miFuncion"], 10, 1],
];
```

### Variable add_action

Similar a los filtros, para [acciones de WordPress](https://developer.wordpress.org/reference/functions/add_action/):

```php
/**
 * add_action data functions
 * @input array
 * @example ['save_post','MiClase::miFuncion',10,2]
 * @example ['save_post',['MiClase','miFuncion'],10,2]
 */
public $add_action = [
    ["save_post", "MiClase::miFuncion", 10, 1],
    ["wp_enqueue_scripts", ["MiClase", "miFuncion"], 10, 1],
];
```

## Configuración del Dashboard

### Variable dashboard

Puedes agregar apartados en el Dashboard de WordPress:

```php
/**
 * Dashboard configuration
 * Add sections to WordPress admin dashboard
 */
public $dashboard = [
    [
        "title" => "Mi Plugin",
        "function" => "MiClase::dashboardWidget",
        "control" => "MiClase::dashboardControl"
    ]
];
```

## Siguiente paso

Una vez configurado el archivo `config.php`, tu plugin estará listo para comenzar a desarrollar la funcionalidad. En la siguiente sección aprenderemos los conceptos básicos para crear controladores y modelos.
