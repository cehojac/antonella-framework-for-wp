<?php
namespace CH;

use CH\Config;
use CH\Classes\Template;

class Gutenberg
{
    public function __construct()
    {

    }

    public static function index()
    {

    }


    /** 
      * Registra nuestro blocks
      * Recuerda que cada uno de nuestros blocks van en src/Blocks/tu-nuevo-block
      * y para poder importarlos añadelo también en src/Blocks/index.js de la siguiente forma
      * fichero: src/Blocks/index.js
      *     import "./src/Blocks/tu-nuevo-block"  
     */
    public static function blocks() {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        // Si gutenberg no existe, no ejecutar nada
        // quizas mejor ponerlo en Hooks.php para encolarlo o no
        if (!function_exists('register_block_type')) { return; }

        // Registrar el script que contiene los bloques
        \wp_register_script(
            'antonella-editor-script',  // nombre
            \plugin_dir_url(__DIR__) .'assets/blocks/index.js',    // archivo con todos los blocks
            ['wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor'], // dependencias
            filemtime(\plugin_dir_path(__DIR__) . 'assets/blocks/index.js'), // version
            true 
        );
		
		// bootstrap-grid.min.css
        if (file_exists(\plugin_dir_path(__DIR__) . 'assets/css/bootstrap-grid.min.css')) {
            \wp_enqueue_style(
                'bootstrap-grid',   // handle
                \plugin_dir_url(__DIR__) . 'assets/css/bootstrap-grid.min.css', // bootstrap-grid
                array(), // dependencias
                filemtime(\plugin_dir_path(__DIR__) . 'assets/css/bootstrap-grid.min.css'), // version
                'all'
            );
        }

        if (file_exists(\plugin_dir_path(__DIR__) . 'assets/blocks/css/editor.css')) {
            \wp_register_style(
                'antonella-editor-styles', // nombre
                \plugin_dir_url(__DIR__) .'assets/blocks/css/editor.css',    // estilos css para el editor de gutenberg
                array('wp-edit-blocks'), // dependencias
                filemtime(\plugin_dir_path(__DIR__) . 'assets/blocks/css/editor.css'), // version
                'all'
            );
        }

        if (file_exists(\plugin_dir_path(__DIR__) . 'assets/blocks/css/style.css')) {
            \wp_register_style(
                'antonella-front-end-styles', // nombre
                \plugin_dir_url(__DIR__) .'assets/blocks/css/style.css',    // estilos css para el front-end
                array(), // dependencias
                filemtime(\plugin_dir_path(__DIR__) . 'assets/blocks/css/style.css'), // version
                'all'
            );
        }


        $config = new Config;
        $blocks = $config->gutenberg_blocks;

        foreach ($blocks as $block => $args) {
			
			$data = [
				'editor_script' => 'antonella-editor-script',   // informacion del bloque
                'editor_style' => 'antonella-editor-styles',    // estilos css para el editor de gutenber 
                'style' => 'antonella-front-end-styles'         // estilos css para el front end	
			];
			
			// registramos el block
			register_block_type($block, array_merge($data, $args));
        }
    }


    /** Definicón de los render_callback para tus blocks dinámicos */
    public static function antonella_dinamico_render_callback($attr) {

        $count = isset( $attr['count'] ) ? $attr['count'] : 3;

        $apiUrl = sprintf('https://carlos-herrera.com/wp-json/wp/v2/posts?_embed&per_page=%s', $count);
        $response = wp_remote_get($apiUrl, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accepts' => 'application/json',
                'Authorization' => 'Basic c2FnaXRhcml1czpzYWdpdGFyaXVz'
            ]
        ]);
        
        $responseBody = wp_remote_retrieve_body( $response );
        $posts = json_decode( $responseBody );
        
        /** 
         * Buscará en 
         *      resources/templates/dinamico/index.php
		 * 			( No requiere blade )
         * 
        */
        return Template::render('dinamico/index', compact('attr', 'posts'));
        
        /** 
         * 
         * Version blade
         *      return 
         *          view('dinamico/index', compact('attr', 'posts'))
         * 
         * resources/views/dinamico/index.blade.php
        */

    }    
} /* generated with antonella framework */
