![Antonella Framework](https://antonellaframework.com/wp-content/uploads/2018/06/anonella-repositorio.png)

[![Total Downloads](https://poser.pugx.org/cehojac/antonella-framework-for-wp/downloads)](https://packagist.org/packages/cehojac/antonella-framework-for-wp)
[![Latest Unstable Version](https://poser.pugx.org/cehojac/antonella-framework-for-wp/v/unstable)](https://packagist.org/packages/cehojac/antonella-framework-for-wp)
[![License](https://poser.pugx.org/cehojac/antonella-framework-for-wp/license)](https://packagist.org/packages/cehojac/antonella-framework-for-wp)
[![Gitter](https://badges.gitter.im/Antonella-Framework/community.svg)](https://gitter.im/Antonella-Framework/community?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

Antonella Framework for WordPress
================================

Framework for develop WordPress plugins based on Model View Controller
You can read the full documentation in https://antonellaframework.com/documentacion

Versión 1.8 es un Fork de Antonella Framework

## Requeriments
* php (minimun 5.6) 
* composer
* git
* node & npm for gutenberg block with jsx

## Documentation
[See documentation](https://github.com/d3turnes/antonella-framework-for-wp/tree/1.8/docs)

## Install
create a folder for yours antonella framework's projects and execute

~~`composer create-project --prefer-dist cehojac/antonella-framework-for-wp:dev-master my-awesome-plugin`~~

`git clone --branch 1.8 https://github.com/d3turnes/antonella-framework-for-wp my-awesome-plugin`

my-awesome-plugin is your project's plugin

`cd my-awesome-plugin`

Rename .env-example to .env ( only testing )

`cp -r .env-example .env`

`composer update`

Instala dependencias de php necesarias para antonella

`npm install`

Instala dependencias para poder trabajar con gutenberg

this is all!!- start your marvelous plugin in wordpress

[See more](https://github.com/d3turnes/antonella-framework-for-wp/blob/1.8/docs/install.md)

## Basics

Antonella Framework have console functions:

`php antonella namespace FOO`

rename the namespace in all files

`php antonella make MyClassController`

create MyClassController.php file in src\Controllers folder with pre-data

[See more](https://github.com/d3turnes/antonella-framework-for-wp/blob/1.8/docs/controllers.md)

`php antonella widget MyWidget [--enque]`

create a Class for Widget Function in src\Widgets, si --enque es proporcionado éste será añadido al array $widgets[] de fichero config.php

[See more](https://github.com/d3turnes/antonella-framework-for-wp/blob/1.8/docs/widgets.md)

`php antonella shortcode name:Controller@method [--enque]`

Crea un controlador para un shortcode junto a su method, si el controlador no existe éste es creado junto a su method, si --enque es proporcionado será añadido a su array
correspondiente.

[See more](https://github.com/d3turnes/antonella-framework-for-wp/blob/1.8/docs/shortcode.md)

`php antonella helper miclass`

Crea un fichero auxiliar para albergar funciones auxiliares.

[See more](https://github.com/d3turnes/antonella-framework-for-wp/blob/1.8/docs/helper.md)

`php antonella cpt car`

Crea el custom post type car

[See more](https://github.com/d3turnes/antonella-framework-for-wp/blob/1.8/docs/cpt.md)

`php antonella add blade`

Añade un package para trabajar con vistas blade

`php antonella add action tag:Controller@method [--enque]`

Crea un hook de action y lo enloca en el array $add_action[] del fichero config.php, si el controlador no existe éste es creado junto 
al method proporcionado.

`php antonella add filter tag:Controller@method [--enque]`

Crea un hook de tipo filter y lo encola en el array $add_filter[] del fichero config.php, si el controlador no existe éste es creado junto
al method proporcionado.

[See more](https://github.com/d3turnes/antonella-framework-for-wp/blob/1.8/docs/add.md)

`php antonella block namespace/your-block [--callable] [--enque]`

Crea un block de gutenberg compatible con js moderno, haciendo uso de @wordpress/script. El argumento optional --callable indica que haremos uso de un block dinamico que será renderizado desde php. Con el argumento --enque tambien optional nos permite encolarlo al array correspondiente dentro del fichero config.php

[See more](https://github.com/d3turnes/antonella-framework-for-wp/blob/1.8/docs/gutenberg.md)

### Example

`php antonella block antonella/prueba --enque`

Creará los ficheros index.js, editor.css y style.css dentro de la carpeta prueba en el directorio components, el fichero index.js representará nuestro block, mientras que los otros dos ficheros seran los estilos para el backend(editor) y el frontend.

Para obtener un único fichero con todos los blocks tan solo debemos ejecutar el comando `npm run build` éste creará  en assets/blocks un index.js y en blocks/css sus respectivos ficheros de estilos para el backend (editor.css) y frontend (style.css).

## Export you zip plugin

`php antonella makeup`
Compress your project in a .zip file

### Deploy modo testing

## Servidor local

Montar un WP localmente con [Antonella Framework](https://antonellaframework.com/documentacion/)

[Dale al play bro](https://www.youtube.com/watch?v=An4t8LKX2-I)

Recuerda rellenar tu fichero .env con los datos correspondientes

`cp -r .env-example .env`

Depués ejecute el siguiente comando

`php antonella serve`

## Theme

```bash
php antonella theme sample-theme --theme_name=\"Sample Theme\" --author=\"Carlos Herrera\"
```
Generates starter code for a theme based on _s.

[See more](https://github.com/d3turnes/antonella-framework-for-wp/blob/1.8/docs/underscores.md)
