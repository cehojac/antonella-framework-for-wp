# Deployment - Despliegue de Plugins

Guía completa para desplegar plugins desarrollados con Antonella Framework en diferentes entornos: desarrollo, staging y producción.

## 🎯 **Estrategias de despliegue**

- **Manual**: Subida directa via FTP/SFTP
- **Git-based**: Despliegue desde repositorio
- **CI/CD**: Automatización completa
- **WordPress.org**: Publicación en el directorio oficial
- **Distribución privada**: Para clientes específicos

## 🏗️ **Preparación para producción**

### 1. **Checklist pre-despliegue**

```bash
# ✅ Verificar versión
# ✅ Ejecutar pruebas
# ✅ Optimizar assets
# ✅ Limpiar código de desarrollo
# ✅ Verificar dependencias
# ✅ Actualizar documentación
# ✅ Crear backup
```

### 2. **Configuración de entornos**

```php
<?php
// src/Config/Environment.php
namespace MiPlugin\Config;

class Environment
{
    public static function isDevelopment()
    {
        return defined('WP_DEBUG') && WP_DEBUG;
    }

    public static function isStaging()
    {
        return defined('WP_ENV') && WP_ENV === 'staging';
    }

    public static function isProduction()
    {
        return !self::isDevelopment() && !self::isStaging();
    }

    public static function getConfig()
    {
        if (self::isProduction()) {
            return [
                'debug' => false,
                'cache_duration' => 3600,
                'log_level' => 'error',
                'minify_assets' => true
            ];
        }

        if (self::isStaging()) {
            return [
                'debug' => true,
                'cache_duration' => 300,
                'log_level' => 'warning',
                'minify_assets' => true
            ];
        }

        // Development
        return [
            'debug' => true,
            'cache_duration' => 0,
            'log_level' => 'debug',
            'minify_assets' => false
        ];
    }
}
```

### 3. **Script de build**

```bash
#!/bin/bash
# build.sh - Script de construcción para producción

echo "🚀 Iniciando build para producción..."

# Limpiar directorio de build
rm -rf build/
mkdir -p build/

# Copiar archivos necesarios
echo "📁 Copiando archivos..."
cp -r src/ build/
cp -r assets/ build/
cp -r languages/ build/
cp *.php build/
cp readme.txt build/
cp composer.json build/

# Instalar dependencias de producción
echo "📦 Instalando dependencias..."
cd build/
composer install --no-dev --optimize-autoloader

# Optimizar assets
echo "⚡ Optimizando assets..."
if command -v npm &> /dev/null; then
    npm run build:production
fi

# Minificar CSS y JS
if command -v uglifyjs &> /dev/null; then
    find assets/js -name "*.js" -not -name "*.min.js" -exec uglifyjs {} -o {}.min.js \;
fi

if command -v cleancss &> /dev/null; then
    find assets/css -name "*.css" -not -name "*.min.css" -exec cleancss {} -o {}.min.css \;
fi

# Limpiar archivos de desarrollo
echo "🧹 Limpiando archivos de desarrollo..."
rm -rf tests/
rm -rf node_modules/
rm -f .gitignore
rm -f phpunit.xml
rm -f package.json
rm -f webpack.config.js

# Crear archivo ZIP
echo "📦 Creando archivo ZIP..."
cd ..
zip -r "mi-plugin-$(date +%Y%m%d-%H%M%S).zip" build/

echo "✅ Build completado!"
```

## 🔄 **CI/CD con GitHub Actions**

### 1. **Workflow básico (.github/workflows/deploy.yml)**

