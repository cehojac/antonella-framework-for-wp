<?php
/**
* No modify this file !!!
*/

namespace CH;

class Hooks
{
    public function __construct()
    {
        $config = new Config();
        $registrer = $this->registrer();
        $filter = $this->filter($config->add_filter);
        $action = $this->action($config->add_action);
    }

    /*
    * Registrer call
    * @info: https://codex.wordpress.org/Function_Reference/register_deactivation_hook
    * @info: https://codex.wordpress.org/Function_Reference/register_activation_hook
    * @info: https://developer.wordpress.org/reference/functions/register_uninstall_hook/
    */
    public function registrer()
    {
        register_activation_hook(NELLA_URL, [__NAMESPACE__.'\Install', 'index']);
        register_deactivation_hook(NELLA_URL, [__NAMESPACE__.'\Desactivate', 'index']);
        register_uninstall_hook(NELLA_URL, __NAMESPACE__.'\Unistall::index');
    }

    /*
    * filter call
    * @info: https://codex.wordpress.org/Plugin_API/Filter_Reference
    */
    public function filter($filter = [])
    {
        if (isset($filter) && count($filter) > 0) {
            foreach ($filter as $data) {
                call_user_func_array('add_filter', [
                    isset($data[0]) ? $data[0] : null,
                    isset($data[1]) ? $data[1] : null,
                    isset($data[2]) ? $data[2] : null,
                    isset($data[3]) ? $data[3] : null,
                ]);
            }
        }
    }

    /*
    * Action Call
    * this is a indexed guide for search the functions
    * @info: https://codex.wordpress.org/Plugin_API/Action_Reference
    */
    public function action($action)
    {
        //ADMIN SECTION
        \add_action('admin_menu', [__NAMESPACE__.'\Admin\Admin', 'menu']);
        \add_action('admin_init', [__NAMESPACE__.'\Admin\PageAdmin', 'index']);
        //INIT SECTION
        \add_action('init', [__NAMESPACE__.'\Init', 'index'], 0);
        //SHORTCODES
        \add_action('init', [__NAMESPACE__.'\Shortcodes', 'index'], 1);
        //POST-TYPES
        \add_action('init', [__NAMESPACE__.'\PostTypes', 'index'], 1);
        //WIDGETS
        \add_action('widgets_init', [__NAMESPACE__.'\Widgets', 'index'], 1);
        //GUTENBERG'S BLOCKS
        \add_action('enqueue_block_editor_assets', [__NAMESPACE__.'\Gutenberg', 'blocks'], 1, 10);
        // DASHBOARD
        \add_action('wp_dashboard_setup', [__NAMESPACE__.'\Admin\Dashboard', 'index']);
        // add_action( 'admin_enqueue_scripts', array(__NAMESPACE__.'\Admin\Dashboard','scripts') );

        if ($action) {
            foreach ($action as $data) {
                if (isset($data)) {
                    call_user_func_array('add_action', [
                        isset($data[0]) ? $data[0] : null,
                        isset($data[1]) ? $data[1] : null,
                        isset($data[2]) ? $data[2] : null,
                        isset($data[3]) ? $data[3] : null,
                    ]);
                }
            }
        }
    }
}
