<?php
namespace CH;

class PostTypes
{
  public function index()
  {
    $config = new Config;
    $post_type= new PostTypes;
    $post_type_array= $config->post_types;
    if(is_array($post_type_array))
    {
      foreach ($post_type_array as $pt)
      {
        if($pt['singular'])
        $post_type->register_post_type($pt['singular'],$pt['plural'],$pt['slug'],$pt['translate'],$pt['position'],$pt['taxonomy'],$pt['image']);
      }
    }
    
  }
  /*
  * Easy add new post_type
  * @ver 1.1
  * @descripcion: only add simple variables for create a new Post-Type
  * @output void
  */
    public function register_post_type($singular,$plural,$slug,$translate=false,$position=false,$taxonomy=['category'],$image='antonella-icon.png')
    {
        $translate=$translate?$translate:'text_domain';
        $position=$position?$position:5;
        $labels =[
          'name'               => _x( $plural, 'Post Type General Name', $translate ),
          'singular_name'      => _x( $singular, 'Post Type Singular Name', $translate ),
          'menu_name'          => __( $singular, $translate ),
          'all_items'          => __( $plural, $translate ),
          'view_item'          => __( 'Ver '.$singular, $translate ),
          'add_new_item'       => __( 'Añadir '.$singular, $translate ),
          'add_new'            => __( 'Añadir '.$singular, $translate ),
          'edit_item'          => __( 'Editar '.$singular, $translate ),
          'update_item'        => __( 'Actualizar', $translate ),
          'search_items'       => __( 'Buscar '.$singular, $translate ),
          'not_found'          => __( $singular.' no encontrada', $translate ),
          'not_found_in_trash' => __( $singular.' no encontrada en Papelera', $translate ),
          ];

        $rewrite = [
          'slug'                => $slug,
          'with_front'          => true,
          'pages'               => true,
          'feeds'               => false,
        ];

        $args = [
          'label'               => __( $singular, $translate ),
          'description'         => __( 'Información de las '.$singular, $translate ),
          'labels'              => $labels,
          'supports'            => array( 'title', 'editor', 'comments', 'thumbnail'),
          'taxonomies'          => $taxonomy,
          'hierarchical'        => true,
          'support'             => array('title','custom-fields'),
          'public'              => true,
          'show_ui'             => true,
          'show_in_menu'        => true,
          'show_in_nav_menus'   => true,
          'show_in_admin_bar'   => true,
          'menu_position'       => $position,
          'menu_icon'           => plugins_url('assets/img/'.$image, dirname(__FILE__)),
          'can_export'          => true,
          'has_archive'         => $slug,
          'exclude_from_search' => false,
          'query_var'           => $slug,
          'rewrite'             => $rewrite,
          ];
        register_post_type($slug, $args);
    }
}
