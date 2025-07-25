# Instalación y Configuración

> **📌 Versión actual:** Antonella Framework v1.9

Esta guía te llevará paso a paso a través del proceso de instalación y configuración del Framework Antonella v1.9 en tu entorno de desarrollo.

## 📋 **Requisitos previos**

Antes de comenzar, asegúrate de tener instalado:

- **PHP 8.0+** con extensiones necesarias
- **WordPress 5.0+** funcionando correctamente
- **Composer** para gestión de dependencias
- **Git** para control de versiones
- **Editor de código** (VS Code, PHPStorm, etc.)

## 🚀 **Método 1: Instalador Oficial (Recomendado)**

La forma más rápida y sencilla de crear un nuevo proyecto con Antonella Framework es usando nuestro **instalador oficial**, que funciona igual que el instalador de Laravel.

### Paso 1: Instalar el instalador globalmente

```bash
# Instalar Antonella Installer globalmente
composer require global antonella-framework/antonella-installer
```

### Paso 2: Crear nuevo proyecto

```bash
# Crear nuevo proyecto con Antonella Framework
antonella new mi-plugin

# Entrar al directorio del proyecto
cd mi-plugin
```

### Configuración automática

El instalador configurará automáticamente:
- **Estructura MVC** organizada
- **Namespace** personalizado
- **Autoloader** de Composer
- **Archivos base** del plugin
- **Configuración inicial** lista para usar
- **Ejemplos** de controladores y vistas

### ✅ **¡Listo!**

Tendrás un plugin completamente funcional con:
- ✅ Estructura MVC organizada
- ✅ Autoloader de Composer configurado
- ✅ Ejemplos de controladores y vistas
- ✅ Configuración base lista para usar
- ✅ Documentación incluida

### 📦 **Repositorio del instalador**

**GitHub**: https://github.com/antonella-framework/antonella-installer

---

## 🛠️ **Método 2: Instalación Manual**

Si prefieres instalar manualmente en tu entorno existente:

### Paso 1: Descargar el framework

```bash
cd wp-content/plugins/
git clone https://github.com/tu-usuario/antonella-framework-for-wp.git
cd antonella-framework-for-wp
```

### Paso 2: Instalar dependencias

```bash
composer install
```

### Paso 3: Configurar el namespace

```bash
php antonella namespace tu-namespace
```

### Paso 4: Activar el plugin

Ve a **Plugins > Plugins instalados** en tu WordPress y activa "Antonella Framework".

## ⚙️ **Configuración inicial**

### 1. Verificar la instalación

Después de la instalación, verifica que todo funcione correctamente:

```php
// En cualquier archivo PHP de tu plugin
if (class_exists('CH\Config')) {
    echo "✅ Antonella Framework está funcionando correctamente";
} else {
    echo "❌ Error: Framework no encontrado";
}
```

### 2. Configurar el archivo principal

El archivo `antonella-framework.php` contiene la configuración principal:

```php
<?php
/*
Plugin Name: Tu Plugin con Antonella
Description: Plugin desarrollado con Antonella Framework
Version: 1.0.0
Text Domain: tu-textdomain
*/

// El framework se inicializa automáticamente
```

### 3. Personalizar la configuración

Edita el archivo `src/Config.php` para personalizar tu plugin:

```php
<?php
namespace TuNamespace;

class Config
{
    // Configuración de menús de admin
    public $plugin_menu = [
        // Tu configuración aquí
    ];
    
    // Custom Post Types
    public $post_types = [
        // Tu configuración aquí
    ];
    
    // Y más configuraciones...
}
```

## 🔧 **Configuración del entorno de desarrollo**

### Editor de código recomendado

Para una mejor experiencia de desarrollo, configura tu editor:

#### VS Code
Instala estas extensiones:
- PHP Intelephense
- WordPress Snippets
- Docker
- GitLens

#### PHPStorm
Configura:
- WordPress integration
- Composer support
- Docker integration

### Debugging

Para habilitar el debugging en WordPress:

```php
// En wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

## 🧪 **Verificación de la instalación**

### Test básico

Crea un archivo de prueba `test-antonella.php` en tu plugin:

```php
<?php
// test-antonella.php

use TuNamespace\Config;
use TuNamespace\Security;

