#!/bin/bash
set -e

# Ejecutar el entrypoint original de WordPress
docker-entrypoint.sh "$@" &

# Obtener el PID del proceso de WordPress
WORDPRESS_PID=$!

# Esperar un poco para que WordPress se inicie
sleep 10

# Ejecutar la inicialización en segundo plano
/docker-scripts/init-wordpress.sh &

# Esperar a que WordPress termine
wait $WORDPRESS_PID
