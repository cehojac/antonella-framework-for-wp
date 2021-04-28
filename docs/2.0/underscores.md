# Creación de Theme

Ahora es posible crear un theme basado en _s ([underscores](https://developer.wordpress.org/cli/commands/scaffold/_s/))

Para más información visite el [repositorio oficial en git](https://github.com/automattic/_s)

## Ejemplo de uso

```bash
php antonella make:theme <slug> [--activate|-a] [--enable-network|-n] [--theme_name=<title>] [--author=<full-name>] [--author_uri=<uri>] [--sassify] [--woocommerce] [--force]
```

Este comando no es más que un wrapper de `wp scaffold <comand>`, eres libre de usar uno u otro.

Nota. No olvides pasar el argumento --path=wp-test para indicar donde se encuentra la instalación de tu WP

```bash
php wp-cli.phar scaffold _s sample-theme --theme_name="Sample Theme" --path=wp-test` 
```

## Opciones

```bash
<slug> 
	El slug para el nuevo tema, y usado para prefijar funciones ( para evitar conflictos ) y como clave del textdomain (traducciones).
[--activate] 
	Activar el tema recién descargado.
[--enable-network]	
	Habilita el tema recién descargado para toda la red
[--theme_name=<title>]
	Establece el Nombre del Theme dentro del style.css
[--author=<full-name>]
	Estable el Author del Theme dentro del style.css
[--author_uri=<uri>]
	Estable el Author Uri dentro del style.css
[--sassify]
	Incluye los style como sass
[--woocommerce]
	Si queremos que nuestro theme sea compatible con woocommerce
[--debug]
	Muestra el comando a ser ejecutado sin ejecutarse
```

### Ejemplos

```bash
php antonella make:theme sample-theme --theme_name="Sample Theme" --author="Carlos Herrera" 
```

Genera el theme de nombre "Sample Theme" y author "Carlos Herrera"

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

# Operar con los Themes

Ahora es posible listar y eliminar theme

## Listar themes

```bash
php antonella theme:list
```

### Opciones

```bash
[--filter=key1,value1:key2,value2]
	Permite filtrar por distintas key,value. --filter=status,inactive:update,none
	Este comando genera, la siguinete salida. --status=inactive --update=none
[--field=<field>]
	Imprime el valor de un solo campo para cada tema
[--fields=<fields>]
	Imprime sólo los campos específicos. Ejemplo --fields=name,status,version
[--format=table|csv|json|count|yaml]
	Renderiza la salida a uno de los siguientes formatos. Por defecto es table
```

Muestra todos los themes instalados (activos o no)

### Ejemplos

```bash
php antonella theme:list --filter=status,inactive:update,none
php antonella theme:list --filter=status,inactive --field=name
php antonella theme:list --filter=status,inactive --fields=name,status --format=json
```

## Eliminar theme

```bash
php antonella theme:delete <theme>
```

### Opciones

```bash
[<theme>[,<theme>]]
	Uno o más themes para eliminar. Lista de theme separados por coma
[-all]
	Si `--all` está presente, se eliminarán todos los temas excepto el tema activo
	Es excluyente de [<theme>[,<theme>]]
[--force]
	Si `--force` está presente, el theme active será también eliminado
	Es excluyente de [<theme>[,<theme>]]
	Sólo es válido si está presente la opción --all
```

### Ejemplos

```bash
php antonella theme:delete twentytwenty,twentytwentyone
```

Eliminará los themes, twentytwenty y twentytwentyone

```bash
php antonella theme:delete --all
```

Elimina todos los themes excepto el theme activo

```bash
php antonella theme:delete --all --force
```

Elimina todos los themes y el theme activo
```


[Volver al índice](https://github.com/cehojac/antonella-framework-for-wp/tree/2.0/docs/2.0/readme.md)