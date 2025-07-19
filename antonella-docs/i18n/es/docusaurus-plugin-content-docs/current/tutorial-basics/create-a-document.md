---
sidebar_position: 1
---

# Instalación

## Requerimientos Mínimos

Antonella Framework necesita los siguientes elementos:

- [**Git**](https://git-scm.com/)
- [**Composer**](https://getcomposer.org/)
- [**PHP 8.0 o superior**](http://php.net/downloads.php)
- **PHP-ZIP** (en sistemas Linux)
- [**Docker**](https://www.docker.com/products/docker-desktop/) (para Live Testing)

### Verificar instalación

Puedes verificar que tienes los requerimientos instalados:

```bash
# Verificar PHP
php --version

# Verificar Composer
composer --version

# Verificar Git
git --version

# Verificar Docker
docker --version
```

## Instalación de Antonella Framework

Puedes instalar Antonella Framework de dos formas:

### Método 1: Usando Composer (Recomendado)

```bash
composer create-project cehojac/antonella-framework-for-wp tu-nombre-de-plugin
```

### Método 2: Versión en desarrollo

```bash
composer create-project cehojac/antonella-framework-for-wp -s dev tu-nombre-de-plugin
```

### Navegar al directorio del proyecto

```bash
cd tu-nombre-de-plugin
```

## Configuración inicial

Durante la instalación, se te preguntará si deseas instalar el sistema de plantillas Blade:

```bash
You need add blade? (Template system)? Type 'yes' or 'y' to continue:
```

Responde `yes` o `y` si quieres usar el sistema de plantillas Blade en tu proyecto.

## Estructura del proyecto

Una vez instalado, verás la siguiente estructura de carpetas:

```
tu-nombre-de-plugin/
├── src/                    # Archivos del Framework
│   ├── Controllers/        # Controladores
│   ├── Models/            # Modelos
│   └── Helpers/           # Helpers personalizados
├── resources/             # Recursos del proyecto
│   └── views/             # Vistas Blade
│       └── template/      # Plantillas
├── wp-test/               # WordPress de prueba (testing)
├── languages/             # Archivos de idioma
├── composer.json          # Dependencias de Composer
├── antonella-framework.php # Archivo principal del plugin
└── config.php            # Configuración del plugin
```

## Configurar el Namespace

Al instalar, Antonella crea un namespace aleatorio. Puedes cambiarlo:

```bash
# Cambiar a un namespace específico
php antonella namespace TUNAMESPACE

# Generar un namespace aleatorio
php antonella namespace
```

## Verificar la instalación

Puedes verificar que todo funciona correctamente ejecutando:

```bash
php antonella help
```

Esto debería mostrar la ayuda de la consola Antonella.

## ¡Listo para comenzar!

Ahora tienes Antonella Framework instalado y configurado. En la siguiente sección aprenderás cómo configurar tu primer plugin.
