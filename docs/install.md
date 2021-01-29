# Instalación

El primer paso será clonar nuestro repositorio en nuestro disco duro local, para ello ejecute el siguiente comando.

```bash
git clone --branch 1.8 https://github.com/d3turnes/antonella-framework-for-wp my-awesome-plugin
```
Donde `my-awesome-plugin` es el nombre de nuestro plugin

El siguiente paso será acceder al directorio de nuestro proyecto recien creado.

```bash
cd my-awesome-plugin
```

Una vez dentro del directorio, instalaremos las dependencias de php y posteriormente las dependencias para
trabajar desde el front-end para js (en nuestro caso para gutenberg).

```bash
composer update
```

Éste comando instala y actualiza todas las dependencias php

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

Por último ejecute

```bash
php antonella serve
```

Éste comando creará un servidor local dentro de la carpeta wp-test accesible desde el puerto 8010 (por default)

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

[Volver al índice](https://github.com/d3turnes/antonella-framework-for-wp/tree/1.8/docs/readme.md)