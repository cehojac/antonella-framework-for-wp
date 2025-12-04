#!/bin/bash
set -e

echo "🚀 Iniciando contenedor de WordPress..."

# Ejecutar el entrypoint original de WordPress
docker-entrypoint.sh "$@" &

# Obtener el PID del proceso de WordPress
WORDPRESS_PID=$!

echo "⏳ Esperando a que WordPress se inicie..."
# Esperar más tiempo para que WordPress se inicie completamente
sleep 30

echo "🔧 Ejecutando script de inicialización de WordPress..."
# Ejecutar la inicialización en segundo plano con logging
/docker-scripts/init-wordpress.sh 2>&1 | tee /tmp/init-wordpress.log &

# Esperar a que WordPress termine
wait $WORDPRESS_PID
