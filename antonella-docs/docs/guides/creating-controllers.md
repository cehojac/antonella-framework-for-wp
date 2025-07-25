# Creando Controladores

Los controladores en Antonella Framework manejan la lógica de tu aplicación y se integran perfectamente con el sistema de configuración. Esta guía te enseñará cómo crear controladores y registrarlos correctamente en `Config.php`.

## 🎯 **¿Qué es un controlador en Antonella?**

Un controlador es una clase que:
- **Maneja acciones** específicas (hooks, formularios, AJAX)
- **Procesa shortcodes** personalizados
- **Gestiona endpoints** de API REST
- **Integra con WordPress** a través de `Config.php`
- **Sigue el patrón MVC** del framework

## 📋 **Paso 1: Crear el controlador**

### **Estructura básica:**

```php
<?php
namespace CH\Controllers;

use CH\Security;

class MiController
{
    /**
     * Acción principal del controlador
     */
    public static function index()
    {
        // Verificar permisos
        Security::check_user_capability('read');
        
        // Tu lógica aquí
        $data = [
            'mensaje' => 'Hola desde Antonella Framework'
        ];
        
        return $data;
    }
    
    /**
     * Shortcode personalizado
     */
    public static function mi_shortcode($atts = [], $content = null)
    {
        // Sanitizar atributos
        $atts = shortcode_atts([
            'titulo' => 'Título por defecto',
            'clase' => 'mi-clase'
        ], $atts);
        
        // Generar HTML
        $html = '<div class="' . esc_attr($atts['clase']) . '">';
        $html .= '<h3>' . esc_html($atts['titulo']) . '</h3>';
        $html .= '<p>' . esc_html($content) . '</p>';
        $html .= '</div>';
        
        return $html;
    }
    public static function create()
    {
        Security::check_user_capability('edit_posts');
        
        if ($_POST) {
            return self::store();
        }
        
        self::render('create');
    }
    
    /**
     * Procesar creación
     */
    private static function store()
    {
        // Verificar nonce
        Security::verify_nonce('tu_nonce', 'tu_action');
        
        // Sanitizar datos
        $data = self::sanitizeInput($_POST);
        
        // Validar datos
        $errors = self::validate($data);
        if (!empty($errors)) {
            self::render('create', ['errors' => $errors, 'data' => $data]);
            return;
        }
        
        // Crear elemento
        // Crear usando WordPress API
        $result = wp_insert_post([
            'post_title' => $data['title'],
            'post_content' => $data['content'],
            'post_status' => 'publish',
            'post_type' => 'tu_post_type'
        ]);
        
        if ($result) {
            wp_redirect(admin_url('admin.php?page=tu-page&success=created'));
            exit;
        } else {
            self::render('create', [
                'error' => 'Error al crear el elemento',
                'data' => $data
            ]);
        }
    }
    
    /**
     * Renderizar vista
     */
    private static function render($view, $data = [])
    {
        // Extraer variables para la vista
        extract($data);
        
        // Incluir vista
        $view_file = __DIR__ . "/../Views/tu-controlador/{$view}.php";
        if (file_exists($view_file)) {
            include $view_file;
        } else {
            wp_die("Vista no encontrada: {$view}");
        }
    }
    
    /**
     * Sanitizar entrada
     */
    private static function sanitizeInput($input)
    {
        return [
            'titulo' => Security::sanitize_input($input['titulo'] ?? '', 'text'),
            'contenido' => Security::sanitize_input($input['contenido'] ?? '', 'textarea'),
            'email' => Security::sanitize_input($input['email'] ?? '', 'email'),
        ];
    }
    
    /**
     * Validar datos
     */
    private static function validate($data)
    {
        $errors = [];
        
        if (empty($data['titulo'])) {
            $errors['titulo'] = 'El título es obligatorio';
        }
        
        if (empty($data['contenido'])) {
            $errors['contenido'] = 'El contenido es obligatorio';
        }
        
        if (!empty($data['email']) && !is_email($data['email'])) {
            $errors['email'] = 'Email inválido';
        }
        
        return $errors;
    }
    
    /**
     * Endpoint de API REST
     */
    public static function api_obtener_datos($request)
    {
        // Verificar permisos
        if (!current_user_can('read')) {
            return new \WP_Error('forbidden', 'No tienes permisos', ['status' => 403]);
        }
        
        // Obtener parámetros
        $limite = $request->get_param('limite') ?? 10;
        
        // Tu lógica aquí
        $datos = [
            'elementos' => [
                ['id' => 1, 'nombre' => 'Elemento 1'],
                ['id' => 2, 'nombre' => 'Elemento 2']
            ],
            'total' => 2
        ];
        
        return rest_ensure_response($datos);
    }
    
    /**
     * Procesar formulario POST
     */
    public static function procesar_formulario()
    {
        // Verificar nonce
        Security::verify_nonce('mi_nonce', 'mi_accion');
        
        // Sanitizar datos
        $nombre = Security::sanitize_input($_POST['nombre'] ?? '', 'text');
        $email = Security::sanitize_input($_POST['email'] ?? '', 'email');
        
        // Procesar datos
        // Tu lógica aquí
        
        // Redireccionar
        wp_redirect(admin_url('admin.php?page=mi-plugin&success=1'));
        exit;
    }
}
```