```yaml
name: Deploy Plugin

on:
  push:
    tags:
      - 'v*'
  workflow_dispatch:

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:5.7
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: wordpress_test
        ports:
          - 3306:3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
    - uses: actions/checkout@v3

    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.0'
        extensions: mysql, zip

    - name: Install Composer dependencies
      run: composer install --prefer-dist --no-progress

    - name: Setup WordPress test environment
      run: |
        bash bin/install-wp-tests.sh wordpress_test root root 127.0.0.1 latest
        
    - name: Run PHPUnit tests
      run: vendor/bin/phpunit

    - name: Run PHP CodeSniffer
      run: vendor/bin/phpcs --standard=WordPress src/

  build:
    needs: test
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v3

    - name: Setup Node.js
      uses: actions/setup-node@v3
      with:
        node-version: '16'
        cache: 'npm'

    - name: Install Node dependencies
      run: npm ci

    - name: Build assets
      run: npm run build:production

    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.0'

    - name: Install Composer dependencies
      run: composer install --no-dev --optimize-autoloader

    - name: Create build directory
      run: |
        mkdir build
        rsync -av --exclude-from='.deployignore' . build/
        
    - name: Create ZIP file
      run: |
        cd build
        zip -r ../mi-plugin-${{ github.ref_name }}.zip .
        
    - name: Upload build artifact
      uses: actions/upload-artifact@v3
      with:
        name: plugin-build
        path: mi-plugin-${{ github.ref_name }}.zip

  deploy-staging:
    needs: build
    runs-on: ubuntu-latest
    if: contains(github.ref, 'refs/heads/develop')
    
    steps:
    - name: Download build artifact
      uses: actions/download-artifact@v3
      with:
        name: plugin-build

    - name: Deploy to staging
      uses: SamKirkland/FTP-Deploy-Action@4.3.3
      with:
        server: ${{ secrets.STAGING_FTP_HOST }}
        username: ${{ secrets.STAGING_FTP_USER }}
        password: ${{ secrets.STAGING_FTP_PASSWORD }}
        local-dir: ./
        server-dir: /wp-content/plugins/mi-plugin/

  deploy-production:
    needs: build
    runs-on: ubuntu-latest
    if: startsWith(github.ref, 'refs/tags/v')
    environment: production
    
    steps:
    - name: Download build artifact
      uses: actions/download-artifact@v3
      with:
        name: plugin-build

    - name: Deploy to production
      uses: SamKirkland/FTP-Deploy-Action@4.3.3
      with:
        server: ${{ secrets.PROD_FTP_HOST }}
        username: ${{ secrets.PROD_FTP_USER }}
        password: ${{ secrets.PROD_FTP_PASSWORD }}
        local-dir: ./
        server-dir: /wp-content/plugins/mi-plugin/

    - name: Create GitHub Release
      uses: softprops/action-gh-release@v1
      with:
        files: mi-plugin-${{ github.ref_name }}.zip
        generate_release_notes: true
```

### 2. **Archivo de exclusión (.deployignore)**

```
.git/
.github/
tests/
node_modules/
.env
.env.local
phpunit.xml
composer.lock
package-lock.json
webpack.config.js
.gitignore
.deployignore
README.md
build.sh
```

## 🌐 **Despliegue manual**

### 1. **Via FTP/SFTP**

```bash
# Crear build
./build.sh

# Subir via SFTP
sftp usuario@servidor.com
put -r build/* /path/to/wp-content/plugins/mi-plugin/
```

### 2. **Via WP-CLI**

```bash
# Instalar plugin desde ZIP
wp plugin install mi-plugin.zip --activate

# Actualizar plugin
wp plugin update mi-plugin

# Verificar instalación
wp plugin status mi-plugin
```

## 📦 **Distribución en WordPress.org**

### 1. **Preparar para el directorio oficial**

```php
<?php
/*
Plugin Name: Mi Plugin
Plugin URI: https://wordpress.org/plugins/mi-plugin/
Description: Descripción del plugin para WordPress.org
Version: 1.0.0
Author: Tu Nombre
Author URI: https://tu-sitio.com
Text Domain: mi-plugin
Domain Path: /languages
License: GPL2+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Mi Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
*/
```

### 2. **README.txt para WordPress.org**

