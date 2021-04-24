# Creación de Theme

Ahora es posible crear un theme basado en _s ([underscores](https://developer.wordpress.org/cli/commands/scaffold/_s/))

Para más información visite el [repositorio oficial en git](https://github.com/automattic/_s)

## Ejemplo de uso

```bash
php antonella make:theme <slug> [--activate] [--enable-network] [--theme_name=<title>] [--author=<full-name>] [--author_uri=<uri>] [--sassify] [--woocommerce] [--force]
```

Este comando no es más que un wrapper de `wp scaffold <comand>`, eres libre de usar uno u otro.

Nota. Si optas por esta segunda manera asegurate de pasar el argumento --path=wp-test para indicar donde se encuentra la instalación 
de wordpress

```bash
php wp-cli.phar scaffold _s sample --theme_name=\"Sample Theme\" --path=wp-test --force` 
```

## Opciones

```bash
<slug> 
	El slug para el nuevo tema, y usado para prefijar funciones ( para evitar conflictos ) y como clave del textdomain (traducciones).
[--activate] 
	Activar el tema recién descargado.
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
[--force]
	Sobreescribe los ficheros existentes. En éste caso no es necesario ya que está aplicado por default.
```

### Ejemplo

```bash
php antonella make:theme sample-theme --theme_name=\"Sample Theme\" --author=\"Carlos Herrera\" 
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
 
[Volver al índice](https://github.com/cehojac/antonella-framework-for-wp/tree/2.0/docs/2.0/readme.md)