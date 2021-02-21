<?php

namespace CH;

class PostTypes
{
    public static function index()
    {
        $config = new Config();
        $post_type = new PostTypes();
        $post_type_array = $config->post_types;
        $taxonomy_array = $config->taxonomies;
        if (is_array($post_type_array)) {
            foreach ($post_type_array as $pt) {
                if ($pt['singular']) {
                    $post_type->register_post_type($pt);
                }
            }
        }
        if (is_array($taxonomy_array)) {
            foreach ($taxonomy_array as $tx) {
                if ($tx['singular']) {
                    $post_type->add_taxonomy($tx);
                }
            }
        }
    }

    /**
     * Easy add new post_type and taxonomies
     * Add variables for create a new Post-Type.
     *
     * @version 2.1
     *
     * @param array $pt From Config  $config->post_types
     *
     * @return void
     */
    public function register_post_type($pt)
    {
        $config = new Config();
        $img = explode('.', $pt['image']);
        $image = isset($img[1]) && strlen($img[1] <= 4) ? plugins_url('assets/img/'.$pt['image'], dirname(__FILE__)) : $pt['image'];
        $translate = $config->language_name;
        $singular = $pt['singular'];
        $plural = $pt['plural'];
        $slug = $pt['slug'];
        $taxonomy = $pt['taxonomy'];

        $labels = [];
        $args = [];
        $rewrite = [];

        $labels['name'] = isset($pt['labels']['name']) ? _x($pt['labels']['name'], $translate) : _x($plural, 'Post Type General Name', $translate);
        $labels['singular_name'] = isset($pt['labels']['singular_name']) ? $pt['labels']['singular_name'] : _x($singular, 'Post Type Singular Name', $translate);

        $labels['menu_name'] = isset($pt['labels']['menu_name']) ? __($pt['labels']['menu_name'], $translate) : __($singular, $translate);
        $labels['all_items'] = isset($pt['labels']['all_items']) ? __($pt['labels']['all_items'], $translate) : __($plural, $translate);
        $labels['view_item'] = isset($pt['labels']['view_item']) ? __($pt['labels']['view_item'], $translate) : __('See '.$singular, $translate);
        $labels['add_new_item'] = isset($pt['labels']['add_new_item']) ? __($pt['labels']['add_new_item'], $translate) : __('Add '.$singular, $translate);
        $labels['add_new'] = isset($pt['labels']['add_new']) ? __($pt['labels']['add_new'], $translate) : __('Add '.$singular, $translate);
        $labels['edit_item'] = isset($pt['labels']['edit_item']) ? __($pt['labels']['edit_item'], $translate) : __('Edit '.$singular, $translate);
        $labels['update_item'] = isset($pt['labels']['update_item']) ? __($pt['labels']['update_item'], $translate) : __('Update '.$singular, $translate);
        $labels['search_items'] = isset($pt['labels']['search_items']) ? __($pt['labels']['search_items'], $translate) : __('Search '.$singular, $translate);
        $labels['not_found'] = isset($pt['labels']['not_found']) ? __($pt['labels']['not_found'], $translate) : __($singular.' not found', $translate);
        $labels['not_found_in_trash'] = isset($pt['labels']['not_found_in_trash']) ? __($pt['labels']['not_found_in_trash'], $translate) : __($singular.' not found in trash', $translate);

        $rewrite['slug'] = $slug;
        $rewrite['whit_front'] = isset($pt['rewrite']['whitfront']) ? $pt['rewrite']['whitfront'] : true;
        $rewrite['pages'] = isset($pt['rewrite']['pages']) ? $pt['rewrite']['pages'] : true;
        $rewrite['feeds'] = isset($pt['rewrite']['feeds']) ? $pt['rewrite']['feeds'] : false;

        $args['label'] = isset($pt['args']['label']) ? $pt['args']['label'] : __($plural, $translate);
        $args['labels'] = $labels;
        $args['description'] = isset($pt['args']['description']) ? __($pt['args']['description'], $translate) : __('Info about '.$singular, $translate);
        $args['supports'] = isset($pt['args']['supports']) ? $pt['args']['supports'] : ['title', 'editor', 'comments', 'thumbnail'];
        $args['public'] = isset($pt['args']['public']) ? $pt['args']['public'] : true;
        $args['publicly_queryable'] = isset($pt['args']['publicly_queryable']) ? $pt['args']['publicly_queryable'] : true;
        $args['show_ui'] = isset($pt['args']['show_ui']) ? $pt['args']['show_ui'] : true;
        $args['delete_with_user'] = isset($pt['args']['delete_with_user']) ? $pt['args']['delete_with_user'] : null;
        $args['show_in_rest'] = isset($pt['args']['show_in_rest']) ? $pt['args']['show_in_rest'] : ($pt['gutemberg'] == false ? false : true);
        $args['rest_base'] = isset($pt['args']['rest_base']) ? $pt['args']['rest_base'] : $slug;
        $args['rest_controller_class'] = isset($pt['args']['rest_controller_class']) ? $pt['args']['rest_controller_class'] : 'WP_REST_Posts_Controller';
        $args['has_archive'] = isset($pt['args']['has_archive']) ? $pt['args']['has_archive'] : $slug;
        $args['show_in_menu'] = isset($pt['args']['show_in_menu']) ? $pt['args']['show_in_menu'] : true;
        $args['show_in_nav_menus'] = isset($pt['args']['show_in_nav_menus']) ? $pt['args']['show_in_nav_menus'] : true;
        $args['exclude_from_search'] = isset($pt['args']['exclude_from_search']) ? $pt['args']['exclude_from_search'] : false;
        $args['capability_type'] = isset($pt['args']['capability_type']) ? $pt['args']['capability_type'] : 'post';
        $args['map_meta_cap'] = isset($pt['args']['map_meta_cap']) ? $pt['args']['map_meta_cap'] : true;
        $args['hierarchical'] = isset($pt['args']['hierarchical']) ? $pt['args']['hierarchical'] : false;
        $args['rewrite'] = isset($pt['args']['rewrite']) ? $pt['args']['rewrite'] : ['slug' => $slug, 'with_front' => true];
        $args['query_var'] = isset($pt['args']['query_var']) ? $pt['args']['query_var'] : true;
        $args['menu_position'] = isset($pt['args']['position']) ? $pt['args']['position'] : ($pt['position'] ? $pt['position'] : 4);
        $args['menu_icon'] = $image;

        register_post_type($slug, $args);

        //Taxonomies
        if (is_array($taxonomy) && count($taxonomy) > 0) {
            foreach ($taxonomy as $tx) {
                register_taxonomy($tx, [$slug], [
                'label' => __($tx, $translate),
                'show_in_rest' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
              ]);
            }
        }
    }

