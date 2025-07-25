# Arquitectura Vista-Controlador en Antonella Framework

> **📌 Versión:** Antonella Framework v1.9

El **patrón Vista-Controlador (VC)** es la arquitectura fundamental que utiliza Antonella Framework v1.9 para organizar el código de manera limpia, mantenible y escalable.

## 🎯 **¿Qué es el patrón Vista-Controlador?**

El patrón VC es un diseño arquitectónico que separa la aplicación en dos componentes principales:

- **👁️ Vista (View)**: Se encarga de la presentación y la interfaz de usuario
- **🎮 Controlador (Controller)**: Maneja la lógica de aplicación, datos y coordina con las vistas

> **💡 Nota:** Antonella Framework v1.9 no implementa una capa de Modelo separada. Los controladores manejan directamente la lógica de datos usando las APIs nativas de WordPress (WP_Query, get_posts, etc.) y funciones personalizadas.

## 🏗️ **Implementación en Antonella Framework**

### **📁 Estructura de directorios**

```
mi-plugin/
├── src/
│   ├── Controllers/         # 🎮 Controladores
│   │   └── ExampleController.php
│   ├── Admin/               # 🛠️ Funciones del wp-admin
│   │   ├── Admin.php
│   │   ├── Dashboard.php
│   │   └── PageAdmin.php
│   ├── Helpers/             # 🔧 Utilidades y helpers
│   │   └── blade.php
│   ├── Api.php              # 🌐 API REST
│   ├── Config.php           # ⚙️ Configuración central
│   ├── Desactivate.php      # 🚫 Desactivación del plugin
│   ├── Gutenberg.php        # 🧩 Bloques de Gutenberg
│   ├── Hooks.php            # 🪝 Hooks y filtros
│   ├── Init.php             # 🚀 Inicialización
│   ├── Install.php          # 📦 Instalación del plugin
│   ├── Language.php         # 🌍 Internacionalización
│   ├── PostTypes.php        # 📝 Custom Post Types y Taxonomías
│   ├── Request.php          # 🔄 Manejo de peticiones
│   ├── Security.php         # 🔒 Seguridad
│   ├── Shortcodes.php       # 🎨 Shortcodes
│   ├── Start.php            # ▶️ Inicio del framework
│   ├── Uninstall.php        # 🗑️ Desinstalación del plugin
│   ├── Users.php            # 👥 Gestión de usuarios
│   ├── Widgets.php          # 🧩 Widgets
│   └── helpers.php          # 🔧 Funciones auxiliares
├── resources/               # 👁️ Vistas y plantillas
│   ├── views/
│   │   ├── admin/
│   │   └── frontend/
│   └── templates/
├── Assets/                  # 🖼️ Imágenes y archivos estáticos
│   ├── css/
│   ├── js/
│   └── images/
├── languages/               # 🌍 Archivos de idioma
│   ├── antonella-es_ES.po
│   └── antonella-en_US.po
├── vendor/                  # 📦 Dependencias de Composer
├── test/                    # 🧪 Testing del framework
│   ├── unit/
│   └── integration/
├── antonella-docs/          # 📚 Documentación
├── antonella-framework.php  # 🚀 Archivo principal del plugin
└── composer.json            # 📋 Configuración de Composer
```

## 🎮 **Controladores (Controllers)**

Los controladores manejan las peticiones del usuario y coordinan la respuesta.

### **Características principales:**

- **Procesamiento de formularios** con validación
- **Gestión de permisos** y seguridad
- **Coordinación** con vistas y datos
- **Manejo de errores** y excepciones

### **Ejemplo práctico:**

