# Ejemplo: Integración con API Externa

Este ejemplo muestra cómo integrar APIs externas en tu plugin usando Antonella Framework, incluyendo manejo de errores, caché y autenticación.

## 🎯 **Lo que construiremos**

Un plugin que consume la API de JSONPlaceholder para mostrar posts externos:
- ✅ Consumir API REST externa
- ✅ Caché de respuestas
- ✅ Manejo de errores robusto
- ✅ Interfaz de administración
- ✅ Shortcode para frontend

## 🏗️ **1. Configuración**

```php
<?php
// src/Config.php
namespace ApiPlugin;

class Config
{
    public $plugin_menu = [
        [
            'page_title' => 'API Externa',
            'menu_title' => 'API Posts',
            'capability' => 'manage_options',
            'menu_slug' => 'api-posts',
            'function' => 'ApiPlugin\Controllers\ApiController::dashboard',
            'icon_url' => 'dashicons-cloud'
        ]
    ];

    public $shortcodes = [
        'external_posts' => 'ApiPlugin\Controllers\ApiController::shortcode_posts'
    ];

    public $actions = [
        ['wp_ajax_refresh_api_cache', 'ApiPlugin\Controllers\ApiController::refresh_cache'],
        ['wp_ajax_test_api_connection', 'ApiPlugin\Controllers\ApiController::test_connection']
    ];

    // Configuración de la API
    public $api_config = [
        'base_url' => 'https://jsonplaceholder.typicode.com',
        'timeout' => 30,
        'cache_duration' => 3600, // 1 hora
        'max_posts' => 50
    ];
}
```

## 🌐 **2. Servicio de API**

