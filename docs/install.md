# Requerimientos

PHP 7 o superior
Composer
PHPZip en el caso de Linux

Para trabajar en entorno de test local
MySQL o MariaDB
node
npm

En el caso de Windows php node y (MySQL/MariaDB) deben poder ejecutarse desde consola desde cualquier ubicación. 

# Instalación

El primer paso será clonar nuestro repositorio en nuestro disco duro local, para ello ejecute el siguiente comando.

```bash
composer create-project --prefer-dist cehojac/antonella-framework-for-wp:dev-master my-awesome-plugin
```
Donde `my-awesome-plugin` es el nombre de nuestro plugin

El siguiente paso será acceder al directorio de nuestro proyecto recien creado.

```bash
cd my-awesome-plugin
```

Si nuestro plugin va hacer uso de gutenberg es necesario instalar las dependencias mediante node, para ello asegurate
de tener instalado node y npm.

```bash
npm install
```

Éste comando entre otras dependencias instalará @wordpress/scripts para trabajar con javascript moderno (jsx)

## Como crear un WordPress local desde [Antonella Framework](https://antonellaframework.com/documentacion/) fácilmente.

[![Create a Local Server](http://i3.ytimg.com/vi/An4t8LKX2-I/maxresdefault.jpg)](https://www.youtube.com/watch?v=An4t8LKX2-I)

Recuerde que para poder crear un servidor local desde antonella debes rellenar el fichero .env con tus credenciales,
para ello ejecute los siguientes comandos.

```bash
cp -r .env-example .env
```

Edite el fichero .env y rellene con sus credenciales

```text
DBUSER=root
DBNAME=
DBPASS=
```

El port por default es el 8010 si deas cambiarlo edite el fichero `.env` y agregre al final del fichero PORT=xxxx

```test
DBUSER=root
DBNAME=
DBPASS=
PORT=8080
```

Luego ejecute

```bash
php antonella serve
```

Éste comando creará un servidor local dentro de la carpeta wp-test accesible desde el puerto 8010 (por default) o el 
que haya configurado en su fichero .env (si es el caso).

```bash
php antonella test refresh
```

A diferencia del comando anterior, éste aplica los nuevos cambios (empaquetando e instalado 
nuevamente el plugin).

[http://localhost:8010](http://localhost:8010)

Para acceder al panel de administración puede ingresar mediante la url

[http://localhost:8010/wp-login.php](http://localhost:8010/wp-login.php)

> **Username:** `test`  
> **Password:** `test`

Para evitar conflictos con otros plugin asegurese de cambiar el namespace CH (por default) por otro

```bash
php antonella namespace FOO
```
Donde FOO es el nuevo nombre del namespace ó
```bash
php antonella namespace
```
Donde antonella seignará automáticamente un namespace

[Volver al índice](https://github.com/d3turnes/antonella-framework-for-wp/tree/1.8/docs/readme.md)