## 📋 **Paso 2: Registrar en Config.php**

En Antonella Framework, **todos los controladores se registran en `Config.php`**. Aquí te mostramos cómo registrar diferentes tipos de funcionalidades:

### **🎨 Registrar Shortcodes**

```php
// src/Config.php

/**
 * Custom shortcodes
 * Add custom shortcodes for your plugin
 */
public $shortcodes = [
    ['mi_shortcode', __NAMESPACE__.'\Controllers\MiController::mi_shortcode'],
    ['otro_shortcode', __NAMESPACE__.'\Controllers\MiController::otro_shortcode']
];
```

**Uso del shortcode:**
```
[mi_shortcode titulo="Mi Título" clase="mi-clase-css"]Contenido del shortcode[/mi_shortcode]
```

### **🔌 Registrar API Endpoints**

```php
// src/Config.php

/**
 * REST API Endpoints
 * Add custom REST API endpoints for your plugin
 */
public $api_endpoint_name = 'mi-plugin-api';
public $api_endpoint_version = 1;
public $api_endpoints_functions = [
    ['obtener-datos', 'GET', __NAMESPACE__.'\Controllers\MiController::api_obtener_datos'],
    ['crear-elemento', 'POST', __NAMESPACE__.'\Controllers\MiController::api_crear_elemento'],
    ['actualizar/{id}', 'PUT', __NAMESPACE__.'\Controllers\MiController::api_actualizar']
];
```

**URLs generadas:**
- `GET /wp-json/mi-plugin-api/v1/obtener-datos`
- `POST /wp-json/mi-plugin-api/v1/crear-elemento`
- `PUT /wp-json/mi-plugin-api/v1/actualizar/123`

### **⚙️ Registrar Acciones y Filtros**

```php
// src/Config.php

/**
 * add_action data functions
 */
public $add_action = [
    ['init', __NAMESPACE__.'\Controllers\MiController::inicializar', 10, 1],
    ['wp_ajax_mi_accion', __NAMESPACE__.'\Controllers\MiController::procesar_ajax', 10, 0],
    ['wp_ajax_nopriv_mi_accion', __NAMESPACE__.'\Controllers\MiController::procesar_ajax', 10, 0]
];

/**
 * add_filter data functions
 */
public $add_filter = [
    ['the_content', __NAMESPACE__.'\Controllers\MiController::filtrar_contenido', 10, 1],
    ['body_class', __NAMESPACE__.'\Controllers\MiController::agregar_clases_body', 10, 1]
];
```

### **📝 Registrar Procesamiento POST/GET**

```php
// src/Config.php

/**
 * POST data process
 * get the post data and execute the function
 */
public $post = [
    'procesar_formulario' => __NAMESPACE__.'\Controllers\MiController::procesar_formulario',
    'guardar_configuracion' => __NAMESPACE__.'\Controllers\MiController::guardar_configuracion'
];

/**
 * GET data process
 * get the get data and execute the function
 */
public $get = [
    'exportar_datos' => __NAMESPACE__.'\Controllers\MiController::exportar_datos',
    'generar_reporte' => __NAMESPACE__.'\Controllers\MiController::generar_reporte'
];
```

## 💡 **Ejemplo práctico completo**

Vamos a crear un controlador para gestionar "Testimonios" y registrarlo completamente:

### **Paso 1: Crear el controlador**