// Test 1: Verificar autoloading
if (class_exists('TuNamespace\Config')) {
    echo "✅ Autoloading funcionando\n";
} else {
    echo "❌ Error en autoloading\n";
}

// Test 2: Verificar configuración
$config = new Config();
if (!empty($config->plugin_menu)) {
    echo "✅ Configuración cargada\n";
} else {
    echo "⚠️ Configuración vacía\n";
}

// Test 3: Verificar seguridad
if (method_exists('TuNamespace\Security', 'check_user_capability')) {
    echo "✅ Módulo de seguridad disponible\n";
} else {
    echo "❌ Error en módulo de seguridad\n";
}

echo "🎉 Instalación verificada correctamente\n";
```

Ejecuta el test:

```bash
php test-antonella.php
```

## 🚨 **Solución de problemas comunes**

### Error: "Class not found"

**Problema**: Las clases del framework no se encuentran.

**Solución**:
```bash
# Regenerar autoloader
composer dump-autoload

# Verificar permisos
chmod -R 755 vendor/
```

### Error: "Namespace conflicts"

**Problema**: Conflictos con otros plugins.

**Solución**:
```bash
# Cambiar namespace
php antonella namespace nuevo-namespace-unico
```

### Error: "Permission denied"

**Problema**: Permisos incorrectos en archivos.

**Solución**:
```bash
# Corregir permisos
chmod -R 755 antonella-framework/
chown -R www-data:www-data antonella-framework/
```

---

## 🧪 **Entorno de Testing y Desarrollo**

Antonella Framework incluye un **entorno Docker completo** diseñado específicamente para **testear tu plugin** en condiciones reales de WordPress con herramientas de validación profesionales.

### 🎯 **Propósito del entorno Docker**

El entorno Docker **NO es un método de instalación**, sino una herramienta para:

- ✅ **Testear tu plugin** en WordPress real
- ✅ **Validar código** con Plugin Check y herramientas profesionales
- ✅ **Desarrollar localmente** sin configurar WordPress manualmente
- ✅ **Debugging avanzado** con Query Monitor y Debug Bar
- ✅ **Testing completo** antes de publicar tu plugin

### 🚀 **Usar el entorno de testing**

```bash
# 1. Navegar a tu proyecto Antonella
cd mi-plugin

# 2. Levantar el entorno de testing
docker-compose up -d --build

# 3. Acceder al entorno
```

### 🌐 **URLs del entorno**

- **WordPress**: http://localhost:8080
- **Admin**: http://localhost:8080/wp-admin
- **phpMyAdmin**: http://localhost:9000

**Credenciales de testing:**
- Usuario: `test`
- Contraseña: `test`

### 🔧 **Herramientas incluidas automáticamente**

El entorno instala y activa automáticamente:

- **Plugin Check** - Validación oficial de WordPress
- **Query Monitor** - Debugging de consultas y rendimiento
- **Debug Bar** - Información detallada de debugging
- **Theme Check** - Validación de temas
- **Developer** - Herramientas adicionales de desarrollo

### ✅ **Configuración automática**

- WordPress actualizado a la última versión
- Tu plugin Antonella **instalado y activado**
- Permalinks configurados
- Permisos optimizados
- Contenido de prueba creado
- Entorno listo para desarrollo

### 💡 **Flujo de trabajo recomendado**

1. **Desarrolla** tu plugin con Antonella
2. **Levanta** el entorno Docker para testing
3. **Valida** tu código con Plugin Check
4. **Debuggea** con Query Monitor
5. **Testea** funcionalidades en WordPress real
6. **Publica** con confianza

---

## 📚 **Próximos pasos**

¡Felicidades! Ya tienes Antonella Framework instalado y funcionando. Ahora puedes:

1. **[Crear tu primer plugin](./first-steps.md)** con el framework
2. **[Explorar la arquitectura](../architecture/mvc.md)** del framework
3. **[Crear controladores](../guides/creating-controllers.md)** personalizados

---

> 💡 **Tip**: Si encuentras algún problema durante la instalación, revisa los logs de WordPress en `wp-content/debug.log` o contacta con la comunidad en nuestro [repositorio de GitHub](https://github.com/antonella-framework/antonella-framework-for-wp/issues).
