# Testing - Pruebas en Antonella Framework

Una guía completa para implementar pruebas unitarias, de integración y funcionales en plugins desarrollados con Antonella Framework.

## 🎯 **Tipos de pruebas**

- **Pruebas unitarias**: Testean componentes individuales
- **Pruebas de integración**: Verifican la interacción entre componentes
- **Pruebas funcionales**: Validan el comportamiento completo
- **Pruebas de API**: Testean endpoints REST
- **Pruebas de frontend**: Verifican shortcodes y UI

## 🏗️ **Configuración del entorno de testing**

### 1. **Instalar PHPUnit**

```bash
# Via Composer
composer require --dev phpunit/phpunit

# Instalar WordPress Test Suite
bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
```

### 2. **Estructura de directorios**

```
mi-plugin/
├── tests/
│   ├── bootstrap.php
│   ├── unit/
│   │   ├── ModelsTest.php
│   │   └── ControllersTest.php
│   ├── integration/
│   │   ├── ApiTest.php
│   │   └── DatabaseTest.php
│   └── functional/
│       ├── ShortcodesTest.php
│       └── AdminTest.php
├── phpunit.xml
└── composer.json
```

### 3. **Configuración PHPUnit (phpunit.xml)**

```xml
<?xml version="1.0"?>
<phpunit
    bootstrap="tests/bootstrap.php"
    backupGlobals="false"
    colors="true"
    convertErrorsToExceptions="true"
    convertNoticesToExceptions="true"
    convertWarningsToExceptions="true"
>
    <testsuites>
        <testsuite name="unit">
            <directory prefix="test-" suffix=".php">./tests/unit/</directory>
        </testsuite>
        <testsuite name="integration">
            <directory prefix="test-" suffix=".php">./tests/integration/</directory>
        </testsuite>
        <testsuite name="functional">
            <directory prefix="test-" suffix=".php">./tests/functional/</directory>
        </testsuite>
    </testsuites>
    <filter>
        <whitelist>
            <directory suffix=".php">./src/</directory>
        </whitelist>
    </filter>
</phpunit>
```

### 4. **Bootstrap de pruebas (tests/bootstrap.php)**

```php
<?php
/**
 * Bootstrap para pruebas PHPUnit
 */

// Directorio del plugin
$_tests_dir = getenv('WP_TESTS_DIR');
if (!$_tests_dir) {
    $_tests_dir = rtrim(sys_get_temp_dir(), '/\\') . '/wordpress-tests-lib';
}

if (!file_exists($_tests_dir . '/includes/functions.php')) {
    echo "No se pudo encontrar el directorio de pruebas de WordPress.\n";
    exit(1);
}

// Cargar funciones de prueba de WordPress
require_once $_tests_dir . '/includes/functions.php';

/**
 * Activar plugin manualmente para las pruebas
 */
function _manually_load_plugin() {
    require dirname(__DIR__) . '/mi-plugin.php';
}
tests_add_filter('muplugins_loaded', '_manually_load_plugin');

// Inicializar WordPress para pruebas
require $_tests_dir . '/includes/bootstrap.php';

// Cargar clases de prueba base
require_once __DIR__ . '/base/BaseTestCase.php';
require_once __DIR__ . '/base/ApiTestCase.php';
require_once __DIR__ . '/base/DatabaseTestCase.php';
```

## 🧪 **Clases base para pruebas**

### 1. **Clase base general (tests/base/BaseTestCase.php)**

