# Ejemplo Simple: CRUD Básico

Este ejemplo muestra cómo implementar operaciones CRUD (Crear, Leer, Actualizar, Eliminar) básicas usando Antonella Framework.

## 🎯 **Lo que construiremos**

Un sistema simple de gestión de tareas con:
- ✅ Lista de tareas
- ✅ Crear nueva tarea
- ✅ Editar tarea existente
- ✅ Eliminar tarea
- ✅ Marcar como completada

## 🏗️ **1. Configuración básica**

```php
<?php
// src/Config.php
namespace MiPlugin;

class Config
{
    public $plugin_menu = [
        [
            'page_title' => 'Gestión de Tareas',
            'menu_title' => 'Tareas',
            'capability' => 'manage_options',
            'menu_slug' => 'tareas',
            'function' => 'MiPlugin\Controllers\TaskController::index',
            'icon_url' => 'dashicons-list-view'
        ]
    ];

    public $post_types = [
        'tarea' => [
            'labels' => [
                'name' => 'Tareas',
                'singular_name' => 'Tarea'
            ],
            'public' => false,
            'show_ui' => true,
            'supports' => ['title', 'editor']
        ]
    ];
}
```

## 🗃️ **2. Modelo de Tarea**

```php
<?php
// src/Models/Task.php
namespace MiPlugin\Models;

class Task
{
    private $id;
    private $title;
    private $description;
    private $completed;
    private $created_at;

    public function __construct($id = null)
    {
        if ($id) {
            $this->load($id);
        }
    }

    private function load($id)
    {
        $post = get_post($id);
        if ($post && $post->post_type === 'tarea') {
            $this->id = $post->ID;
            $this->title = $post->post_title;
            $this->description = $post->post_content;
            $this->completed = get_post_meta($id, '_tarea_completada', true);
            $this->created_at = $post->post_date;
        }
    }

    public static function getAll()
    {
        $posts = get_posts([
            'post_type' => 'tarea',
            'numberposts' => -1,
            'post_status' => 'publish'
        ]);

        return array_map(function($post) {
            return new self($post->ID);
        }, $posts);
    }

    public function create($data)
    {
        $post_data = [
            'post_title' => sanitize_text_field($data['title']),
            'post_content' => sanitize_textarea_field($data['description']),
            'post_type' => 'tarea',
            'post_status' => 'publish'
        ];

        $post_id = wp_insert_post($post_data);

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_tarea_completada', false);
            $this->load($post_id);
            return true;
        }

        return false;
    }

    public function update($data)
    {
        if (!$this->id) return false;

        $post_data = [
            'ID' => $this->id,
            'post_title' => sanitize_text_field($data['title']),
            'post_content' => sanitize_textarea_field($data['description'])
        ];

        $result = wp_update_post($post_data);

        if ($result && !is_wp_error($result)) {
            if (isset($data['completed'])) {
                update_post_meta($this->id, '_tarea_completada', (bool)$data['completed']);
            }
            $this->load($this->id);
            return true;
        }

        return false;
    }

    public function delete()
    {
        return $this->id ? wp_delete_post($this->id, true) : false;
    }

    public function toggleCompleted()
    {
        $new_status = !$this->completed;
        update_post_meta($this->id, '_tarea_completada', $new_status);
        $this->completed = $new_status;
        return true;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function isCompleted() { return (bool)$this->completed; }
    public function getCreatedAt() { return $this->created_at; }
}
```

## 🎮 **3. Controlador**