```php
<?php
// src/Controllers/TestimoniosController.php
namespace CH\Controllers;

use CH\Security;

class TestimoniosController
{
    /**
     * Shortcode para mostrar testimonios
     */
    public static function mostrar_testimonios($atts = [], $content = null)
    {
        $atts = shortcode_atts([
            'limite' => 3,
            'categoria' => '',
            'clase' => 'testimonios-grid'
        ], $atts);
        
        // Obtener testimonios de la base de datos
        $testimonios = self::obtener_testimonios($atts['limite'], $atts['categoria']);
        
        // Generar HTML
        $html = '<div class="' . esc_attr($atts['clase']) . '">';
        foreach ($testimonios as $testimonio) {
            $html .= '<div class="testimonio">';
            $html .= '<p>' . esc_html($testimonio['contenido']) . '</p>';
            $html .= '<cite>' . esc_html($testimonio['autor']) . '</cite>';
            $html .= '</div>';
        }
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * API: Obtener testimonios
     */
    public static function api_obtener_testimonios($request)
    {
        // Verificar permisos
        if (!current_user_can('read')) {
            return new \WP_Error('forbidden', 'Sin permisos', ['status' => 403]);
        }
        
        $limite = $request->get_param('limite') ?? 10;
        $categoria = $request->get_param('categoria') ?? '';
        
        $testimonios = self::obtener_testimonios($limite, $categoria);
        
        return rest_ensure_response([
            'testimonios' => $testimonios,
            'total' => count($testimonios)
        ]);
    }
    
    /**
     * Procesar formulario de nuevo testimonio
     */
    public static function crear_testimonio()
    {
        // Verificar nonce
        Security::verify_nonce('testimonio_nonce', 'crear_testimonio');
        
        // Sanitizar datos
        $autor = Security::sanitize_input($_POST['autor'] ?? '', 'text');
        $contenido = Security::sanitize_input($_POST['contenido'] ?? '', 'textarea');
        $email = Security::sanitize_input($_POST['email'] ?? '', 'email');
        
        // Validar
        if (empty($autor) || empty($contenido)) {
            wp_redirect(add_query_arg(['error' => 'campos_requeridos'], wp_get_referer()));
            exit;
        }
        
        // Guardar testimonio
        $resultado = self::guardar_testimonio($autor, $contenido, $email);
        
        if ($resultado) {
            wp_redirect(add_query_arg(['success' => 'testimonio_creado'], wp_get_referer()));
        } else {
            wp_redirect(add_query_arg(['error' => 'error_guardar'], wp_get_referer()));
        }
        exit;
    }
    
    /**
     * Filtro para agregar testimonios al contenido
     */
    public static function agregar_testimonios_contenido($content)
    {
        // Solo en posts individuales
        if (!is_single()) {
            return $content;
        }
        
        // Agregar testimonios al final del contenido
        $testimonios = do_shortcode('[mostrar_testimonios limite="2"]');
        return $content . '<div class="testimonios-relacionados">' . $testimonios . '</div>';
    }
    
    // Métodos privados auxiliares
    private static function obtener_testimonios($limite, $categoria)
    {
        // Aquí iría tu lógica para obtener de la BD
        return [
            ['autor' => 'Juan Pérez', 'contenido' => 'Excelente plugin!', 'categoria' => 'general'],
            ['autor' => 'María García', 'contenido' => 'Muy fácil de usar', 'categoria' => 'usabilidad']
        ];
    }
    
    private static function guardar_testimonio($autor, $contenido, $email)
    {
        // Aquí iría tu lógica para guardar en la BD
        return true;
    }
}
```

### **Paso 2: Registrar en Config.php**

```php
// src/Config.php

class Config
{
    // ... otras propiedades ...
    
    /**
     * Custom shortcodes
     */
    public $shortcodes = [
        ['mostrar_testimonios', __NAMESPACE__.'\Controllers\TestimoniosController::mostrar_testimonios']
    ];
    
    /**
     * REST API Endpoints
     */
    public $api_endpoint_name = 'mi-plugin';
    public $api_endpoint_version = 1;
    public $api_endpoints_functions = [
        ['testimonios', 'GET', __NAMESPACE__.'\Controllers\TestimoniosController::api_obtener_testimonios']
    ];
    
    /**
     * POST data process
     */
    public $post = [
        'crear_testimonio' => __NAMESPACE__.'\Controllers\TestimoniosController::crear_testimonio'
    ];
    
    /**
     * add_filter data functions
     */
    public $add_filter = [
        ['the_content', __NAMESPACE__.'\Controllers\TestimoniosController::agregar_testimonios_contenido', 10, 1]
    ];
}
```

### **Paso 3: Usar en el frontend**

**Shortcode en posts/páginas:**
```
[mostrar_testimonios limite="5" categoria="general" clase="mis-testimonios"]
```

**API REST:**
```javascript
// JavaScript
fetch('/wp-json/mi-plugin/v1/testimonios')
    .then(response => response.json())
    .then(data => console.log(data.testimonios));
```

**Formulario HTML:**
```html
<form method="post">
    <?php wp_nonce_field('crear_testimonio', 'testimonio_nonce'); ?>
    <input type="hidden" name="action" value="crear_testimonio">
    
    <input type="text" name="autor" placeholder="Tu nombre" required>
    <textarea name="contenido" placeholder="Tu testimonio" required></textarea>
    <input type="email" name="email" placeholder="Tu email">
    
    <button type="submit">Enviar Testimonio</button>
</form>
```

