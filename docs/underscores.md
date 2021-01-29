# Creación de Theme

Ahora es posible crear un theme basado en _s ([undercores](https://developer.wordpress.org/cli/commands/scaffold/_s/))

Para más información visite el [repositorio oficial en git](https://github.com/automattic/_s)

## Ejemplo de uso

```bash
php antonella theme <slug> [--activate] [--enable-network] [--theme_name=<title>] [--author=<full-name>] [--author_uri=<uri>] [--sassify] [--woocommerce] [--force]
```

## Argumentos

```bash
1 <slug> : El slug para el nuevo tema, y usado para prefijar funciones ( para evitar conflictos ).
2 [--activate] : Activar el tema recién descargado.
3 [--theme_name=<title>] : Establece el Nombre del Theme dentro del style.css
4 [--author=<full-name>] : Estable el Author del Theme dentro del style.css
5 [--author_uri=<uri>] : Estable el Author Uri dentro del style.css
6 [--sassify] : Incluye los style como sass
7 [--woocommerce] : Si queremos que nuestro theme sea compatible con woocommerce
8 [--force] : Sobreescribe los ficheros existentes.
```

### Ejemplo

```bash
php antonella theme sample-theme --theme_name=\"Sample Theme\" --author=\"Carlos Herrera\" 
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
 
[Volver al índice](https://github.com/d3turnes/antonella-framework-for-wp/tree/1.8/docs/readme.md)