```php
<?php
namespace MiPlugin\Tests\Base;

use WP_UnitTestCase;

class BaseTestCase extends WP_UnitTestCase
{
    protected $plugin_instance;

    public function setUp(): void
    {
        parent::setUp();
        
        // Inicializar plugin
        $this->plugin_instance = new \MiPlugin\Init();
        
        // Limpiar datos de prueba
        $this->clean_test_data();
    }

    public function tearDown(): void
    {
        // Limpiar después de cada prueba
        $this->clean_test_data();
        
        parent::tearDown();
    }

    /**
     * Crear usuario de prueba
     */
    protected function create_test_user($role = 'administrator')
    {
        return $this->factory->user->create([
            'role' => $role,
            'user_login' => 'testuser_' . uniqid(),
            'user_email' => 'test_' . uniqid() . '@example.com'
        ]);
    }

    /**
     * Crear post de prueba
     */
    protected function create_test_post($args = [])
    {
        $defaults = [
            'post_title' => 'Test Post ' . uniqid(),
            'post_content' => 'Test content',
            'post_status' => 'publish',
            'post_type' => 'post'
        ];

        return $this->factory->post->create(array_merge($defaults, $args));
    }

    /**
     * Limpiar datos de prueba
     */
    protected function clean_test_data()
    {
        // Limpiar transients
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
        
        // Limpiar opciones del plugin
        delete_option('mi_plugin_options');
        delete_option('mi_plugin_version');
    }

    /**
     * Simular petición AJAX
     */
    protected function make_ajax_request($action, $data = [], $user_id = null)
    {
        if ($user_id) {
            wp_set_current_user($user_id);
        }

        $_POST['action'] = $action;
        foreach ($data as $key => $value) {
            $_POST[$key] = $value;
        }

        try {
            $this->_handleAjax($action);
        } catch (WPAjaxDieContinueException $e) {
            // Esto es normal para AJAX exitoso
        }
    }

    /**
     * Verificar respuesta JSON
     */
    protected function assertJsonResponse($expected_data = null)
    {
        $response = json_decode($this->_last_response, true);
        
        $this->assertIsArray($response);
        
        if ($expected_data) {
            foreach ($expected_data as $key => $value) {
                $this->assertArrayHasKey($key, $response);
                $this->assertEquals($value, $response[$key]);
            }
        }
        
        return $response;
    }
}
```

### 2. **Clase base para API (tests/base/ApiTestCase.php)**

```php
<?php
namespace MiPlugin\Tests\Base;

class ApiTestCase extends BaseTestCase
{
    protected $server;
    protected $namespaced_route = '/mi-plugin/v1';

    public function setUp(): void
    {
        parent::setUp();
        
        global $wp_rest_server;
        $this->server = $wp_rest_server = new \WP_REST_Server;
        do_action('rest_api_init');
    }

    /**
     * Hacer petición GET a la API
     */
    protected function get_api($endpoint, $params = [])
    {
        $request = new \WP_REST_Request('GET', $this->namespaced_route . $endpoint);
        
        foreach ($params as $key => $value) {
            $request->set_param($key, $value);
        }

        return $this->server->dispatch($request);
    }

    /**
     * Hacer petición POST a la API
     */
    protected function post_api($endpoint, $data = [])
    {
        $request = new \WP_REST_Request('POST', $this->namespaced_route . $endpoint);
        $request->set_header('content-type', 'application/json');
        $request->set_body(json_encode($data));

        return $this->server->dispatch($request);
    }

    /**
     * Hacer petición PUT a la API
     */
    protected function put_api($endpoint, $data = [])
    {
        $request = new \WP_REST_Request('PUT', $this->namespaced_route . $endpoint);
        $request->set_header('content-type', 'application/json');
        $request->set_body(json_encode($data));

        return $this->server->dispatch($request);
    }

    /**
     * Hacer petición DELETE a la API
     */
    protected function delete_api($endpoint)
    {
        $request = new \WP_REST_Request('DELETE', $this->namespaced_route . $endpoint);
        return $this->server->dispatch($request);
    }

    /**
     * Verificar respuesta de API exitosa
     */
    protected function assertApiSuccess($response, $expected_status = 200)
    {
        $this->assertEquals($expected_status, $response->get_status());
        $this->assertFalse($response->is_error());
        
        return $response->get_data();
    }

    /**
     * Verificar error de API
     */
    protected function assertApiError($response, $expected_status = 400)
    {
        $this->assertEquals($expected_status, $response->get_status());
        $this->assertTrue($response->is_error());
        
        return $response->get_data();
    }
}
```

## 🧪 **Ejemplos de pruebas**

### 1. **Pruebas unitarias de modelos**