## ✅ **Mejores prácticas**

### **🔒 1. Seguridad siempre primero**

```php
// ✅ Correcto - Siempre verificar permisos y nonces
public static function procesar_datos()
{
    // 1. Verificar permisos de usuario
    Security::check_user_capability('edit_posts');
    
    // 2. Verificar nonce para CSRF
    Security::verify_nonce('mi_nonce', 'mi_accion');
    
    // 3. Sanitizar TODA la entrada
    $nombre = Security::sanitize_input($_POST['nombre'] ?? '', 'text');
    $email = Security::sanitize_input($_POST['email'] ?? '', 'email');
    
    // 4. Validar antes de procesar
    if (empty($nombre) || !is_email($email)) {
        wp_redirect(add_query_arg(['error' => 'datos_invalidos'], wp_get_referer()));
        exit;
    }
    
    // 5. Procesar de forma segura
}

// ❌ Incorrecto - Sin verificaciones
public static function procesar_datos_inseguro()
{
    $nombre = $_POST['nombre']; // ¡Peligroso!
    // Procesar sin verificaciones...
}
```

### **📋 2. Registro correcto en Config.php**

```php
// ✅ Correcto - Usar la estructura exacta del framework
class Config
{
    public $shortcodes = [
        ['mi_shortcode', __NAMESPACE__.'\Controllers\MiController::mi_shortcode']
    ];
    
    public $api_endpoints_functions = [
        ['endpoint', 'GET', __NAMESPACE__.'\Controllers\MiController::api_method']
    ];
    
    public $add_action = [
        ['init', __NAMESPACE__.'\Controllers\MiController::inicializar', 10, 1]
    ];
}

// ❌ Incorrecto - Estructura incorrecta
public $shortcodes = 'MiController::mi_shortcode'; // ¡Error!
```

### **🎯 3. Métodos estáticos siempre**

```php
// ✅ Correcto - Métodos estáticos
class MiController
{
    public static function mi_metodo()
    {
        // Tu código aquí
    }
}

// ❌ Incorrecto - Métodos no estáticos
class MiController
{
    public function mi_metodo() // ¡Error! Debe ser static
    {
        // No funcionará con Antonella
    }
}
```

### **🔄 4. Manejo de errores profesional**

```php
public static function api_endpoint($request)
{
    // Verificar permisos
    if (!current_user_can('read')) {
        return new \WP_Error('forbidden', 'Sin permisos', ['status' => 403]);
    }
    
    try {
        // Tu lógica aquí
        $datos = self::obtener_datos();
        
        if (empty($datos)) {
            return new \WP_Error('no_data', 'No hay datos', ['status' => 404]);
        }
        
        return rest_ensure_response($datos);
        
    } catch (Exception $e) {
        error_log('Error en API: ' . $e->getMessage());
        return new \WP_Error('server_error', 'Error interno', ['status' => 500]);
    }
}
```

### **📝 5. Documentación clara**

```php
/**
 * Procesa formulario de contacto con validación completa
 * 
 * @since 1.0.0
 * @return void Redirige tras procesar
 */
public static function procesar_contacto()
{
    // Código bien documentado
}
```

## 🚀 **Flujo de trabajo recomendado**

1. **📝 Planifica** tu controlador y sus métodos
2. **🏗️ Crea** el archivo del controlador en `src/Controllers/`
3. **📋 Registra** en `Config.php` según el tipo de funcionalidad
4. **🧪 Testea** en el entorno Docker de Antonella
5. **✅ Valida** con Plugin Check
6. **🚀 Despliega** con confianza

## 🎯 **Puntos clave para recordar**

- ✅ **Todos los controladores** se registran en `Config.php`
- ✅ **Métodos siempre estáticos** en los controladores
- ✅ **Namespace correcto**: `CH\Controllers\`
- ✅ **Seguridad primero**: permisos, nonces, sanitización
- ✅ **Usar el entorno Docker** para testing
- ✅ **Seguir la estructura** del framework Antonella

## 📚 **Próximos pasos**

Ahora que sabes crear controladores, explora:

- **[Configuración avanzada](../configuration/config-overview.md)** - Personalizar tu plugin
- **[Ejemplos prácticos](../examples/crud-example.md)** - Ver más casos de uso
- **[Testing](../advanced/testing.md)** - Validar tu código
- **[API Reference](../configuration/api-endpoints.md)** - Referencia completa

---

> 💡 **¡Importante!** En Antonella Framework, **Config.php es el corazón** donde se registran todas las funcionalidades. Sin registro en Config.php, tu controlador no funcionará.