```php
<?php
// src/Services/ApiService.php
namespace ApiPlugin\Services;

class ApiService
{
    private $base_url;
    private $timeout;
    private $cache_duration;

    public function __construct($config = [])
    {
        $this->base_url = $config['base_url'] ?? 'https://jsonplaceholder.typicode.com';
        $this->timeout = $config['timeout'] ?? 30;
        $this->cache_duration = $config['cache_duration'] ?? 3600;
    }

    /**
     * Obtener posts de la API externa
     */
    public function getPosts($limit = 10, $force_refresh = false)
    {
        $cache_key = 'api_posts_' . $limit;
        
        // Verificar caché
        if (!$force_refresh) {
            $cached_data = get_transient($cache_key);
            if ($cached_data !== false) {
                return [
                    'success' => true,
                    'data' => $cached_data,
                    'from_cache' => true
                ];
            }
        }

        // Hacer petición a la API
        $response = $this->makeRequest('/posts', [
            'timeout' => $this->timeout,
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => 'WordPress-Plugin/1.0'
            ]
        ]);

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'error' => $response->get_error_message(),
                'error_code' => $response->get_error_code()
            ];
        }

        $body = wp_remote_retrieve_body($response);
        $status_code = wp_remote_retrieve_response_code($response);

        if ($status_code !== 200) {
            return [
                'success' => false,
                'error' => "Error HTTP: {$status_code}",
                'error_code' => 'http_error'
            ];
        }

        $posts = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Error al decodificar JSON: ' . json_last_error_msg(),
                'error_code' => 'json_error'
            ];
        }

        // Limitar resultados
        $posts = array_slice($posts, 0, $limit);

        // Procesar y limpiar datos
        $processed_posts = array_map([$this, 'processPost'], $posts);

        // Guardar en caché
        set_transient($cache_key, $processed_posts, $this->cache_duration);

        return [
            'success' => true,
            'data' => $processed_posts,
            'from_cache' => false
        ];
    }

    /**
     * Obtener un post específico
     */
    public function getPost($id)
    {
        $cache_key = 'api_post_' . $id;
        
        $cached_data = get_transient($cache_key);
        if ($cached_data !== false) {
            return [
                'success' => true,
                'data' => $cached_data,
                'from_cache' => true
            ];
        }

        $response = $this->makeRequest("/posts/{$id}");

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'error' => $response->get_error_message()
            ];
        }

        $body = wp_remote_retrieve_body($response);
        $status_code = wp_remote_retrieve_response_code($response);

        if ($status_code === 404) {
            return [
                'success' => false,
                'error' => 'Post no encontrado',
                'error_code' => 'not_found'
            ];
        }

        if ($status_code !== 200) {
            return [
                'success' => false,
                'error' => "Error HTTP: {$status_code}"
            ];
        }

        $post = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Error al decodificar JSON'
            ];
        }

        $processed_post = $this->processPost($post);
        set_transient($cache_key, $processed_post, $this->cache_duration);

        return [
            'success' => true,
            'data' => $processed_post,
            'from_cache' => false
        ];
    }

    /**
     * Obtener comentarios de un post
     */
    public function getPostComments($post_id)
    {
        $cache_key = 'api_comments_' . $post_id;
        
        $cached_data = get_transient($cache_key);
        if ($cached_data !== false) {
            return [
                'success' => true,
                'data' => $cached_data,
                'from_cache' => true
            ];
        }

        $response = $this->makeRequest("/posts/{$post_id}/comments");

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'error' => $response->get_error_message()
            ];
        }

        $body = wp_remote_retrieve_body($response);
        $comments = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Error al decodificar JSON'
            ];
        }

        $processed_comments = array_map([$this, 'processComment'], $comments);
        set_transient($cache_key, $processed_comments, $this->cache_duration);

        return [
            'success' => true,
            'data' => $processed_comments,
            'from_cache' => false
        ];
    }

    /**
     * Limpiar todo el caché
     */
    public function clearCache()
    {
        global $wpdb;
        
        $wpdb->query(
            "DELETE FROM {$wpdb->options} 
             WHERE option_name LIKE '_transient_api_posts_%' 
             OR option_name LIKE '_transient_api_post_%' 
             OR option_name LIKE '_transient_api_comments_%'"
        );

        return true;
    }

    /**
     * Probar conexión con la API
     */
    public function testConnection()
    {
        $start_time = microtime(true);
        
        $response = $this->makeRequest('/posts/1', [
            'timeout' => 10
        ]);

        $end_time = microtime(true);
        $response_time = round(($end_time - $start_time) * 1000, 2);

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'error' => $response->get_error_message(),
                'response_time' => $response_time
            ];
        }

        $status_code = wp_remote_retrieve_response_code($response);

        return [
            'success' => $status_code === 200,
            'status_code' => $status_code,
            'response_time' => $response_time,
            'api_url' => $this->base_url
        ];
    }

    /**
     * Hacer petición HTTP
     */
    private function makeRequest($endpoint, $args = [])
    {
        $url = rtrim($this->base_url, '/') . '/' . ltrim($endpoint, '/');
        
        $default_args = [
            'timeout' => $this->timeout,
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => 'WordPress-Plugin/1.0'
            ]
        ];

        $args = wp_parse_args($args, $default_args);

        return wp_remote_get($url, $args);
    }

    /**
     * Procesar y limpiar datos del post
     */
    private function processPost($post)
    {
        return [
            'id' => intval($post['id']),
            'title' => sanitize_text_field($post['title']),
            'body' => sanitize_textarea_field($post['body']),
            'user_id' => intval($post['userId']),
            'excerpt' => wp_trim_words(sanitize_textarea_field($post['body']), 20),
            'processed_at' => current_time('mysql')
        ];
    }

    /**
     * Procesar comentarios
     */
    private function processComment($comment)
    {
        return [
            'id' => intval($comment['id']),
            'post_id' => intval($comment['postId']),
            'name' => sanitize_text_field($comment['name']),
            'email' => sanitize_email($comment['email']),
            'body' => sanitize_textarea_field($comment['body'])
        ];
    }
}
```

