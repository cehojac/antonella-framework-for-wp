#!/bin/bash

# Script para inicializar WordPress automáticamente
# NO usar 'set -e' para manejar errores manualmente
set +e

echo "🚀 Iniciando configuración automática de WordPress..."
echo "📅 $(date)"

# Esperar a que MySQL esté listo con timeout
echo "⏳ Esperando a que MySQL esté disponible..."
MYSQL_TIMEOUT=120  # 2 minutos
MYSQL_COUNTER=0
MYSQL_READY=false

while [ $MYSQL_COUNTER -lt $MYSQL_TIMEOUT ]; do
    if mysqladmin ping -h"mysql" -u"wordpress" -p"wordpress" --skip-ssl --silent 2>/dev/null; then
        MYSQL_READY=true
        echo "✅ MySQL está listo (después de ${MYSQL_COUNTER} segundos)"
        break
    fi
    
    if [ $((MYSQL_COUNTER % 10)) -eq 0 ]; then
        echo "   ⏱️  Esperando MySQL... (${MYSQL_COUNTER}/${MYSQL_TIMEOUT}s)"
    fi
    
    sleep 2
    MYSQL_COUNTER=$((MYSQL_COUNTER + 2))
done

if [ "$MYSQL_READY" = false ]; then
    echo "❌ ERROR: MySQL no está disponible después de ${MYSQL_TIMEOUT} segundos"
    echo "❌ Verifica que el contenedor de MySQL esté corriendo correctamente"
    echo "💡 Ejecuta: docker compose logs mysql"
    exit 1
fi

# Esperar a que WordPress esté disponible con timeout (solo si NO es modo CLI)
if [ -z "$WP_CLI_MODE" ]; then
    echo "⏳ Esperando a que WordPress esté disponible..."
    WP_TIMEOUT=60  # 1 minuto
    WP_COUNTER=0
    WP_READY=false

    while [ $WP_COUNTER -lt $WP_TIMEOUT ]; do
        if curl -s http://localhost > /dev/null 2>&1; then
            WP_READY=true
            echo "✅ WordPress está disponible (después de ${WP_COUNTER} segundos)"
            break
        fi
        
        if [ $((WP_COUNTER % 10)) -eq 0 ]; then
            echo "   ⏱️  Esperando WordPress... (${WP_COUNTER}/${WP_TIMEOUT}s)"
        fi
        
        sleep 2
        WP_COUNTER=$((WP_COUNTER + 2))
    done

    if [ "$WP_READY" = false ]; then
        echo "❌ ERROR: WordPress no está disponible después de ${WP_TIMEOUT} segundos"
        echo "❌ Verifica que Apache esté corriendo correctamente"
        exit 1
    fi
else
    echo "ℹ️  Modo WP-CLI: Saltando verificación de servidor web local."
fi

# =============================================================================
# Lógica de instalación (Solo si NO es modo WP-CLI)
# =============================================================================
if [ -n "$WP_CLI_MODE" ]; then
    echo "ℹ️  Modo WP-CLI: Saltando instalación y configuración."
    echo "✅ Contenedor listo para ejecutar comandos."
    exit 0
fi

# Verificar si WordPress ya está instalado
if wp core is-installed --allow-root --path=/var/www/html --quiet 2>/dev/null; then
    echo "✅ WordPress ya está instalado"
    
    # Actualizar WordPress a la última versión
    echo "🔄 Verificando actualizaciones de WordPress..."
    if wp core check-update --allow-root --path=/var/www/html --format=count --quiet 2>/dev/null | grep -q "^[1-9]"; then
        echo "📥 Actualizando WordPress a la última versión..."
        wp core update --allow-root --path=/var/www/html --quiet 2>/dev/null
        echo "✅ WordPress actualizado correctamente"
    else
        echo "✅ WordPress ya está en la última versión"
    fi
else
    echo "📦 Instalando WordPress..."
    
    # Instalar WordPress
    wp core install \
        --url="http://localhost:${WORDPRESS_PORT:-8080}" \
        --title="Antonella Framework Test" \
        --admin_user="test" \
        --admin_password="test" \
        --admin_email="test@antonella.test" \
        --allow-root \
        --path=/var/www/html \
        --quiet 2>/dev/null
    
    echo "✅ WordPress instalado correctamente"
    
    # Actualizar WordPress a la última versión después de la instalación
    echo "🔄 Actualizando WordPress a la última versión..."
    wp core update --allow-root --path=/var/www/html --quiet 2>/dev/null
    echo "✅ WordPress actualizado a la última versión"
