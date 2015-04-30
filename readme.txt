Framework de prueba Antonella Framework
Se ha seguido el sistema de trabajo PRS-4
Más información sobre psr-4 (http://www.php-fig.org/psr/psr-4/)
Realizado por Carlos Herrera.
http://carlos-herrera.com


Pasos para instalación:

-Crear una tabla llamada link en una base de datos.
-Importar el archivo links.sql con pypadmin o algun programa el cual estés familiarizado
-en la carpeta solucion/config.php puede configurar el acceso a la base de datos.

¿Esperabas más pasos? siento decepcionarte ;)



Documentación:

El archivo que debe ser visible en publico debe ser la carpeta public, ahi será la ubicación del dominio el cual solo tendra un archivo index.php,


La solución tiene soporte para múltiples idiomas; aparte de los idiomas latinos soporta idiomas del medio oriente y asiáticos.
Para agregar más o configurarlos a tu gusto puedes verlo en solucion/config.php función charset.

BASE DE DATOS

Para hacer consultas para obtener información de la base de datos solo debes usar la funcion database::get($query,$char) donde:

$query: es la consulta mysql. para insert o delete estan otras funciones
$char: donde puedes elegir el chartset para exportarlo independientemente del juego de caracteres que hayas configurado. mas acerca de los charset permitidos (http://php.net/manual/en/function.html-entity-decode.php)

para insertar estar la funcion database:insert($table,$col,$vars) donde:
$table: es la tabla donde se debe insertar
$col: es las columnas donde se debe insertar se inserta tipo array
$vars: son los valores a insertar, deben estar en el mismo orden de $cols se inserta tipo array


para borrar esta la funcion database::delete($table,$col,$signal,$val) donde
$table: es la tabla donde se debe borrar
$col: es la columna de referencia 
$signal: es un valor de comparación, puede ser = , > ,< ,<=, <>
$val: el valor de referencia

GENERACION DE TOKENS

Los fomrularios llevan un token de seguridad que puedes modificar en solucion/config.php function forms
Para la generación de tokens dependiendo de las necesidades pueden hacerse por sesión o por firma-autogenerada. 
esto se controla con la función

forms::maketoken($form,$type) donde:

$form es la palabra clave configurada previamente en config.php denominada como CH_WORD
$type: es el tipo de generación del token. si es vacío por defecto es por firma-autogenerada, si el valor es 'session' se genera mediante sesión.

más detalles de la funcion en solucion/secure/forms/forms.php


GENERACION DE PLANTILLAS

la aplicación tiene un sistema de plantillas que puedes crear en la carpeta solucion/templates
por defecto hay 3:
header.php  donde está el contenido del header
footer.php donde está el contenido del footer
home.php la pagina inicial donde esta el contenido actual de la solución.

Puedes crear tus propias plantillas y despues invocarlas en el index con la siguiente función:

templates::render($template,array($data)) donde:

$template es el nombre del archivo de la plantilla alojada en solucion/templates sin la extensión .php
ejemplo: si llamo mi plantilla blog.php coloco solo blog, sin el .php 
en las plantillas no será necesario llamar a la función maketoken en el form ya que la solución los inserta de forma automática.

$data: es la información que deseas enviar a la plantilla, debes enviarlo en forma de array



más info templates/templates.php

PLANTILLA HOME (url amigable =>  /)

Aqui se realiza la consulta deseada segun los parametros solicitados

PLANTILLA AGREGAR HOSPEDAJES (url amigable =>  /agregar)

Aqui podrás agregar más hospedajes









