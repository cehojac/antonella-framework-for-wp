![Antonella Framework](https://antonellaframework.com/wp-content/uploads/2018/06/anonella-repositorio.png)

[![Total Downloads](https://poser.pugx.org/cehojac/antonella-framework-for-wp/downloads)](https://packagist.org/packages/cehojac/antonella-framework-for-wp)
[![Latest Unstable Version](https://poser.pugx.org/cehojac/antonella-framework-for-wp/v/unstable)](https://packagist.org/packages/cehojac/antonella-framework-for-wp)
[![License](https://poser.pugx.org/cehojac/antonella-framework-for-wp/license)](https://packagist.org/packages/cehojac/antonella-framework-for-wp)
[![Gitter](https://badges.gitter.im/Antonella-Framework/community.svg)](https://gitter.im/Antonella-Framework/community?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

Antonella Framework for WordPress
================================

Framework for develop WordPress plugins based on Model View Controller
You can read the full documentation in https://antonellaframework.com/documentacion

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