    /**
     * Add taxonomies
     * Add variables for create a new Taxonomy.
     *
     * @version 1.0
     *
     * @param array $tx From Config  $config->post_types
     *
     * @return void
     */
    public function add_taxonomy($tx)
    {
        $config = new Config();
        $labels = [];
        $args = [];
        $capabilities = [];
        $rewrite = [];
        $post_type = $tx['post_type'];
        $singular = $tx['singular'];
        $plural = $tx['plural'];
        $slug = $tx['slug'];
        $pt = $tx['post_type'];
        $translate = $config->language_name;

        $labels['name'] = isset($tx['labels']['name']) ? $tx['labels']['name'] : _x($plural, 'Taxonomy general name', $translate);
        $labels['singular_name'] = isset($tx['labels']['singular_name']) ? $tx['labels']['singular_name'] : _x($singular, 'Taxonomy singular name', $translate);
        $labels['search_items'] = isset($tx['labels']['search_items']) ? $tx['labels']['search_items'] : __('Search '.$singular, $translate);
        $labels['all_items'] = isset($tx['labels']['all_items']) ? $tx['labels']['all_items'] : __('All '.$singular, $translate);
        $labels['parent_item'] = isset($tx['labels']['parent_item']) ? $tx['labels']['parent_item'] : __('Parent '.$singular, $translate);
        $labels['parent_item_colon'] = isset($tx['labels']['parent_item_colon']) ? $tx['labels']['parent_item_colon'] : __('Parebt '.$singular, $translate);
        $labels['edit_item'] = isset($tx['labels']['edit_item']) ? $tx['labels']['edit_item'] : __('Edit '.$singular, $translate);
        $labels['view_item'] = isset($tx['labels']['view_item']) ? $tx['labels']['view_item'] : __('View '.$singular, $translate);
        $labels['update_item'] = isset($tx['labels']['update_item']) ? $tx['labels']['update_item'] : __('Update '.$singular, $translate);
        $labels['add_new_item'] = isset($tx['labels']['add_new_item']) ? $tx['labels']['add_new_item'] : __('Add new '.$singular, $translate);
        $labels['new_item_name'] = isset($tx['labels']['new_item_name']) ? $tx['labels']['new_item_name'] : __('New '.$singular, $translate);
        $labels['menu_name'] = isset($tx['labels']['menu_name']) ? $tx['labels']['menu_name'] : _x($plural, $translate);
        $labels['popular_items'] = isset($tx['labels']['popular_items']) ? $tx['labels']['popular_items'] : _x('Popular '.$plural, $translate);

        $args['hierarchical'] = isset($tx['args']['hierarchical']) ? $tx['args']['hierarchical'] : true;
        $args['labels'] = $labels;
        $args['show_ui'] = isset($tx['args']['show_ui']) ? $tx['args']['show_ui'] : true;
        $args['public'] = isset($tx['args']['public']) ? $tx['args']['public'] : true;
        $args['publicly_queryable'] = isset($tx['args']['publicly_queryable']) ? $tx['args']['publicly_queryable'] : true;
        $args['show_admin_column'] = isset($tx['args']['show_admin_column']) ? $tx['args']['show_admin_column'] : true;
        $args['show_in_menu'] = isset($tx['args']['show_in_menu']) ? $tx['args']['show_in_menu'] : true;
        $args['show_in_rest'] = isset($tx['args']['show_in_rest']) ? $tx['args']['show_in_rest'] : ($tx['gutemberg'] == false ? false : true);
        $args['query_var'] = $slug;
        $args['rest_base'] = isset($tx['args']['rest_base']) ? $tx['args']['rest_base'] : $plural;
        $args['rest_controller_class'] = isset($tx['args']['rest_controller_class']) ? $tx['args']['rest_controller_class'] : 'WP_REST_Terms_Controller';
        $args['show_tagcloud'] = isset($tx['args']['show_tagcloud']) ? $tx['args']['show_tagcloud'] : $args['show_ui'];
        $args['show_in_quick_edit'] = isset($tx['args']['show_in_quick_edit']) ? $tx['args']['show_in_quick_edit'] : $args['show_ui'];
        $args['meta_box_cb'] = isset($tx['args']['meta_box_cb']) ? $tx['args']['meta_box_cb'] : null;
        $args['show_admin_column'] = isset($tx['args']['show_admin_column']) ? $tx['args']['show_admin_column'] : false;
        $args['show_in_nav_menus'] = isset($tx['args']['show_in_nav_menus']) ? $tx['args']['show_in_nav_menus'] : true;
        $args['query_var'] = isset($tx['args']['query_var']) ? $tx['args']['query_var'] : true;
        $args['rewrite'] = isset($tx['args']['rewrite']) ? $tx['args']['rewrite'] : (isset($tx['rewrite']) ? $tx['rewrite'] : ['slug' => $slug]);
        $args['description'] = isset($tx['args']['description']) ? $tx['args']['description'] : '';
        $args['query_var'] = $slug;

        $rewrite['slug'] = $slug;
        $rewrite['with_front'] = isset($tx['args']['rewrite']['with_front']) ? $tx['args']['rewrite']['with_front'] : (isset($tx['rewrite']['with_front']) ? $tx['rewrite']['with_front'] : true);
        $rewrite['hierarchical'] = isset($tx['args']['rewrite']['hierarchical']) ? $tx['args']['rewrite']['hierarchical'] : (isset($tx['rewrite']['hierarchical']) ? $tx['rewrite']['hierarchical'] : false);
        $rewrite['ep_mask'] = isset($tx['args']['rewrite']['ep_mask']) ? $tx['args']['rewrite']['ep_mask'] : (isset($tx['rewrite']['ep_mask']) ? $tx['rewrite']['ep_mask'] : EP_NONE);

        $args['rewrite'] = $rewrite;

        $capabilities['manage_terms'] = isset($tx['args']['capabilities']['manage_terms']) ? $tx['args']['capabilities']['manage_terms'] : (isset($tx['capabilities']['manage_terms']) ? $tx['capabilities']['manage_terms'] : 'manage_'.$slug);
        $capabilities['edit_terms'] = isset($tx['args']['capabilities']['edit_terms']) ? $tx['args']['capabilities']['edit_terms'] : (isset($tx['capabilities']['edit_terms']) ? $tx['capabilities']['edit_terms'] : 'manage_'.$slug);
        $capabilities['delete_terms'] = isset($tx['args']['capabilities']['delete_terms']) ? $tx['args']['capabilities']['delete_terms'] : (isset($tx['capabilities']['delete_terms']) ? $tx['capabilities']['delete_terms'] : 'manage_'.$slug);
        $capabilities['assign_terms'] = isset($tx['args']['capabilities']['assign_terms']) ? $tx['args']['capabilities']['assign_terms'] : (isset($tx['capabilities']['assign_terms']) ? $tx['capabilities']['assign_terms'] : 'edit_'.$slug);

        register_taxonomy($plural, [$post_type], $args);
    }
}