```php
<?php
namespace TuNamespace\Controllers;

use TuNamespace\Security;

class PostController
{
    /**
     * Mostrar lista de posts
     */
    public static function index()
    {
        // Verificar permisos
        Security::check_user_capability('read');
        
        // Obtener datos usando WordPress API
        $posts = get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => -1
        ]);
        
        // Pasar datos a la vista
        include_once __DIR__ . '/../resources/views/posts/index.php';
    }
    
    /**
     * Crear nuevo post
     */
    public static function create()
    {
        Security::check_user_capability('edit_posts');
        
        if ($_POST) {
            // Verificar nonce
            Security::verify_nonce('post_nonce', 'create_post');
            
            // Sanitizar datos
            $title = Security::sanitize_input($_POST['title'], 'text');
            $content = Security::sanitize_input($_POST['content'], 'textarea');
            
            // Crear usando WordPress API
            $result = wp_insert_post([
                'post_title' => $title,
                'post_content' => $content,
                'post_status' => 'publish',
                'post_type' => 'post'
            ]);
            
            if ($result) {
                wp_redirect(admin_url('admin.php?page=posts&success=1'));
                exit;
            } else {
                $error = 'Error al crear el post';
            }
        }
        
        // Mostrar formulario
        include_once __DIR__ . '/../Views/posts/create.php';
    }
    
    /**
     * API endpoint
     */
    public static function api_get_posts($request)
    {
        // Validar permisos
        if (!current_user_can('read')) {
            return new \WP_Error('forbidden', 'Sin permisos', ['status' => 403]);
        }
        
        // Obtener parámetros
        $limit = $request->get_param('limit') ?: 10;
        
        // Obtener datos
        $posts = Post::getAll($limit);
        
        // Retornar respuesta JSON
        return new \WP_REST_Response([
            'success' => true,
            'data' => $posts,
            'total' => count($posts)
        ], 200);
    }
}
```

## 👁️ **Vistas (Views)**

Las vistas se encargan de la presentación y la interfaz de usuario.

### **Principios de las vistas:**

- **Separación de lógica** y presentación
- **Escape de datos** para seguridad
- **Reutilización** de componentes
- **Responsive design**

### **Ejemplo de vista de administración:**

```php
<!-- Views/posts/index.php -->
<div class="wrap">
    <h1><?php echo esc_html(__('Gestión de Posts', 'tu-textdomain')); ?></h1>
    
    <a href="<?php echo admin_url('admin.php?page=posts&action=create'); ?>" class="button button-primary">
        <?php echo esc_html(__('Crear Nuevo Post', 'tu-textdomain')); ?>
    </a>
    
    <?php if (!empty($posts)): ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php echo esc_html(__('Título', 'tu-textdomain')); ?></th>
                    <th><?php echo esc_html(__('Autor', 'tu-textdomain')); ?></th>
                    <th><?php echo esc_html(__('Fecha', 'tu-textdomain')); ?></th>
                    <th><?php echo esc_html(__('Acciones', 'tu-textdomain')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?php echo esc_html($post->getTitle()); ?></td>
                        <td><?php echo esc_html(get_userdata($post->getAuthorId())->display_name); ?></td>
                        <td><?php echo esc_html($post->getCreatedAt()); ?></td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=posts&action=edit&id=' . $post->getId()); ?>">
                                <?php echo esc_html(__('Editar', 'tu-textdomain')); ?>
                            </a>
                            |
                            <a href="<?php echo admin_url('admin.php?page=posts&action=delete&id=' . $post->getId()); ?>" 
                               onclick="return confirm('¿Estás seguro?')">
                                <?php echo esc_html(__('Eliminar', 'tu-textdomain')); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><?php echo esc_html(__('No hay posts disponibles.', 'tu-textdomain')); ?></p>
    <?php endif; ?>
</div>
```

### **Ejemplo de vista de frontend:**

