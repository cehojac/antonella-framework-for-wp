---
title: "Reporte de Error: Falla en la Instalación del Framework en Fedora con Docker"
id: 20251020-01
date: 2025-10-20
author: Miguel BC
---

# Informe técnico: problemas de instalación en Fedora (Docker)

## Resumen ejecutivo

El instalador automático del framework (Composer + scripts PHP que levantan un entorno Docker y ejecutan WP-CLI) no completa la instalación en Fedora. Se identificaron varios problemas encadenados: conflictos de dependencias globales de Composer, puertos ocupados en Docker, políticas SELinux que bloquean el acceso a volúmenes montados y un bug en el script que renombra el namespace del plugin.

## Metadatos

- **ID del reporte:** `20251020-01`
- **Fecha:** 20 de octubre de 2025
- **Autor:** Miguel BC
- **Framework:** Antonella Framework v1.9.3
- **Entorno:** Fedora Linux (SELinux enforcing), Docker, PHP 8+, Composer, WP-CLI

## 1. Entorno de pruebas

- Sistema operativo: Fedora Linux (SELinux en modo enforcing)
- Contenedores: Docker (docker-compose)
- Herramientas: PHP 8+, Composer, WP-CLI

## 2. Resumen de problemas encontrados

1. Conflicto de dependencias al instalar Composer globalmente (`symfony/console`).
2. Conflicto de puertos en Docker (por ejemplo, `9000` ya ocupado).
3. El script automático se queda en "Esperando a que MySQL esté disponible..." y no completa la instalación de WordPress.
4. SELinux impide que los procesos del contenedor lean archivos montados desde el host (plugin no detectado).
5. Inconsistencia de namespace provocada por `php antonella namespace` (autoloader roto, error fatal de PHP).

## 3. Pasos para reproducir

1. Instalar prerrequisitos en Fedora: PHP, Composer, Docker.
2. Crear proyecto:

```bash
composer create-project --prefer-dist cehojac/antonella-framework-for-wp:dev-master mi-plugin-de-prueba
```

3. Entrar al directorio del proyecto y ejecutar el instalador:

```bash
cd mi-plugin-de-prueba
php antonella serve
```

4. Observar los logs: el proceso se detiene en "Esperando a que MySQL esté disponible...".
5. Si se fuerza una instalación manual desde el navegador, puede que el plugin no aparezca en la lista de plugins (debido a SELinux o a errores de namespace).

## 4. Comportamiento esperado

Tras `php antonella serve`:

- Contenedores levantados.
- WordPress instalado y configurado automáticamente.
- Namespace del plugin renombrado de forma consistente en `composer.json` y `src/*.php`.
- Plugin `antonella-framework` activado automáticamente.

## 5. Comportamiento observado (detallado)

### 5.1 Conflicto Composer global

- Problema: `composer global require` falló por conflicto de versiones de `symfony/console`.
- Solución aplicada: usar `composer create-project` para aislar dependencias al proyecto.

### 5.2 Conflicto de puertos Docker

- Problema: error `Bind for 0.0.0.0:9000 failed: port is already allocated` al iniciar `docker compose up`.
- Solución: reasignar el puerto del servicio problemático en `docker-compose.yaml`, por ejemplo:

```yaml
services:
  phpmyadmin:
    ports:
      - "9002:80" # 9000 -> 9002
```

### 5.3 Falla del script de instalación automática (WP-CLI)

- Problema: el script se queda esperando a MySQL y no finaliza la instalación.
- Solución: instalación manual controlada usando WP-CLI dentro del contenedor.

Pasos realizados (ejemplo):

```bash
# Levantar servicios en background
docker compose up -d

# Acceder al contenedor wpcli
docker compose exec wpcli bash

# Desde dentro: instalar WP y activar plugins
wp core install --url="http://localhost:8080" --title="Sitio de Prueba" --admin_user=admin --admin_password=pass --admin_email=you@example.com --allow-root
wp plugin activate antonella-framework --allow-root
```

### 5.4 SELinux: plugin no detectado

- Problema: el volumen montado no tenía el contexto requerido para que los procesos dentro del contenedor accedieran a los archivos.
- Solución aplicada en host (Fedora):

```bash
sudo chcon -R -t container_file_t /ruta/al/proyecto
# o, desde la carpeta del proyecto
sudo chcon -R -t container_file_t .

# Reiniciar contenedores
docker compose restart
```

### 5.5 Inconsistencia de namespace (Error fatal de PHP)

- Problema: `php antonella namespace` reportaba haber renombrado el namespace (por ejemplo `TWDYJC`), pero no actualizó `composer.json` ni todos los archivos `.php` en `src/`. Resultado: autoloader roto y error `Class not found`.
- Solución aplicada: reemplazo masivo con `sed` y regeneración del autoloader.

Ejemplo de corrección dentro del contenedor `wpcli`:

```bash
sed -i 's/MIPLUGIN/TWDYJC/g' composer.json
find ./src -type f -name "*.php" -exec sed -i 's/MIPLUGIN/TWDYJC/g' {} +
php composer.phar dump-autoload
```

## 6. Recomendaciones y pasos a seguir

- Documentar en el README del proyecto que en sistemas con SELinux (Fedora/CentOS) es necesario aplicar `container_file_t` a los directorios montados, o usar la opción `:z`/`:Z` en los volúmenes de Docker cuando sea apropiado.
- Mejorar el script `php antonella namespace` para que:
  - Actualice `composer.json` y todos los archivos `.php` en `src/` de forma atómica.
  - Valide la estructura del autoload y ejecute `composer dump-autoload` al final.
  - Añada tests unitarios que simulen el renombrado de namespace.
- Añadir lógica de reintento/wait-for en el script que ejecuta WP-CLI para esperar a MySQL (por ejemplo con timeout y backoff exponencial) en lugar de colgar indefinidamente.
- Incluir en la documentación una sección de solución de problemas con los comandos usados (`chcon`, `sed`, `dump-autoload`, `wp-cli`).

## 7. Conclusión

La instalación fue posible tras aplicar varios arreglos manuales. Las causas principales fueron: aislamiento inadecuado de dependencias (Composer global), conflictos de red en Docker, políticas SELinux en el host y un bug en el script de renombrado de namespace. Con las correcciones indicadas se logra una instalación reproducible y documentada.

## Anexo: comandos útiles

```bash
# Levantar contenedores en background
docker compose up -d

# Asignar contexto SELinux (en Fedora)
sudo chcon -R -t container_file_t /ruta/al/proyecto

# Entrar a wpcli y ejecutar instalación manual
docker compose exec wpcli bash
wp core install --allow-root ...

# Reemplazar namespaces y regenerar autoloader
sed -i 's/MIPLUGIN/TWDYJC/g' composer.json
find ./src -type f -name "*.php" -exec sed -i 's/MIPLUGIN/TWDYJC/g' {} +
php composer.phar dump-autoload

# Activar plugin
wp plugin activate antonella-framework --allow-root
```

Reporte generado: 20 de octubre de 2025
