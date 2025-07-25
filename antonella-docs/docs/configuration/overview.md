# Configuración - Visión General

Antonella Framework utiliza un sistema de configuración centralizado que permite definir todos los aspectos de tu plugin de manera organizada y eficiente.

## 🎯 **Archivo de configuración principal**

El archivo `src/Config.php` es el corazón de la configuración de tu plugin. Aquí defines:

- **Menús de administración** y submenús
- **Custom Post Types** y taxonomías
- **Shortcodes** y sus controladores
- **API endpoints** REST
- **Hooks y filtros** personalizados
- **Opciones** del plugin
- **Assets** (CSS/JS)

## 🏗️ **Estructura básica**

```php
<?php
namespace TuPlugin;

class Config
{
    // Menú principal de administración
    public $plugin_menu = [
        // Configuración del menú...
    ];

    // Submenús
    public $plugin_submenu = [
        // Configuración de submenús...
    ];

    // Custom Post Types
    public $post_types = [
        // Definición de post types...
    ];

    // Taxonomías
    public $taxonomies = [
        // Definición de taxonomías...
    ];

    // Shortcodes
    public $shortcodes = [
        // Mapeo de shortcodes...
    ];

    // API REST
    public $api_endpoints = [
        // Definición de endpoints...
    ];

    // Hooks y acciones
    public $actions = [
        // Hooks de WordPress...
    ];

    // Filtros
    public $filters = [
        // Filtros de WordPress...
    ];

    // Opciones del plugin
    public $plugin_options = [
        // Configuraciones por defecto...
    ];
}
```

## ⚙️ **Propiedades de configuración**

### 📋 **Menús de administración**
Define los menús que aparecerán en el panel de administración de WordPress.

```php
public $plugin_menu = [
    [
        'page_title' => 'Mi Plugin',
        'menu_title' => 'Mi Plugin',
        'capability' => 'manage_options',
        'menu_slug' => 'mi-plugin',
        'function' => 'MiPlugin\Controllers\AdminController::dashboard',
        'icon_url' => 'dashicons-admin-generic',
        'position' => 30
    ]
];
```

### 📝 **Custom Post Types**
Registra tipos de contenido personalizados para tu plugin.

```php
public $post_types = [
    'mi_contenido' => [
        'labels' => [
            'name' => 'Mi Contenido',
            'singular_name' => 'Contenido'
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail']
    ]
];
```

### 🏷️ **Taxonomías**
Define categorías y etiquetas personalizadas.

```php
public $taxonomies = [
    'mi_categoria' => [
        'post_types' => ['mi_contenido'],
        'labels' => [
            'name' => 'Mis Categorías',
            'singular_name' => 'Categoría'
        ],
        'hierarchical' => true
    ]
];
```

### 🔧 **Shortcodes**
Mapea shortcodes a métodos de controladores.

```php
public $shortcodes = [
    'mi_shortcode' => 'MiPlugin\Controllers\FrontendController::mi_shortcode',
    'otro_shortcode' => 'MiPlugin\Controllers\FrontendController::otro_shortcode'
];
```

### 🌐 **API REST**
Define endpoints de API REST personalizados.

```php
public $api_endpoints = [
    [
        'methods' => 'GET',
        'route' => '/mi-endpoint',
        'callback' => 'MiPlugin\Controllers\ApiController::get_data',
        'permission_callback' => 'MiPlugin\Controllers\ApiController::check_permissions'
    ]
];
```

### 🔗 **Hooks y filtros**
Registra acciones y filtros de WordPress.

```php
public $actions = [
    ['init', 'MiPlugin\Controllers\MainController::init'],
    ['wp_enqueue_scripts', 'MiPlugin\Controllers\AssetsController::enqueue_scripts']
];

public $filters = [
    ['the_content', 'MiPlugin\Controllers\ContentController::modify_content'],
    ['wp_title', 'MiPlugin\Controllers\SeoController::modify_title']
];
```

## 🎨 **Configuración avanzada**

### 📦 **Assets (CSS/JS)**
```php
public $assets = [
    'styles' => [
        'mi-plugin-style' => [
            'src' => 'assets/css/style.css',
            'deps' => [],
            'version' => '1.0.0'
        ]
    ],
    'scripts' => [
        'mi-plugin-script' => [
            'src' => 'assets/js/script.js',
            'deps' => ['jquery'],
            'version' => '1.0.0',
            'in_footer' => true
        ]
    ]
];
```

### 🔧 **Opciones del plugin**
```php
public $plugin_options = [
    'version' => '1.0.0',
    'db_version' => '1.0',
    'default_settings' => [
        'enable_feature_x' => true,
        'items_per_page' => 10,
        'cache_duration' => 3600
    ]
];
```

### 🗄️ **Configuración de base de datos**
```php
public $database_tables = [
    'mi_tabla' => [
        'columns' => [
            'id' => 'bigint(20) NOT NULL AUTO_INCREMENT',
            'name' => 'varchar(255) NOT NULL',
            'data' => 'longtext',
            'created_at' => 'datetime DEFAULT CURRENT_TIMESTAMP'
        ],
        'primary_key' => 'id',
        'indexes' => [
            'name_index' => 'name'
        ]
    ]
];
```

## 🔄 **Carga automática**

Antonella Framework carga automáticamente tu configuración y:

1. **Registra menús** en el panel de administración
2. **Crea post types** y taxonomías
3. **Mapea shortcodes** a controladores
4. **Registra endpoints** de API
5. **Conecta hooks** y filtros
6. **Carga assets** cuando es necesario
7. **Inicializa opciones** del plugin

## 📋 **Mejores prácticas**

### ✅ **Organización**
- Agrupa configuraciones relacionadas
- Usa nombres descriptivos y consistentes
- Comenta configuraciones complejas

### ✅ **Seguridad**
- Siempre define `capability` apropiados
- Valida y sanitiza datos de entrada
- Usa nonces para formularios

### ✅ **Performance**
- No registres recursos innecesarios
- Usa caché cuando sea apropiado
- Carga assets solo donde se necesiten

### ✅ **Mantenibilidad**
- Separa configuraciones por funcionalidad
- Usa constantes para valores repetidos
- Documenta configuraciones especiales

## 🔗 **Referencias**

- [Menús de administración](plugin-menu.md)
- [Custom Post Types](custom-post-types.md)
- [Taxonomías](taxonomies.md)
- [Hooks y filtros](hooks-filters.md)
- [API REST](api-endpoints.md)

---

> 💡 **Tip**: La configuración centralizada hace que tu plugin sea más fácil de mantener y modificar. Todos los aspectos importantes están en un solo lugar.