```php
<?php
// tests/unit/test-models.php
namespace MiPlugin\Tests\Unit;

use MiPlugin\Tests\Base\BaseTestCase;
use MiPlugin\Models\Task;

class ModelsTest extends BaseTestCase
{
    public function test_task_creation()
    {
        $task_data = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending'
        ];

        $task = new Task();
        $result = $task->create($task_data);

        $this->assertTrue($result);
        $this->assertNotEmpty($task->getId());
        $this->assertEquals('Test Task', $task->getTitle());
        $this->assertEquals('Test Description', $task->getDescription());
        $this->assertEquals('pending', $task->getStatus());
    }

    public function test_task_validation()
    {
        $invalid_data = [
            'title' => '', // Título vacío
            'description' => 'Test Description'
        ];

        $errors = Task::validate($invalid_data);

        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('title', $errors);
        $this->assertEquals('El título es obligatorio', $errors['title']);
    }

    public function test_task_update()
    {
        // Crear tarea
        $task = new Task();
        $task->create([
            'title' => 'Original Title',
            'description' => 'Original Description'
        ]);

        $task_id = $task->getId();

        // Actualizar
        $update_data = [
            'title' => 'Updated Title',
            'description' => 'Updated Description'
        ];

        $result = $task->update($update_data);

        $this->assertTrue($result);
        $this->assertEquals('Updated Title', $task->getTitle());
        $this->assertEquals('Updated Description', $task->getDescription());

        // Verificar en base de datos
        $task_from_db = new Task($task_id);
        $this->assertEquals('Updated Title', $task_from_db->getTitle());
    }

    public function test_task_deletion()
    {
        // Crear tarea
        $task = new Task();
        $task->create([
            'title' => 'Task to Delete',
            'description' => 'Will be deleted'
        ]);

        $task_id = $task->getId();
        $this->assertNotEmpty($task_id);

        // Eliminar
        $result = $task->delete();
        $this->assertTrue($result);

        // Verificar que no existe
        $deleted_task = new Task($task_id);
        $this->assertEmpty($deleted_task->getId());
    }

    public function test_get_all_tasks()
    {
        // Crear varias tareas
        for ($i = 1; $i <= 3; $i++) {
            $task = new Task();
            $task->create([
                'title' => "Task {$i}",
                'description' => "Description {$i}"
            ]);
        }

        $all_tasks = Task::getAll();

        $this->assertCount(3, $all_tasks);
        $this->assertInstanceOf(Task::class, $all_tasks[0]);
    }
}
```

### 2. **Pruebas de controladores**

```php
<?php
// tests/unit/test-controllers.php
namespace MiPlugin\Tests\Unit;

use MiPlugin\Tests\Base\BaseTestCase;
use MiPlugin\Controllers\TaskController;

class ControllersTest extends BaseTestCase
{
    public function test_index_requires_capability()
    {
        // Usuario sin permisos
        $user_id = $this->create_test_user('subscriber');
        wp_set_current_user($user_id);

        $this->expectException(\Exception::class);
        TaskController::index();
    }

    public function test_create_task_with_valid_data()
    {
        $admin_id = $this->create_test_user('administrator');
        wp_set_current_user($admin_id);

        $_POST = [
            'task_nonce' => wp_create_nonce('create_task'),
            'title' => 'New Task',
            'description' => 'Task Description'
        ];

        // Capturar salida
        ob_start();
        TaskController::create();
        $output = ob_get_clean();

        // Verificar que se creó la tarea
        $tasks = \MiPlugin\Models\Task::getAll();
        $this->assertCount(1, $tasks);
        $this->assertEquals('New Task', $tasks[0]->getTitle());
    }

    public function test_ajax_delete_task()
    {
        $admin_id = $this->create_test_user('administrator');
        
        // Crear tarea
        $task = new \MiPlugin\Models\Task();
        $task->create([
            'title' => 'Task to Delete',
            'description' => 'Will be deleted via AJAX'
        ]);

        $task_id = $task->getId();

        // Simular petición AJAX
        $this->make_ajax_request('delete_task', [
            'task_id' => $task_id,
            '_ajax_nonce' => wp_create_nonce('delete_task_' . $task_id)
        ], $admin_id);

        // Verificar respuesta
        $response = $this->assertJsonResponse([
            'success' => true
        ]);

        // Verificar que se eliminó
        $deleted_task = new \MiPlugin\Models\Task($task_id);
        $this->assertEmpty($deleted_task->getId());
    }
}
```