```php
<?php
// src/Controllers/TaskController.php
namespace MiPlugin\Controllers;

use MiPlugin\Models\Task;
use MiPlugin\Security;

class TaskController
{
    public static function index()
    {
        Security::check_user_capability('manage_options');

        // Procesar acciones
        if (isset($_GET['action'])) {
            self::handleAction();
        }

        // Obtener tareas
        $tasks = Task::getAll();

        self::render('tasks-list', ['tasks' => $tasks]);
    }

    public static function create()
    {
        Security::check_user_capability('manage_options');

        if ($_POST && isset($_POST['task_nonce'])) {
            Security::verify_nonce('task_nonce', 'create_task');

            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];

            $errors = self::validate($data);

            if (empty($errors)) {
                $task = new Task();
                if ($task->create($data)) {
                    wp_redirect(admin_url('admin.php?page=tareas&success=created'));
                    exit;
                }
                $error = 'Error al crear la tarea';
            }

            self::render('task-form', [
                'action' => 'create',
                'data' => $data,
                'errors' => $errors ?? [],
                'error' => $error ?? ''
            ]);
            return;
        }

        self::render('task-form', ['action' => 'create']);
    }

    public static function edit()
    {
        Security::check_user_capability('manage_options');

        $id = intval($_GET['id'] ?? 0);
        $task = new Task($id);

        if (!$task->getId()) {
            wp_die('Tarea no encontrada');
        }

        if ($_POST && isset($_POST['task_nonce'])) {
            Security::verify_nonce('task_nonce', 'edit_task');

            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'completed' => isset($_POST['completed'])
            ];

            $errors = self::validate($data);

            if (empty($errors)) {
                if ($task->update($data)) {
                    wp_redirect(admin_url('admin.php?page=tareas&success=updated'));
                    exit;
                }
                $error = 'Error al actualizar la tarea';
            }

            self::render('task-form', [
                'action' => 'edit',
                'task' => $task,
                'data' => $data,
                'errors' => $errors ?? [],
                'error' => $error ?? ''
            ]);
            return;
        }

        self::render('task-form', [
            'action' => 'edit',
            'task' => $task
        ]);
    }

    private static function handleAction()
    {
        $action = $_GET['action'];
        $id = intval($_GET['id'] ?? 0);

        switch ($action) {
            case 'delete':
                if (wp_verify_nonce($_GET['_wpnonce'], 'delete_task_' . $id)) {
                    $task = new Task($id);
                    if ($task->delete()) {
                        wp_redirect(admin_url('admin.php?page=tareas&success=deleted'));
                    } else {
                        wp_redirect(admin_url('admin.php?page=tareas&error=delete_failed'));
                    }
                    exit;
                }
                break;

            case 'toggle':
                if (wp_verify_nonce($_GET['_wpnonce'], 'toggle_task_' . $id)) {
                    $task = new Task($id);
                    if ($task->toggleCompleted()) {
                        wp_redirect(admin_url('admin.php?page=tareas&success=toggled'));
                    }
                    exit;
                }
                break;

            case 'create':
                self::create();
                break;

            case 'edit':
                self::edit();
                break;
        }
    }

    private static function validate($data)
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'El título es obligatorio';
        }

        if (strlen($data['title']) > 255) {
            $errors['title'] = 'El título es demasiado largo';
        }

        return $errors;
    }

    private static function render($view, $data = [])
    {
        extract($data);
        include __DIR__ . "/../Views/{$view}.php";
    }
}
```

## 👁️ **4. Vistas**

