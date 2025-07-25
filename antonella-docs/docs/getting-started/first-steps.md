# Primeros Pasos con Antonella Framework

¡Bienvenido a tu primer desarrollo con Antonella Framework! En esta guía crearás tu primer plugin funcional paso a paso, aprendiendo los conceptos fundamentales del framework.

## 🎯 **Lo que construiremos**

Crearemos un plugin simple pero completo que incluye:
- Una página de administración
- Un formulario con validación
- Almacenamiento de datos
- API REST endpoint
- Shortcode para el frontend

## 🚀 **Paso 1: Configuración inicial**

### 1.1 Personalizar el namespace

Primero, personaliza el namespace de tu plugin para evitar conflictos:

```bash
php antonella namespace mi-primer-plugin
```

### 1.2 Configurar información básica

Edita el archivo `antonella-framework.php`:

```php
<?php
/*
Plugin Name: Mi Primer Plugin con Antonella
Plugin URI: https://mi-sitio.com/mi-primer-plugin
Description: Mi primer plugin desarrollado con Antonella Framework
Version: 1.0.0
Author: Tu Nombre
Author URI: https://tu-sitio.com
Text Domain: mi-primer-plugin
Domain Path: /languages
*/

namespace MiPrimerPlugin;

// El framework se inicializa automáticamente
defined('ABSPATH') or die(exit());

// Inicializar el framework
require_once __DIR__ . '/vendor/autoload.php';

if (!class_exists('MiPrimerPlugin\Init')) {
    return;
}

new Init();
```

## 🏗️ **Paso 2: Configurar el plugin**

### 2.1 Editar la configuración principal

Abre `src/Config.php` y configura tu plugin:

```php
<?php
namespace MiPrimerPlugin;

class Config
{
    // Configuración del menú de administración
    public $plugin_menu = [
        [
            'page_title' => 'Mi Primer Plugin',
            'menu_title' => 'Mi Plugin',
            'capability' => 'manage_options',
            'menu_slug' => 'mi-primer-plugin',
            'function' => 'MiPrimerPlugin\Admin\Admin::index',
            'icon_url' => 'dashicons-admin-plugins',
            'position' => 30
        ]
    ];

    // Custom Post Type para nuestros datos
    public $post_types = [
        'mi_contenido' => [
            'labels' => [
                'name' => 'Mi Contenido',
                'singular_name' => 'Contenido',
                'add_new' => 'Añadir Nuevo',
                'add_new_item' => 'Añadir Nuevo Contenido',
                'edit_item' => 'Editar Contenido',
                'new_item' => 'Nuevo Contenido',
                'view_item' => 'Ver Contenido',
                'search_items' => 'Buscar Contenido',
                'not_found' => 'No se encontró contenido',
                'not_found_in_trash' => 'No hay contenido en la papelera'
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'menu_icon' => 'dashicons-admin-post'
        ]
    ];

    // Shortcodes
    public $shortcodes = [
        'mi_shortcode' => 'MiPrimerPlugin\Shortcodes::mi_shortcode'
    ];

    // API Endpoints
    public $api_endpoints = [
        [
            'methods' => 'GET',
            'route' => '/mi-plugin/datos',
            'callback' => 'MiPrimerPlugin\Api::obtener_datos',
            'permission_callback' => 'MiPrimerPlugin\Api::verificar_permisos'
        ]
    ];

    // Opciones del plugin
    public $plugin_options = [
        'mi_plugin_configuracion' => 'default_value',
        'mi_plugin_activo' => true
    ];
}
```

## 📄 **Paso 3: Crear la página de administración**

### 3.1 Crear el controlador de administración

Edita `src/Admin/Admin.php`:

```php
<?php
namespace MiPrimerPlugin\Admin;

use MiPrimerPlugin\Security;

class Admin
{
    public static function index()
    {
        // Verificar permisos
        Security::check_user_capability('manage_options');
        
        // Procesar formulario si se envió
        if (isset($_POST['submit']) && isset($_POST['mi_plugin_nonce'])) {
            self::procesar_formulario();
        }
        
        // Mostrar la página
        self::mostrar_pagina();
    }
    
    private static function procesar_formulario()
    {
        // Verificar nonce
        Security::verify_nonce('mi_plugin_nonce', 'mi_plugin_action');
        
        // Sanitizar datos
        $titulo = Security::sanitize_input($_POST['titulo'], 'text');
        $contenido = Security::sanitize_input($_POST['contenido'], 'textarea');
        
        // Guardar en la base de datos
        $post_data = [
            'post_title' => $titulo,
            'post_content' => $contenido,
            'post_type' => 'mi_contenido',
            'post_status' => 'publish'
        ];
        
        $post_id = wp_insert_post($post_data);
        
        if ($post_id) {
            echo '<div class="notice notice-success"><p>¡Contenido guardado exitosamente!</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Error al guardar el contenido.</p></div>';
        }
    }
    
    private static function mostrar_pagina()
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <form method="post" action="">
                <?php
                // Crear nonce field
                echo Security::create_nonce_field('mi_plugin_action', 'mi_plugin_nonce');
                ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="titulo"><?php echo esc_html(__('Título', 'mi-primer-plugin')); ?></label>
                        </th>
                        <td>
                            <input type="text" id="titulo" name="titulo" class="regular-text" required />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="contenido"><?php echo esc_html(__('Contenido', 'mi-primer-plugin')); ?></label>
                        </th>
                        <td>
                            <textarea id="contenido" name="contenido" rows="5" cols="50" class="large-text"></textarea>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(__('Guardar Contenido', 'mi-primer-plugin')); ?>
            </form>
            
            <hr>
            
            <h2>Contenido guardado</h2>
            <?php self::mostrar_contenido_guardado(); ?>
        </div>
        <?php
    }
    
    private static function mostrar_contenido_guardado()
    {
        $posts = get_posts([
            'post_type' => 'mi_contenido',
            'numberposts' => 10,
            'post_status' => 'publish'
        ]);
        
        if (empty($posts)) {
            echo '<p>No hay contenido guardado aún.</p>';
            return;
        }
        
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>Título</th><th>Contenido</th><th>Fecha</th></tr></thead>';
        echo '<tbody>';
        
        foreach ($posts as $post) {
            echo '<tr>';
            echo '<td>' . esc_html($post->post_title) . '</td>';
            echo '<td>' . esc_html(wp_trim_words($post->post_content, 10)) . '</td>';
            echo '<td>' . esc_html(get_the_date('Y-m-d H:i', $post->ID)) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
    }
}
```