### 3. **Pruebas de API REST**

```php
<?php
// tests/integration/test-api.php
namespace MiPlugin\Tests\Integration;

use MiPlugin\Tests\Base\ApiTestCase;

class ApiTest extends ApiTestCase
{
    public function test_get_tasks_endpoint()
    {
        // Crear algunas tareas
        for ($i = 1; $i <= 3; $i++) {
            $task = new \MiPlugin\Models\Task();
            $task->create([
                'title' => "API Task {$i}",
                'description' => "Description {$i}"
            ]);
        }

        $response = $this->get_api('/tasks');
        $data = $this->assertApiSuccess($response);

        $this->assertArrayHasKey('tasks', $data);
        $this->assertCount(3, $data['tasks']);
        $this->assertEquals('API Task 1', $data['tasks'][0]['title']);
    }

    public function test_create_task_via_api()
    {
        $admin_id = $this->create_test_user('administrator');
        wp_set_current_user($admin_id);

        $task_data = [
            'title' => 'API Created Task',
            'description' => 'Created via REST API'
        ];

        $response = $this->post_api('/tasks', $task_data);
        $data = $this->assertApiSuccess($response, 201);

        $this->assertArrayHasKey('id', $data);
        $this->assertEquals('API Created Task', $data['title']);

        // Verificar en base de datos
        $task = new \MiPlugin\Models\Task($data['id']);
        $this->assertEquals('API Created Task', $task->getTitle());
    }

    public function test_api_authentication()
    {
        // Sin autenticación
        $response = $this->post_api('/tasks', [
            'title' => 'Unauthorized Task'
        ]);

        $this->assertApiError($response, 401);
    }

    public function test_api_validation()
    {
        $admin_id = $this->create_test_user('administrator');
        wp_set_current_user($admin_id);

        // Datos inválidos
        $response = $this->post_api('/tasks', [
            'title' => '', // Título vacío
            'description' => 'Valid description'
        ]);

        $error_data = $this->assertApiError($response, 400);
        $this->assertArrayHasKey('message', $error_data);
    }

    public function test_get_single_task()
    {
        // Crear tarea
        $task = new \MiPlugin\Models\Task();
        $task->create([
            'title' => 'Single Task',
            'description' => 'For single endpoint test'
        ]);

        $task_id = $task->getId();

        $response = $this->get_api("/tasks/{$task_id}");
        $data = $this->assertApiSuccess($response);

        $this->assertEquals($task_id, $data['id']);
        $this->assertEquals('Single Task', $data['title']);
    }

    public function test_update_task_via_api()
    {
        $admin_id = $this->create_test_user('administrator');
        wp_set_current_user($admin_id);

        // Crear tarea
        $task = new \MiPlugin\Models\Task();
        $task->create([
            'title' => 'Original Title',
            'description' => 'Original Description'
        ]);

        $task_id = $task->getId();

        // Actualizar via API
        $update_data = [
            'title' => 'Updated via API',
            'description' => 'Updated description'
        ];

        $response = $this->put_api("/tasks/{$task_id}", $update_data);
        $data = $this->assertApiSuccess($response);

        $this->assertEquals('Updated via API', $data['title']);

        // Verificar en base de datos
        $updated_task = new \MiPlugin\Models\Task($task_id);
        $this->assertEquals('Updated via API', $updated_task->getTitle());
    }

    public function test_delete_task_via_api()
    {
        $admin_id = $this->create_test_user('administrator');
        wp_set_current_user($admin_id);

        // Crear tarea
        $task = new \MiPlugin\Models\Task();
        $task->create([
            'title' => 'Task to Delete',
            'description' => 'Will be deleted'
        ]);

        $task_id = $task->getId();

        // Eliminar via API
        $response = $this->delete_api("/tasks/{$task_id}");
        $this->assertApiSuccess($response, 204);

        // Verificar que se eliminó
        $deleted_task = new \MiPlugin\Models\Task($task_id);
        $this->assertEmpty($deleted_task->getId());
    }
}
```

### 4. **Pruebas funcionales de shortcodes**