## 🎮 **3. Controlador**

```php
<?php
// src/Controllers/ApiController.php
namespace ApiPlugin\Controllers;

use ApiPlugin\Services\ApiService;
use ApiPlugin\Security;

class ApiController
{
    private static $api_service;

    private static function getApiService()
    {
        if (!self::$api_service) {
            $config = [
                'base_url' => 'https://jsonplaceholder.typicode.com',
                'timeout' => 30,
                'cache_duration' => 3600
            ];
            self::$api_service = new ApiService($config);
        }
        return self::$api_service;
    }

    /**
     * Dashboard principal
     */
    public static function dashboard()
    {
        Security::check_user_capability('manage_options');

        $api_service = self::getApiService();
        
        // Obtener estadísticas del caché
        $cache_stats = self::getCacheStats();
        
        // Probar conexión si se solicita
        $connection_test = null;
        if (isset($_GET['test_connection'])) {
            $connection_test = $api_service->testConnection();
        }

        // Obtener posts de ejemplo
        $posts_result = $api_service->getPosts(5);

        self::render('dashboard', [
            'cache_stats' => $cache_stats,
            'connection_test' => $connection_test,
            'posts_result' => $posts_result
        ]);
    }

    /**
     * Refrescar caché (AJAX)
     */
    public static function refresh_cache()
    {
        Security::check_ajax_referer('refresh_api_cache');
        Security::check_user_capability('manage_options');

        $api_service = self::getApiService();
        
        // Limpiar caché
        $api_service->clearCache();
        
        // Obtener datos frescos
        $result = $api_service->getPosts(10, true);

        if ($result['success']) {
            wp_send_json_success([
                'message' => 'Caché actualizado correctamente',
                'posts_count' => count($result['data'])
            ]);
        } else {
            wp_send_json_error([
                'message' => 'Error al actualizar caché: ' . $result['error']
            ]);
        }
    }

    /**
     * Probar conexión (AJAX)
     */
    public static function test_connection()
    {
        Security::check_ajax_referer('test_api_connection');
        Security::check_user_capability('manage_options');

        $api_service = self::getApiService();
        $result = $api_service->testConnection();

        if ($result['success']) {
            wp_send_json_success([
                'message' => 'Conexión exitosa',
                'response_time' => $result['response_time'] . 'ms',
                'status_code' => $result['status_code']
            ]);
        } else {
            wp_send_json_error([
                'message' => 'Error de conexión: ' . $result['error'],
                'response_time' => $result['response_time'] . 'ms'
            ]);
        }
    }

    /**
     * Shortcode para mostrar posts
     */
    public static function shortcode_posts($atts)
    {
        $atts = shortcode_atts([
            'limit' => 5,
            'show_excerpt' => 'true',
            'show_author' => 'false',
            'cache' => 'true'
        ], $atts, 'external_posts');

        $api_service = self::getApiService();
        $force_refresh = $atts['cache'] === 'false';
        
        $result = $api_service->getPosts(intval($atts['limit']), $force_refresh);

        ob_start();
        
        if (!$result['success']) {
            echo '<div class="api-error">';
            echo '<p><strong>Error:</strong> ' . esc_html($result['error']) . '</p>';
            echo '</div>';
            return ob_get_clean();
        }

        $posts = $result['data'];
        $from_cache = $result['from_cache'] ?? false;

        echo '<div class="external-posts-container">';
        
        if ($from_cache) {
            echo '<p class="cache-notice"><em>Datos desde caché</em></p>';
        }

        foreach ($posts as $post) {
            echo '<article class="external-post">';
            echo '<h3>' . esc_html($post['title']) . '</h3>';
            
            if ($atts['show_author'] === 'true') {
                echo '<p class="post-author">Por: Usuario ' . esc_html($post['user_id']) . '</p>';
            }
            
            if ($atts['show_excerpt'] === 'true') {
                echo '<p class="post-excerpt">' . esc_html($post['excerpt']) . '</p>';
            }
            
            echo '<div class="post-content">' . wp_kses_post(wpautop($post['body'])) . '</div>';
            echo '</article>';
        }
        
        echo '</div>';

        return ob_get_clean();
    }

    /**
     * Obtener estadísticas del caché
     */
    private static function getCacheStats()
    {
        global $wpdb;

        $cache_count = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->options} 
             WHERE option_name LIKE '_transient_api_%'"
        );

        return [
            'cached_items' => intval($cache_count),
            'cache_size' => self::getCacheSize()
        ];
    }

    /**
     * Obtener tamaño aproximado del caché
     */
    private static function getCacheSize()
    {
        global $wpdb;

        $size = $wpdb->get_var(
            "SELECT SUM(LENGTH(option_value)) FROM {$wpdb->options} 
             WHERE option_name LIKE '_transient_api_%'"
        );

        return self::formatBytes(intval($size));
    }

    /**
     * Formatear bytes
     */
    private static function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Renderizar vista
     */
    private static function render($view, $data = [])
    {
        extract($data);
        include __DIR__ . "/../Views/{$view}.php";
    }
}
```