### Lista de tareas:
```php
<?php
// src/Views/tasks-list.php
?>
<div class="wrap">
    <h1>Gestión de Tareas</h1>
    
    <a href="<?php echo admin_url('admin.php?page=tareas&action=create'); ?>" class="button button-primary">
        Nueva Tarea
    </a>

    <?php if (isset($_GET['success'])): ?>
        <div class="notice notice-success">
            <p>¡Operación completada exitosamente!</p>
        </div>
    <?php endif; ?>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Título</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td>
                        <strong><?php echo esc_html($task->getTitle()); ?></strong>
                        <?php if ($task->getDescription()): ?>
                            <br><small><?php echo esc_html(wp_trim_words($task->getDescription(), 10)); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($task->isCompleted()): ?>
                            <span class="dashicons dashicons-yes-alt" style="color: green;"></span> Completada
                        <?php else: ?>
                            <span class="dashicons dashicons-clock" style="color: orange;"></span> Pendiente
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d/m/Y', strtotime($task->getCreatedAt())); ?></td>
                    <td>
                        <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=tareas&action=toggle&id=' . $task->getId()), 'toggle_task_' . $task->getId()); ?>">
                            <?php echo $task->isCompleted() ? 'Marcar Pendiente' : 'Marcar Completada'; ?>
                        </a>
                        |
                        <a href="<?php echo admin_url('admin.php?page=tareas&action=edit&id=' . $task->getId()); ?>">
                            Editar
                        </a>
                        |
                        <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=tareas&action=delete&id=' . $task->getId()), 'delete_task_' . $task->getId()); ?>" 
                           onclick="return confirm('¿Estás seguro?')">
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
```

### Formulario de tarea:
```php
<?php
// src/Views/task-form.php
$title = $action === 'edit' ? 'Editar Tarea' : 'Nueva Tarea';
$task_title = $data['title'] ?? ($task->getTitle() ?? '');
$task_description = $data['description'] ?? ($task->getDescription() ?? '');
$task_completed = $data['completed'] ?? ($task->isCompleted() ?? false);
?>
<div class="wrap">
    <h1><?php echo esc_html($title); ?></h1>

    <?php if (!empty($error)): ?>
        <div class="notice notice-error">
            <p><?php echo esc_html($error); ?></p>
        </div>
    <?php endif; ?>

    <form method="post">
        <?php wp_nonce_field($action . '_task', 'task_nonce'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="title">Título *</label>
                </th>
                <td>
                    <input type="text" id="title" name="title" 
                           value="<?php echo esc_attr($task_title); ?>" 
                           class="regular-text" required />
                    <?php if (!empty($errors['title'])): ?>
                        <p class="description" style="color: red;">
                            <?php echo esc_html($errors['title']); ?>
                        </p>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="description">Descripción</label>
                </th>
                <td>
                    <textarea id="description" name="description" 
                              rows="5" cols="50" class="large-text"><?php echo esc_textarea($task_description); ?></textarea>
                </td>
            </tr>
            <?php if ($action === 'edit'): ?>
                <tr>
                    <th scope="row">Estado</th>
                    <td>
                        <label>
                            <input type="checkbox" name="completed" value="1" 
                                   <?php checked($task_completed); ?> />
                            Tarea completada
                        </label>
                    </td>
                </tr>
            <?php endif; ?>
        </table>

        <?php submit_button($action === 'edit' ? 'Actualizar Tarea' : 'Crear Tarea'); ?>
    </form>

    <a href="<?php echo admin_url('admin.php?page=tareas'); ?>">← Volver a la lista</a>
</div>
```

## 🧪 **5. Uso del ejemplo**

### Activar el plugin:
1. Copia el código a tu plugin
2. Activa el plugin en WordPress
3. Ve a **Tareas** en el menú de administración

### Funcionalidades disponibles:
- **Crear**: Haz clic en "Nueva Tarea"
- **Listar**: Ve todas las tareas en la tabla
- **Editar**: Haz clic en "Editar" junto a cualquier tarea
- **Completar**: Haz clic en "Marcar Completada/Pendiente"
- **Eliminar**: Haz clic en "Eliminar" (con confirmación)

## 🎯 **Características del ejemplo**

- ✅ **CRUD completo** implementado
- ✅ **Validación** de datos
- ✅ **Seguridad** con nonces y permisos
- ✅ **Interfaz limpia** usando estilos de WordPress
- ✅ **Mensajes de éxito/error**
- ✅ **Confirmación** para acciones destructivas

---

> 💡 **Tip**: Este ejemplo es perfecto como base para sistemas más complejos. Puedes expandirlo agregando categorías, fechas de vencimiento, asignación de usuarios, etc.
