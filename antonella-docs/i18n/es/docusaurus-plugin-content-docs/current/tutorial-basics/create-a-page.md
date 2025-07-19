---
sidebar_position: 3
---

# Consola Antonella

La consola Antonella es una herramienta poderosa que te permite crear y gestionar los componentes de tu plugin de manera eficiente. Todos los comandos se ejecutan desde la raíz de tu proyecto.

## Comandos disponibles

### Ayuda general

```bash
php antonella help
```

Muestra todos los comandos disponibles y su descripción.

### Gestión de Namespace

```bash
# Cambiar namespace a uno específico
php antonella namespace MI_NAMESPACE

# Generar un namespace aleatorio
php antonella namespace
```

## Creación de componentes

### Controladores

```bash
php antonella controller MiControlador
```

Crea un nuevo controlador en `src/Controllers/MiControlador.php`

### Modelos

```bash
php antonella model MiModelo
```

Crea un nuevo modelo en `src/Models/MiModelo.php`

### Widgets

```bash
php antonella widget MiWidget
```

Crea un nuevo widget personalizado para WordPress.

### Helpers

```bash
php antonella helper MiHelper
```

Crea un helper personalizado en `src/Helpers/MiHelper.php`

### Bloques de Gutenberg

```bash
php antonella block mi-primer-bloque
```

Crea un bloque personalizado de Gutenberg con toda la estructura necesaria.

## Desarrollo y Testing

### Servidor de desarrollo

```bash
php antonella serve
```

Monta un servidor de testing local en `http://localhost:8010` con WordPress de prueba.

### Refrescar cambios

```bash
php antonella test refresh
```

Refresca los cambios hechos en el desarrollo y los muestra en el servidor de testing.

### Docker

```bash
php antonella docker
```

Inicia el entorno Docker para desarrollo. El sitio estará disponible en `http://localhost:8080`.

## Estructura de archivos generados

### Controlador ejemplo

```php
<?php

namespace TU_NAMESPACE\Controllers;

class MiControlador {
    
    public function __construct() {
        // Constructor
    }
    
    public function miFuncion() {
        // Tu lógica aquí
    }
}
```

### Modelo ejemplo

```php
<?php

namespace TU_NAMESPACE\Models;

class MiModelo {
    
    public function __construct() {
        // Constructor
    }
    
    public function obtenerDatos() {
        // Lógica para obtener datos
    }
}
```

### Helper ejemplo

```php
<?php

namespace TU_NAMESPACE\Helpers;

class MiHelper {
    
    public static function utilidad() {
        // Función de utilidad
    }
}
```

## Consejos y buenas prácticas

- 📝 **Nombrado**: Usa PascalCase para clases y camelCase para funciones
- 📁 **Organización**: Mantén tus controladores enfocados en una sola responsabilidad
- 🔄 **Testing**: Usa regularmente `php antonella test refresh` durante el desarrollo
- 🐳 **Docker**: Utiliza Docker para un entorno de desarrollo consistente

## Ejemplo de flujo de trabajo

1. Crear un controlador:
   ```bash
   php antonella controller ProductController
   ```

2. Crear un modelo relacionado:
   ```bash
   php antonella model Product
   ```

3. Iniciar servidor de desarrollo:
   ```bash
   php antonella serve
   ```

4. Hacer cambios y refrescar:
   ```bash
   php antonella test refresh
   ```

Este flujo te permite desarrollar rápidamente y ver los cambios en tiempo real.
