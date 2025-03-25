![Antonella Framework](https://antonellaframework.com/wp-content/uploads/2018/06/anonella-repositorio.png)

[![Latest Stable Version](http://poser.pugx.org/cehojac/antonella-framework-for-wp/v)](https://packagist.org/packages/cehojac/antonella-framework-for-wp) [![Total Downloads](http://poser.pugx.org/cehojac/antonella-framework-for-wp/downloads)](https://packagist.org/packages/cehojac/antonella-framework-for-wp) [![Latest Unstable Version](http://poser.pugx.org/cehojac/antonella-framework-for-wp/v/unstable)](https://packagist.org/packages/cehojac/antonella-framework-for-wp) [![License](http://poser.pugx.org/cehojac/antonella-framework-for-wp/license)](https://packagist.org/packages/cehojac/antonella-framework-for-wp) [![PHP Version Require](http://poser.pugx.org/cehojac/antonella-framework-for-wp/require/php)](https://packagist.org/packages/cehojac/antonella-framework-for-wp)
[![Gitter](https://badges.gitter.im/Antonella-Framework/community.svg)](https://gitter.im/Antonella-Framework/community?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

Antonella Framework for WordPress
================================

Framework for develop WordPress plugins based on Model View Controller
You can read the full documentation in https://antonellaframework.com/documentacion

## License
 [MIT License](LICENSE.md)

 **RELATIONSHIP WITH WORDPRESS AND GPL**  
Antonella Framework is licensed under the MIT license. However, any plugin 
created using Antonella Framework and distributed for use within WordPress 
**must comply with the [GPL license](https://wordpress.org/about/license/)**, in accordance with WordPress requirements 
and the philosophy of free software.  

## Requeriments
* php (minimun 5.6) 
* composer
* git

## Instalation
create a folder for yours antonella framework's projects and execute

`composer create-project --prefer-dist cehojac/antonella-framework-for-wp:dev-master my-awesome-plugin`

my-awesome-plugin is your project's plugin

`cd my-awesome-plugin`

this is all!!- start your marvelous plugin in wordpress

## Basics

Antonella Framework have console functions:

`php antonella namespace FOO`

rename the namespace in all files

`php antonella make MyClassController`

create MyClassController.php file in src folder whit pre-data

`php antonella widget MyWidget`

create a Class for Widget Function

## Export you zip plugin

`php antonella makeup`
Compress your project in a .zip file 