## 👁️ **4. Vista del Dashboard**

```php
<?php
// src/Views/dashboard.php
?>
<div class="wrap">
    <h1>API Externa - Dashboard</h1>

    <div class="postbox-container" style="width: 100%;">
        <div class="meta-box-sortables">
            
            <!-- Estado de la conexión -->
            <div class="postbox">
                <h2 class="hndle">Estado de la API</h2>
                <div class="inside">
                    <?php if ($connection_test): ?>
                        <?php if ($connection_test['success']): ?>
                            <div class="notice notice-success inline">
                                <p>✅ Conexión exitosa - Tiempo de respuesta: <?php echo esc_html($connection_test['response_time']); ?>ms</p>
                            </div>
                        <?php else: ?>
                            <div class="notice notice-error inline">
                                <p>❌ Error de conexión: <?php echo esc_html($connection_test['error']); ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <p>
                        <a href="<?php echo add_query_arg('test_connection', '1'); ?>" class="button">
                            Probar Conexión
                        </a>
                        <button type="button" id="test-connection-ajax" class="button">
                            Probar Conexión (AJAX)
                        </button>
                    </p>
                </div>
            </div>

            <!-- Estadísticas del caché -->
            <div class="postbox">
                <h2 class="hndle">Caché</h2>
                <div class="inside">
                    <p><strong>Elementos en caché:</strong> <?php echo esc_html($cache_stats['cached_items']); ?></p>
                    <p><strong>Tamaño del caché:</strong> <?php echo esc_html($cache_stats['cache_size']); ?></p>
                    
                    <p>
                        <button type="button" id="refresh-cache" class="button button-primary">
                            Actualizar Caché
                        </button>
                        <button type="button" id="clear-cache" class="button">
                            Limpiar Caché
                        </button>
                    </p>
                </div>
            </div>

            <!-- Posts de ejemplo -->
            <div class="postbox">
                <h2 class="hndle">Posts de Ejemplo</h2>
                <div class="inside">
                    <?php if ($posts_result['success']): ?>
                        <?php if ($posts_result['from_cache']): ?>
                            <p><em>Datos desde caché</em></p>
                        <?php endif; ?>
                        
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Usuario</th>
                                    <th>Extracto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts_result['data'] as $post): ?>
                                    <tr>
                                        <td><?php echo esc_html($post['id']); ?></td>
                                        <td><strong><?php echo esc_html($post['title']); ?></strong></td>
                                        <td>Usuario <?php echo esc_html($post['user_id']); ?></td>
                                        <td><?php echo esc_html($post['excerpt']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="notice notice-error inline">
                            <p>Error al cargar posts: <?php echo esc_html($posts_result['error']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Shortcode de ejemplo -->
            <div class="postbox">
                <h2 class="hndle">Uso del Shortcode</h2>
                <div class="inside">
                    <p>Usa este shortcode para mostrar posts en el frontend:</p>
                    <code>[external_posts limit="5" show_excerpt="true" show_author="true"]</code>
                    
                    <h4>Parámetros disponibles:</h4>
                    <ul>
                        <li><code>limit</code> - Número de posts a mostrar (por defecto: 5)</li>
                        <li><code>show_excerpt</code> - Mostrar extracto (true/false)</li>
                        <li><code>show_author</code> - Mostrar autor (true/false)</li>
                        <li><code>cache</code> - Usar caché (true/false)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Probar conexión AJAX
    $('#test-connection-ajax').on('click', function() {
        var button = $(this);
        button.prop('disabled', true).text('Probando...');
        
        $.post(ajaxurl, {
            action: 'test_api_connection',
            _ajax_nonce: '<?php echo wp_create_nonce('test_api_connection'); ?>'
        }, function(response) {
            if (response.success) {
                alert('✅ ' + response.data.message + '\nTiempo: ' + response.data.response_time);
            } else {
                alert('❌ ' + response.data.message);
            }
        }).always(function() {
            button.prop('disabled', false).text('Probar Conexión (AJAX)');
        });
    });

    // Actualizar caché
    $('#refresh-cache').on('click', function() {
        var button = $(this);
        button.prop('disabled', true).text('Actualizando...');
        
        $.post(ajaxurl, {
            action: 'refresh_api_cache',
            _ajax_nonce: '<?php echo wp_create_nonce('refresh_api_cache'); ?>'
        }, function(response) {
            if (response.success) {
                alert('✅ ' + response.data.message);
                location.reload();
            } else {
                alert('❌ ' + response.data.message);
            }
        }).always(function() {
            button.prop('disabled', false).text('Actualizar Caché');
        });
    });
});
</script>
```