```
=== Mi Plugin ===
Contributors: tu-usuario
Tags: antonella, framework, mvc
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL2+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Descripción corta del plugin (máximo 150 caracteres).

== Description ==

Descripción detallada del plugin, sus características y beneficios.

= Características principales =

* Característica 1
* Característica 2
* Característica 3

= Documentación =

Para documentación completa, visita [nuestra documentación](https://tu-sitio.com/docs).

== Installation ==

1. Sube el plugin al directorio `/wp-content/plugins/`
2. Activa el plugin desde el menú 'Plugins' en WordPress
3. Configura el plugin desde el menú de administración

== Frequently Asked Questions ==

= ¿Pregunta frecuente 1? =

Respuesta a la pregunta frecuente 1.

= ¿Pregunta frecuente 2? =

Respuesta a la pregunta frecuente 2.

== Screenshots ==

1. Captura de pantalla 1
2. Captura de pantalla 2

== Changelog ==

= 1.0.0 =
* Lanzamiento inicial

== Upgrade Notice ==

= 1.0.0 =
Lanzamiento inicial del plugin.
```

### 3. **Proceso de envío**

```bash
# 1. Crear cuenta en WordPress.org
# 2. Enviar plugin para revisión
# 3. Esperar aprobación (puede tomar semanas)
# 4. Configurar SVN para actualizaciones

# Checkout del repositorio SVN
svn checkout https://plugins.svn.wordpress.org/mi-plugin

# Estructura del repositorio
mi-plugin/
├── trunk/          # Versión de desarrollo
├── tags/           # Versiones estables
│   ├── 1.0.0/
│   └── 1.0.1/
└── assets/         # Screenshots, banners, iconos
    ├── screenshot-1.png
    ├── banner-772x250.png
    └── icon-256x256.png
```

## 🔧 **Automatización con scripts**

### 1. **Script de versioning**

```bash
#!/bin/bash
# version.sh - Actualizar versión en todos los archivos

if [ -z "$1" ]; then
    echo "Uso: ./version.sh <nueva-version>"
    exit 1
fi

NEW_VERSION=$1

echo "🔄 Actualizando versión a $NEW_VERSION..."

# Actualizar archivo principal
sed -i "s/Version: .*/Version: $NEW_VERSION/" mi-plugin.php

# Actualizar README.txt
sed -i "s/Stable tag: .*/Stable tag: $NEW_VERSION/" readme.txt

# Actualizar composer.json
sed -i "s/\"version\": \".*\"/\"version\": \"$NEW_VERSION\"/" composer.json

# Actualizar package.json
sed -i "s/\"version\": \".*\"/\"version\": \"$NEW_VERSION\"/" package.json

# Crear tag en Git
git add .
git commit -m "Bump version to $NEW_VERSION"
git tag -a "v$NEW_VERSION" -m "Version $NEW_VERSION"

echo "✅ Versión actualizada a $NEW_VERSION"
echo "📝 No olvides actualizar el CHANGELOG.md"
```

### 2. **Script de release**

```bash
#!/bin/bash
# release.sh - Crear release completo

VERSION=$1
if [ -z "$VERSION" ]; then
    echo "Uso: ./release.sh <version>"
    exit 1
fi

echo "🚀 Creando release $VERSION..."

# Actualizar versión
./version.sh $VERSION

# Ejecutar pruebas
echo "🧪 Ejecutando pruebas..."
composer test
if [ $? -ne 0 ]; then
    echo "❌ Las pruebas fallaron. Abortando release."
    exit 1
fi

# Crear build
echo "📦 Creando build..."
./build.sh

# Push a Git
echo "📤 Subiendo a Git..."
git push origin main
git push origin --tags

# Crear release en GitHub (requiere gh CLI)
if command -v gh &> /dev/null; then
    echo "🎉 Creando release en GitHub..."
    gh release create "v$VERSION" "mi-plugin-*.zip" \
        --title "Version $VERSION" \
        --generate-notes
fi

echo "✅ Release $VERSION completado!"
```