## 🔌 **Paso 4: Crear un shortcode**

### 4.1 Implementar el shortcode

Edita `src/Shortcodes.php`:

```php
<?php
namespace MiPrimerPlugin;

class Shortcodes
{
    public static function mi_shortcode($atts)
    {
        // Atributos por defecto
        $atts = shortcode_atts([
            'limite' => 5,
            'mostrar_fecha' => 'true'
        ], $atts, 'mi_shortcode');
        
        // Obtener posts
        $posts = get_posts([
            'post_type' => 'mi_contenido',
            'numberposts' => intval($atts['limite']),
            'post_status' => 'publish'
        ]);
        
        if (empty($posts)) {
            return '<p>No hay contenido disponible.</p>';
        }
        
        // Generar HTML
        $output = '<div class="mi-plugin-contenido">';
        
        foreach ($posts as $post) {
            $output .= '<div class="mi-plugin-item">';
            $output .= '<h3>' . esc_html($post->post_title) . '</h3>';
            $output .= '<div class="contenido">' . wp_kses_post($post->post_content) . '</div>';
            
            if ($atts['mostrar_fecha'] === 'true') {
                $output .= '<small class="fecha">Publicado: ' . esc_html(get_the_date('Y-m-d', $post->ID)) . '</small>';
            }
            
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        // Agregar CSS básico
        $output .= '<style>
            .mi-plugin-contenido { margin: 20px 0; }
            .mi-plugin-item { 
                border: 1px solid #ddd; 
                padding: 15px; 
                margin-bottom: 15px; 
                border-radius: 5px; 
            }
            .mi-plugin-item h3 { margin-top: 0; }
            .mi-plugin-item .fecha { color: #666; font-style: italic; }
        </style>';
        
        return $output;
    }
}
```

## 🌐 **Paso 5: Crear un endpoint de API**

### 5.1 Implementar la API

Edita `src/Api.php`:

```php
<?php
namespace MiPrimerPlugin;

class Api
{
    public static function obtener_datos($request)
    {
        // Obtener parámetros
        $limite = $request->get_param('limite') ?: 10;
        
        // Obtener posts
        $posts = get_posts([
            'post_type' => 'mi_contenido',
            'numberposts' => intval($limite),
            'post_status' => 'publish'
        ]);
        
        // Formatear respuesta
        $datos = [];
        foreach ($posts as $post) {
            $datos[] = [
                'id' => $post->ID,
                'titulo' => $post->post_title,
                'contenido' => $post->post_content,
                'fecha' => get_the_date('c', $post->ID),
                'url' => get_permalink($post->ID)
            ];
        }
        
        return new \WP_REST_Response([
            'success' => true,
            'data' => $datos,
            'total' => count($datos)
        ], 200);
    }
    
    public static function verificar_permisos()
    {
        // Permitir acceso público para este ejemplo
        return true;
        
        // Para endpoints privados, usar:
        // return current_user_can('read');
    }
}
```

## 🧪 **Paso 6: Probar tu plugin**

### 6.1 Activar el plugin

1. Ve a **Plugins > Plugins instalados** en WordPress
2. Activa "Mi Primer Plugin con Antonella"
3. Verifica que aparezca "Mi Plugin" en el menú de administración

### 6.2 Probar la funcionalidad

1. **Página de administración**:
   - Ve a **Mi Plugin** en el menú
   - Llena el formulario y guarda contenido
   - Verifica que aparezca en la tabla

2. **Custom Post Type**:
   - Ve a **Mi Contenido** en el menú
   - Crea nuevos posts desde aquí también

3. **Shortcode**:
   - Crea una página o post
   - Agrega el shortcode: `[mi_shortcode limite="3"]`
   - Verifica que muestre el contenido

4. **API REST**:
   - Visita: `http://tu-sitio.com/wp-json/wp/v2/mi-plugin/datos`
   - Verifica que devuelva JSON con tus datos

## 🎉 **¡Felicidades!**

Has creado exitosamente tu primer plugin con Antonella Framework. Tu plugin incluye:

- ✅ Página de administración funcional
- ✅ Formularios con seguridad integrada
- ✅ Custom Post Type
- ✅ Shortcode para frontend
- ✅ API REST endpoint
- ✅ Almacenamiento de datos

## 📚 **Próximos pasos**

Ahora que dominas lo básico, puedes:

1. **[Explorar la arquitectura](../architecture/mvc-pattern.md)** del framework
2. **[Aprender sobre seguridad](../guides/security-best-practices.md)** avanzada
3. **[Crear controladores](../guides/creating-controllers.md)** más complejos
4. **[Trabajar con la API](../guides/api-development.md)** REST

---

> 💡 **Tip**: Guarda este código como plantilla para futuros proyectos. Puedes usar este ejemplo como base y expandirlo según tus necesidades específicas.