## 🎨 **5. Estilos CSS**

```css
/* src/Assets/css/api-plugin.css */
.external-posts-container {
    max-width: 800px;
    margin: 20px 0;
}

.external-post {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 20px;
}

.external-post h3 {
    margin-top: 0;
    color: #0073aa;
}

.post-author {
    font-size: 0.9em;
    color: #666;
    margin: 5px 0;
}

.post-excerpt {
    font-style: italic;
    color: #555;
    margin: 10px 0;
}

.post-content {
    line-height: 1.6;
}

.cache-notice {
    font-size: 0.9em;
    color: #666;
    text-align: right;
    margin-bottom: 15px;
}

.api-error {
    background: #ffebe8;
    border: 1px solid #c3232d;
    border-radius: 3px;
    padding: 15px;
    color: #c3232d;
}
```

## 🧪 **6. Uso del ejemplo**

### En el administrador:
1. Ve a **API Posts** en el menú
2. Prueba la conexión con la API
3. Actualiza o limpia el caché
4. Ve los posts de ejemplo

### En el frontend:
```php
// Shortcode básico
[external_posts]

// Con parámetros personalizados
[external_posts limit="10" show_excerpt="true" show_author="true"]

// Sin caché (datos frescos)
[external_posts cache="false"]
```

## 🎯 **Características destacadas**

- ✅ **Caché inteligente** con transients de WordPress
- ✅ **Manejo robusto de errores** HTTP y JSON
- ✅ **Interfaz de administración** completa
- ✅ **AJAX** para operaciones en tiempo real
- ✅ **Shortcode flexible** con múltiples parámetros
- ✅ **Validación y sanitización** de datos
- ✅ **Timeouts configurables** para evitar bloqueos
- ✅ **Estadísticas de caché** y monitoreo

---

> 💡 **Tip**: Este ejemplo es perfecto para integrar cualquier API REST. Solo cambia la URL base y adapta los métodos de procesamiento de datos.