```php
<!-- Views/posts/frontend.php -->
<div class="antonella-posts-container">
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <article class="antonella-post">
                <header class="post-header">
                    <h2 class="post-title"><?php echo esc_html($post->getTitle()); ?></h2>
                    <div class="post-meta">
                        <span class="author">
                            <?php echo esc_html(__('Por', 'tu-textdomain')); ?> 
                            <?php echo esc_html(get_userdata($post->getAuthorId())->display_name); ?>
                        </span>
                        <span class="date">
                            <?php echo esc_html(date('d/m/Y', strtotime($post->getCreatedAt()))); ?>
                        </span>
                    </div>
                </header>
                
                <div class="post-content">
                    <?php echo wp_kses_post($post->getContent()); ?>
                </div>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-posts"><?php echo esc_html(__('No hay contenido disponible.', 'tu-textdomain')); ?></p>
    <?php endif; ?>
</div>

<style>
.antonella-posts-container {
    max-width: 800px;
    margin: 0 auto;
}

.antonella-post {
    margin-bottom: 2rem;
    padding: 1.5rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fff;
}

.post-title {
    margin: 0 0 1rem 0;
    color: #333;
}

.post-meta {
    margin-bottom: 1rem;
    color: #666;
    font-size: 0.9rem;
}

.post-meta span {
    margin-right: 1rem;
}

.post-content {
    line-height: 1.6;
}

.no-posts {
    text-align: center;
    color: #666;
    font-style: italic;
}
</style>
```

## 🔄 **Flujo de datos en MVC**

### **1. Petición del usuario**
```
Usuario → WordPress → Antonella Framework → Controlador
```

### **2. Procesamiento**
```
Controlador → Modelo (datos) → Controlador (lógica)
```

### **3. Respuesta**
```
Controlador → Vista (presentación) → Usuario
```

### **Ejemplo de flujo completo:**

```php
// 1. Usuario accede a: /wp-admin/admin.php?page=mi-plugin&action=create

// 2. WordPress llama al hook registrado
add_action('admin_menu', function() {
    add_menu_page(
        'Mi Plugin',
        'Mi Plugin', 
        'manage_options',
        'mi-plugin',
        'MiNamespace\Controllers\PostController::router'
    );
});

// 3. El router del controlador determina la acción
public static function router()
{
    $action = $_GET['action'] ?? 'index';
    
    switch ($action) {
        case 'create':
            self::create();
            break;
        case 'edit':
            self::edit();
            break;
        default:
            self::index();
    }
}

// 4. El controlador procesa la petición
public static function create()
{
    // Verificar permisos
    Security::check_user_capability('edit_posts');
    
    if ($_POST) {
        // Procesar formulario
        $model = new Post();
        $result = $model->create($_POST['title'], $_POST['content']);
        
        if ($result) {
            // Redirigir con éxito
            wp_redirect(admin_url('admin.php?page=mi-plugin&success=1'));
            exit;
        }
    }
    
    // Mostrar vista
    include_once __DIR__ . '/../Views/posts/create.php';
}
```

## ✅ **Beneficios del patrón MVC**

### **🧹 Código más limpio**
- Separación clara de responsabilidades
- Fácil de leer y mantener
- Menos acoplamiento entre componentes

### **🔧 Mantenibilidad**
- Cambios en la UI no afectan la lógica
- Modificaciones en datos no impactan la presentación
- Testing más sencillo y efectivo

### **📈 Escalabilidad**
- Fácil agregar nuevas funcionalidades
- Reutilización de componentes
- Arquitectura que crece con el proyecto

### **👥 Trabajo en equipo**
- Desarrolladores pueden trabajar en paralelo
- Especialización por capas
- Estándares claros de desarrollo

## 🎯 **Mejores prácticas**

### **Para Controladores:**
- Mantén los controladores delgados
- Una acción por método
- Siempre valida permisos
- Usa dependency injection cuando sea posible

### **Para Modelos:**
- Encapsula la lógica de negocio
- Valida datos antes de guardar
- Usa métodos estáticos para consultas simples
- Implementa relaciones entre modelos

### **Para Vistas:**
- Escapa siempre los datos de salida
- Separa lógica de presentación
- Usa templates reutilizables
- Implementa responsive design

---

> 💡 **Tip**: El patrón MVC en Antonella Framework está optimizado para WordPress, aprovechando sus APIs nativas mientras mantiene una arquitectura limpia y profesional.