fi

# Desinstalar plugins por defecto de WordPress
echo "🗑️  Desinstalando plugins por defecto..."
wp plugin delete hello-dolly --allow-root --path=/var/www/html --quiet 2>/dev/null || true
wp plugin delete akismet --allow-root --path=/var/www/html --quiet 2>/dev/null || true
echo "✅ Plugins por defecto eliminados"

# Activar el framework Antonella (si existe)
echo "🔌 Verificando Antonella Framework..."
if wp plugin list --name=antonella-framework --allow-root --path=/var/www/html --format=count 2>/dev/null | grep -q "1"; then
    wp plugin activate antonella-framework --allow-root --path=/var/www/html 2>/dev/null && echo "✅ Antonella Framework activado" || echo "⚠️  No se pudo activar antonella-framework"
else
    echo "ℹ️  Plugin antonella-framework no encontrado (se activará cuando esté disponible)"
fi

# Instalar y activar Plugin Check
echo "📥 Instalando plugins de desarrollo..."
wp plugin install plugin-check --activate --allow-root --path=/var/www/html --quiet 2>/dev/null || true

# Query Monitor - Para debugging
wp plugin install query-monitor --activate --allow-root --path=/var/www/html --quiet 2>/dev/null || true

# Debug Bar - Para debugging adicional
wp plugin install debug-bar --activate --allow-root --path=/var/www/html --quiet 2>/dev/null || true

# Theme Check - Para verificar temas
wp plugin install theme-check --activate --allow-root --path=/var/www/html --quiet 2>/dev/null || true

echo "✅ Plugins de desarrollo instalados"

# Configurar tema por defecto
echo "🎨 Configurando tema..."
wp theme activate twentytwentyfour --allow-root --path=/var/www/html --quiet 2>/dev/null || true
echo "✅ Tema configurado"

# Configurar permalinks
echo "🔗 Configurando permalinks..."
wp rewrite structure '/%postname%/' --allow-root --path=/var/www/html --quiet 2>/dev/null
wp rewrite flush --allow-root --path=/var/www/html --quiet 2>/dev/null
echo "✅ Permalinks configurados"

# Corregir permisos de WordPress para actualizaciones
echo "🔧 Corrigiendo permisos de WordPress..."
# Excluir directorios montados del host para evitar errores de permisos en Linux
find /var/www/html/wp-content/ -maxdepth 1 -type d ! -name 'plugins' ! -name 'debug.log' -exec chown -R www-data:www-data {} + 2>/dev/null || true
find /var/www/html/wp-content/ -maxdepth 1 -type d ! -name 'plugins' ! -name 'debug.log' -exec chmod -R 755 {} + 2>/dev/null || true

# Crear y dar permisos a directorios necesarios
mkdir -p /var/www/html/wp-content/uploads /var/www/html/wp-content/upgrade 2>/dev/null || true
chown -R www-data:www-data /var/www/html/wp-content/uploads /var/www/html/wp-content/upgrade 2>/dev/null || true
chmod -R 775 /var/www/html/wp-content/uploads /var/www/html/wp-content/upgrade 2>/dev/null || true
echo "✅ Permisos de WordPress configurados"

# Configurar opciones de desarrollo
echo "⚙️  Configurando opciones de desarrollo..."
wp option update blog_public 0 --allow-root --path=/var/www/html --quiet 2>/dev/null
wp option update users_can_register 1 --allow-root --path=/var/www/html --quiet 2>/dev/null
echo "✅ Opciones de desarrollo configuradas"

# Crear contenido de ejemplo
echo "📝 Creando contenido de ejemplo..."
wp post create --post_type=page --post_title="Página de Prueba Antonella" --post_content="Esta es una página de prueba para el framework Antonella." --post_status=publish --allow-root --path=/var/www/html --quiet 2>/dev/null || true
wp post create --post_title="Post de Prueba Antonella" --post_content="Este es un post de prueba para demostrar las funcionalidades del framework Antonella." --post_status=publish --allow-root --path=/var/www/html --quiet 2>/dev/null || true
echo "✅ Contenido de ejemplo creado"

echo "🎉 ¡Configuración completada!"
echo "📍 Accede a tu sitio en: http://localhost:${WORDPRESS_PORT:-8080}"
echo "🔐 Admin: http://localhost:${WORDPRESS_PORT:-8080}/wp-admin"
echo "👤 Usuario: test"
echo "🔑 Contraseña: test"
echo "🗄️  phpMyAdmin: http://localhost:${PHPMYADMIN_PORT:-9000}"