```php
<?php
// tests/functional/test-shortcodes.php
namespace MiPlugin\Tests\Functional;

use MiPlugin\Tests\Base\BaseTestCase;

class ShortcodesTest extends BaseTestCase
{
    public function test_tasks_list_shortcode()
    {
        // Crear algunas tareas
        for ($i = 1; $i <= 3; $i++) {
            $task = new \MiPlugin\Models\Task();
            $task->create([
                'title' => "Shortcode Task {$i}",
                'description' => "Description {$i}",
                'status' => $i % 2 ? 'completed' : 'pending'
            ]);
        }

        // Ejecutar shortcode
        $output = do_shortcode('[tasks_list]');

        // Verificar salida
        $this->assertStringContainsString('Shortcode Task 1', $output);
        $this->assertStringContainsString('Shortcode Task 2', $output);
        $this->assertStringContainsString('Shortcode Task 3', $output);
        $this->assertStringContainsString('tasks-list-container', $output);
    }

    public function test_shortcode_with_attributes()
    {
        // Crear tareas con diferentes estados
        $completed_task = new \MiPlugin\Models\Task();
        $completed_task->create([
            'title' => 'Completed Task',
            'description' => 'This is completed',
            'status' => 'completed'
        ]);

        $pending_task = new \MiPlugin\Models\Task();
        $pending_task->create([
            'title' => 'Pending Task',
            'description' => 'This is pending',
            'status' => 'pending'
        ]);

        // Shortcode solo para completadas
        $output = do_shortcode('[tasks_list status="completed" limit="5"]');

        $this->assertStringContainsString('Completed Task', $output);
        $this->assertStringNotContainsString('Pending Task', $output);
    }

    public function test_single_task_shortcode()
    {
        // Crear tarea
        $task = new \MiPlugin\Models\Task();
        $task->create([
            'title' => 'Single Task Display',
            'description' => 'Detailed description for single task'
        ]);

        $task_id = $task->getId();

        // Ejecutar shortcode
        $output = do_shortcode("[task_detail id=\"{$task_id}\"]");

        $this->assertStringContainsString('Single Task Display', $output);
        $this->assertStringContainsString('Detailed description for single task', $output);
        $this->assertStringContainsString('task-detail-container', $output);
    }

    public function test_shortcode_with_invalid_id()
    {
        $output = do_shortcode('[task_detail id="999999"]');
        
        $this->assertStringContainsString('Tarea no encontrada', $output);
        $this->assertStringContainsString('error', $output);
    }
}
```

## 🚀 **Ejecutar las pruebas**

### Comandos básicos:

```bash
# Todas las pruebas
vendor/bin/phpunit

# Solo pruebas unitarias
vendor/bin/phpunit --testsuite=unit

# Solo pruebas de integración
vendor/bin/phpunit --testsuite=integration

# Con cobertura de código
vendor/bin/phpunit --coverage-html coverage/

# Prueba específica
vendor/bin/phpunit tests/unit/test-models.php

# Con detalles verbosos
vendor/bin/phpunit --verbose
```

### Scripts de Composer:

```json
{
    "scripts": {
        "test": "phpunit",
        "test:unit": "phpunit --testsuite=unit",
        "test:integration": "phpunit --testsuite=integration",
        "test:coverage": "phpunit --coverage-html coverage/"
    }
}
```

## 🎯 **Mejores prácticas**

### ✅ **Organización**
- Separa pruebas por tipo (unit, integration, functional)
- Usa nombres descriptivos para métodos de prueba
- Agrupa pruebas relacionadas en la misma clase

### ✅ **Datos de prueba**
- Usa factories para crear datos consistentes
- Limpia datos después de cada prueba
- No dependas de datos existentes en la base de datos

### ✅ **Aserciones**
- Usa aserciones específicas (`assertEquals` vs `assertTrue`)
- Verifica tanto casos exitosos como errores
- Incluye mensajes descriptivos en aserciones

### ✅ **Cobertura**
- Apunta a al menos 80% de cobertura de código
- Prioriza código crítico y complejo
- No sacrifiques calidad por cobertura

---

> 💡 **Tip**: Las pruebas automatizadas son esenciales para mantener la calidad del código. Ejecuta las pruebas antes de cada commit y en tu pipeline de CI/CD.
