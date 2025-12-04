#!/bin/bash

# Script para inicializar WordPress automáticamente
set -e

echo "🚀 Iniciando configuración automática de WordPress..."

# Esperar a que MySQL esté listo
echo "⏳ Esperando a que MySQL esté disponible..."
while ! mysqladmin ping -h"mysql" -u"wordpress" -p"wordpress" --silent; do
    sleep 1
done
echo "✅ MySQL está listo"

# Esperar a que WordPress esté disponible
echo "⏳ Esperando a que WordPress esté disponible..."
while ! curl -s http://localhost > /dev/null; do
    sleep 2
done
echo "✅ WordPress está disponible"

# Verificar si WordPress ya está instalado
if wp core is-installed --allow-root --path=/var/www/html; then
    echo "✅ WordPress ya está instalado"
    
    # Actualizar WordPress a la última versión
    echo "🔄 Verificando actualizaciones de WordPress..."
    if wp core check-update --allow-root --path=/var/www/html --format=count; then
        echo "📥 Actualizando WordPress a la última versión..."
        wp core update --allow-root --path=/var/www/html
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
        --path=/var/www/html
    
    echo "✅ WordPress instalado correctamente"
    
    # Actualizar WordPress a la última versión después de la instalación
    echo "🔄 Actualizando WordPress a la última versión..."
    wp core update --allow-root --path=/var/www/html
    echo "✅ WordPress actualizado a la última versión"
fi

# Desinstalar plugins por defecto de WordPress
echo "🗑️  Desinstalando plugins por defecto..."
wp plugin delete hello-dolly --allow-root --path=/var/www/html || echo "⚠️  Hello Dolly ya no está instalado"
wp plugin delete akismet --allow-root --path=/var/www/html || echo "⚠️  Akismet ya no está instalado"
echo "✅ Plugins por defecto eliminados"

# Activar el framework Antonella
echo "🔌 Activando Antonella Framework..."
wp plugin activate antonella-framework --allow-root --path=/var/www/html || echo "⚠️  Plugin antonella-framework no encontrado, asegúrate de que esté en la carpeta correcta"

# Instalar y activar Plugin Check
echo "📥 Instalando Plugin Check..."
wp plugin install plugin-check --activate --allow-root --path=/var/www/html

# Instalar otros plugins útiles para desarrollo
echo "📥 Instalando plugins adicionales para desarrollo..."

# Query Monitor - Para debugging
wp plugin install query-monitor --activate --allow-root --path=/var/www/html

# Debug Bar - Para debugging adicional
wp plugin install debug-bar --activate --allow-root --path=/var/www/html

# Theme Check - Para verificar temas
wp plugin install theme-check --activate --allow-root --path=/var/www/html

# Developer - Herramientas de desarrollo
wp plugin install developer --activate --allow-root --path=/var/www/html

# Configurar tema por defecto
echo "🎨 Configurando tema..."
wp theme activate twentytwentyfour --allow-root --path=/var/www/html

# Configurar permalinks
echo "🔗 Configurando permalinks..."
wp rewrite structure '/%postname%/' --allow-root --path=/var/www/html
wp rewrite flush --allow-root --path=/var/www/html
echo "✅ Permalinks configurados"

# Corregir permisos de WordPress para actualizaciones
echo "🔧 Corrigiendo permisos de WordPress..."
chown -R www-data:www-data /var/www/html/wp-content/
chmod -R 755 /var/www/html/wp-content/
chmod -R 775 /var/www/html/wp-content/uploads/
chmod -R 775 /var/www/html/wp-content/upgrade/
echo "✅ Permisos de WordPress corregidos"

# Configurar opciones de desarrollo
echo "⚙️  Configurando opciones de desarrollo..."
wp option update blog_public 0 --allow-root --path=/var/www/html  # No indexar por motores de búsqueda
wp option update users_can_register 1 --allow-root --path=/var/www/html  # Permitir registro de usuarios

# Crear contenido de ejemplo
echo "📝 Creando contenido de ejemplo..."
wp post create --post_type=page --post_title="Página de Prueba Antonella" --post_content="Esta es una página de prueba para el framework Antonella." --post_status=publish --allow-root --path=/var/www/html

wp post create --post_title="Post de Prueba Antonella" --post_content="Este es un post de prueba para demostrar las funcionalidades del framework Antonella." --post_status=publish --allow-root --path=/var/www/html

echo "🎉 ¡Configuración completada!"
echo "📍 Accede a tu sitio en: http://localhost:${WORDPRESS_PORT:-8080}"
echo "🔐 Admin: http://localhost:${WORDPRESS_PORT:-8080}/wp-admin"
echo "👤 Usuario: test"
echo "🔑 Contraseña: test"
echo "🗄️  phpMyAdmin: http://localhost:${PHPMYADMIN_PORT:-9000}"
