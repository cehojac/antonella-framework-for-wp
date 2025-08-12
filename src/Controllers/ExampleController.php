<?php

namespace CH\Controllers;

use CH\Security;

class ExampleController
{
    /**
     * Example form processing with security checks
     */
    public static function process_form()
    {
        // Check user capabilities
         Security::check_user_capability('manage_options');
         try {
                // Verificar nonce usando el controlador Security
                Security::verify_nonce('antonella_nonce', 'antonella_config_action');
                
                // Sanitizar datos usando el controlador Security
                $site_name = Security::sanitize_input($_POST['antonella_site_name'] ?? '', 'text');
                $enable_debug = isset($_POST['antonella_debug']) ? 1 : 0;
                $api_key = Security::sanitize_input($_POST['antonella_api_key'] ?? '', 'text');
                
                // Validaciones adicionales
                if (empty($site_name)) {
                    throw new \Exception('El nombre del sitio no puede estar vacío');
                }
                
                // Guardar opciones
                update_option('antonella_site_name', $site_name);
                update_option('antonella_debug_mode', $enable_debug);
                update_option('antonella_api_key', $api_key);
                
                return '<div class="notice notice-success"><p>Configuración guardada correctamente';
                
            } catch (\Exception $e) {
                log_error('Error: ' . Security::escape_output($e->getMessage()));
            }
    }

    /**
     * Example admin page with nonce
     */
    public static function adminPage()
    {
      
         $site_name = get_option('antonella_site_name', 'Mi Proyecto Increíble');
         $debug_mode = get_option('antonella_debug_mode', false);
         $api_key = get_option('antonella_api_key', '');
        
        ?>
        <div class="antonella-dashboard-widget">
            <div class="inside">
                <h3>🚀 Antonella configuration panel</h3>
                <h4>This is an example panel, it is not a real panel.</h4>
                <p>Location of this function: /Controllers/ExampleController.php  adminPage()</p>
                <?php if(isset($_POST['submit_antonella_config'])):?>
                <div class="notice notice-success"><p>✅ Settings stored correctly</p></div>
                <?php endif; ?>
                <form method="post" action="">
                    <?php Security::nonce_field('antonella_config_action', 'antonella_nonce'); ?>
                    
                    <table class="form-table" style="margin-top: 20px;">
                        <tr>
                            <th scope="row">
                                <label for="antonella_site_name">Site name</label>
                            </th>
                            <td>
                                <input type="text" 
                                       id="antonella_site_name" 
                                       name="antonella_site_name" 
                                       value="<?php echo esc_attr($site_name); ?>" 
                                       class="regular-text" 
                                       placeholder="Example: My incredible project" 
                                       required />
                                <p class="description">Personalized name for your project.</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Debug mode</th>
                            <td>
                                <fieldset>
                                    <label for="antonella_debug">
                                        <input type="checkbox" 
                                               id="antonella_debug" 
                                               name="antonella_debug" 
                                               value="1" 
                                               <?php checked(1, $debug_mode); ?> />
                                        Activate debug mode
                                    </label>
                                    <p class="description">Shows additional information from Debugging.</p>
                                </fieldset>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="antonella_api_key">API Key</label>
                            </th>
                            <td>
                                <input type="password" 
                                       id="antonella_api_key" 
                                       name="antonella_api_key" 
                                       value="<?php echo esc_attr($api_key); ?>" 
                                       class="regular-text" 
                                       placeholder="Secret API Key" />
                                <p class="description">Key for external integrations.</p>
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <input type="submit" 
                               name="submit_antonella_config" 
                               class="button-primary" 
                               value="💾 Save configuration" />
                    </p>
                </form>
                
                <hr style="margin: 20px 0;" />
                
                <div class="antonella-stats" style="display: flex; gap: 15px;">
                    <div class="stat-box" style="flex: 1; background: #f0f0f1; padding: 10px; border-radius: 4px;">
                        <strong>📊 Posted Posts</strong><br>
                        <span style="font-size: 18px; color: #135e96;"><?php echo esc_html(wp_count_posts()->publish); ?></span>
                    </div>
                    <div class="stat-box" style="flex: 1; background: #f0f0f1; padding: 10px; border-radius: 4px;">
                        <strong>👥 Users</strong><br>
                        <span style="font-size: 18px; color: #135e96;"><?php echo esc_html(count_users()['total_users']); ?></span>
                    </div>
                    <div class="stat-box" style="flex: 1; background: #f0f0f1; padding: 10px; border-radius: 4px;">
                        <strong>🔧 Plugins</strong><br>
                        <span style="font-size: 18px; color: #135e96;"><?php echo esc_html(count(get_option('active_plugins', []))); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .antonella-dashboard-widget .form-table th {
            width: 200px;
        }
        .antonella-dashboard-widget .regular-text {
            width: 300px;
        }
        .antonella-dashboard-widget .description {
            font-style: italic;
            color: #666;
        }
        </style>
        <?php
    }

    /**
     * Example API endpoint with security
     */
    public static function api_endpoint()
    {
        // Check if user is logged in
        if (!is_user_logged_in()) {
            return new \WP_Error('unauthorized', __('You must be logged in', 'antonella-framework'), ['status' => 401]);
        }

        // Check capabilities
        if (!Security::can_edit_posts()) {
            return new \WP_Error('forbidden', __('Insufficient permissions', 'antonella-framework'), ['status' => 403]);
        }

        // Sanitize input
        $data = Security::sanitize_input($_REQUEST['data'], 'text');

        // Process and return response
        return rest_ensure_response([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Example AJAX handler
     */
    public static function ajax_handler()
    {
        // Check nonce
        if (!wp_verify_nonce($_POST['nonce'], 'example_ajax_nonce')) {
            wp_die(esc_html(__('Security check failed', 'antonella-framework')));
        }

        // Check capabilities
        Security::check_user_capability('edit_posts');

        // Process AJAX request
        $response = [
            'success' => true,
            'message' => __('AJAX request processed successfully', 'antonella-framework')
        ];

        wp_send_json($response);
    }
}