## 🔍 **Monitoreo post-despliegue**

### 1. **Health checks**

```php
<?php
// src/HealthCheck.php
namespace MiPlugin;

class HealthCheck
{
    public static function run()
    {
        $checks = [
            'database' => self::checkDatabase(),
            'dependencies' => self::checkDependencies(),
            'permissions' => self::checkPermissions(),
            'api' => self::checkApi()
        ];

        return $checks;
    }

    private static function checkDatabase()
    {
        global $wpdb;
        
        try {
            $wpdb->get_var("SELECT 1");
            return ['status' => 'ok', 'message' => 'Database connection OK'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private static function checkDependencies()
    {
        $required = ['curl', 'json', 'mbstring'];
        $missing = [];

        foreach ($required as $ext) {
            if (!extension_loaded($ext)) {
                $missing[] = $ext;
            }
        }

        if (empty($missing)) {
            return ['status' => 'ok', 'message' => 'All dependencies OK'];
        }

        return [
            'status' => 'error', 
            'message' => 'Missing extensions: ' . implode(', ', $missing)
        ];
    }

    private static function checkPermissions()
    {
        $upload_dir = wp_upload_dir();
        $writable = is_writable($upload_dir['basedir']);

        return [
            'status' => $writable ? 'ok' : 'error',
            'message' => $writable ? 'Upload directory writable' : 'Upload directory not writable'
        ];
    }

    private static function checkApi()
    {
        $response = wp_remote_get(rest_url('mi-plugin/v1/health'));
        
        if (is_wp_error($response)) {
            return ['status' => 'error', 'message' => $response->get_error_message()];
        }

        $status_code = wp_remote_retrieve_response_code($response);
        
        return [
            'status' => $status_code === 200 ? 'ok' : 'error',
            'message' => "API returned status $status_code"
        ];
    }
}
```

### 2. **Logging de errores**

```php
<?php
// src/Logger.php
namespace MiPlugin;

class Logger
{
    private static $log_file;

    public static function init()
    {
        $upload_dir = wp_upload_dir();
        self::$log_file = $upload_dir['basedir'] . '/mi-plugin.log';
    }

    public static function error($message, $context = [])
    {
        self::log('ERROR', $message, $context);
    }

    public static function warning($message, $context = [])
    {
        self::log('WARNING', $message, $context);
    }

    public static function info($message, $context = [])
    {
        self::log('INFO', $message, $context);
    }

    private static function log($level, $message, $context = [])
    {
        if (!self::$log_file) {
            self::init();
        }

        $timestamp = date('Y-m-d H:i:s');
        $context_str = !empty($context) ? ' ' . json_encode($context) : '';
        $log_entry = "[{$timestamp}] {$level}: {$message}{$context_str}" . PHP_EOL;

        file_put_contents(self::$log_file, $log_entry, FILE_APPEND | LOCK_EX);
    }
}
```

## 🎯 **Mejores prácticas**

### ✅ **Versionado**
- Usa versionado semántico (SemVer)
- Mantén un CHANGELOG.md actualizado
- Taguea todas las releases en Git

### ✅ **Testing**
- Ejecuta pruebas antes de cada despliegue
- Usa staging para validar cambios
- Implementa health checks

### ✅ **Seguridad**
- No incluyas credenciales en el código
- Usa variables de entorno para configuración
- Valida permisos de archivos

### ✅ **Performance**
- Minifica assets en producción
- Optimiza imágenes y recursos
- Usa caché cuando sea apropiado

### ✅ **Monitoreo**
- Implementa logging de errores
- Monitorea métricas clave
- Configura alertas para problemas críticos

---

> 💡 **Tip**: Un proceso de despliegue bien definido reduce errores y acelera las entregas. Automatiza todo lo que puedas y siempre ten un plan de rollback.
