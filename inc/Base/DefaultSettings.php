<?php


namespace Cbf\Inc\Base;


class DefaultSettings
{
    function register(){

        /**
         * Adding the CPT and taxonomies needed
         */

        add_action( 'init', array($this,'cptui_register_my_cpts') );
        add_action( 'init', array($this, 'cptui_register_my_taxes_cat_recipe') );
        add_action( 'init', array($this, 'cptui_register_my_cpts_cookbook') );

        /**
         * Overriding RCP templates
         */

        add_filter( 'rcp_template_stack', array($this, 'cbf_overriding_rcp_templates'), 1,10 );

        /**
         * Adding a new recipe CPT status
         */
        add_action( 'init', array($this,'cbf_register_custom_post_status') );

    }

    // Register Custom Post Status
    function cbf_register_custom_post_status(){
        register_post_status( 'Private', array(
            'label'                     => _x( 'Private', 'recipe' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'In Private <span class="count">(%s)</span>', 'In Private <span class="count">(%s)</span>' ),
        ) );
    }


    function cbf_overriding_rcp_templates( $template, $names )
    {
    $plugin_template_path = CBF_PLUGIN_PATH . 'templates/rcp/';

    if(in_array($names[0], array('login.php','register.php'))){
        $template[] = $plugin_template_path;
    }

    return $template;
    }

    function cptui_register_my_cpts() {

        /**
         * Post Type: Recipes.
         */

        $labels = [
            "name" => __( "Recipes", "twentytwentyone" ),
            "singular_name" => __( "Recipe", "twentytwentyone" ),
            "menu_name" => __( "My Recipes", "twentytwentyone" ),
            "all_items" => __( "All Recipes", "twentytwentyone" ),
            "add_new" => __( "Add new", "twentytwentyone" ),
            "add_new_item" => __( "Add new Recipe", "twentytwentyone" ),
            "edit_item" => __( "Edit Recipe", "twentytwentyone" ),
            "new_item" => __( "New Recipe", "twentytwentyone" ),
            "view_item" => __( "View Recipe", "twentytwentyone" ),
            "view_items" => __( "View Recipes", "twentytwentyone" ),
            "search_items" => __( "Search Recipes", "twentytwentyone" ),
            "not_found" => __( "No Recipes found", "twentytwentyone" ),
            "not_found_in_trash" => __( "No Recipes found in trash", "twentytwentyone" ),
            "parent" => __( "Parent Recipe:", "twentytwentyone" ),
            "featured_image" => __( "Featured image for this Recipe", "twentytwentyone" ),
            "set_featured_image" => __( "Set featured image for this Recipe", "twentytwentyone" ),
            "remove_featured_image" => __( "Remove featured image for this Recipe", "twentytwentyone" ),
            "use_featured_image" => __( "Use as featured image for this Recipe", "twentytwentyone" ),
            "archives" => __( "Recipe archives", "twentytwentyone" ),
            "insert_into_item" => __( "Insert into Recipe", "twentytwentyone" ),
            "uploaded_to_this_item" => __( "Upload to this Recipe", "twentytwentyone" ),
            "filter_items_list" => __( "Filter Recipes list", "twentytwentyone" ),
            "items_list_navigation" => __( "Recipes list navigation", "twentytwentyone" ),
            "items_list" => __( "Recipes list", "twentytwentyone" ),
            "attributes" => __( "Recipes attributes", "twentytwentyone" ),
            "name_admin_bar" => __( "Recipe", "twentytwentyone" ),
            "item_published" => __( "Recipe published", "twentytwentyone" ),
            "item_published_privately" => __( "Recipe published privately.", "twentytwentyone" ),
            "item_reverted_to_draft" => __( "Recipe reverted to draft.", "twentytwentyone" ),
            "item_scheduled" => __( "Recipe scheduled", "twentytwentyone" ),
            "item_updated" => __( "Recipe updated.", "twentytwentyone" ),
            "parent_item_colon" => __( "Parent Recipe:", "twentytwentyone" ),
        ];

        $args = [
            "label" => __( "Recipes", "twentytwentyone" ),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => [ "slug" => "recipe", "with_front" => true ],
            "query_var" => true,
            "supports" => [ "title", "editor", "thumbnail" ],
            "taxonomies" => [ "cat_recipe" ],
            "show_in_graphql" => false,
        ];

        register_post_type( "recipe", $args );
    }

    function cptui_register_my_taxes_cat_recipe() {

        /**
         * Taxonomy: Recipe Categories.
         */

        $labels = [
            "name" => __( "Recipe Categories", "custom-post-type-ui" ),
            "singular_name" => __( "Recipe Category", "custom-post-type-ui" ),
        ];


        $args = [
            "label" => __( "Recipe Categories", "custom-post-type-ui" ),
            "labels" => $labels,
            "public" => true,
            "publicly_queryable" => true,
            "hierarchical" => false,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => [ 'slug' => 'cat_recipe', 'with_front' => true, ],
            "show_admin_column" => false,
            "show_in_rest" => true,
            "show_tagcloud" => false,
            "rest_base" => "cat_recipe",
            "rest_controller_class" => "WP_REST_Terms_Controller",
            "show_in_quick_edit" => false,
            "show_in_graphql" => false,
        ];
        register_taxonomy( "cat_recipe", [ "recipe" ], $args );
    }

    function cptui_register_my_cpts_cookbook() {

        /**
         * Post Type: Cookbooks.
         */

        $labels = [
            "name" => __( "Cookbooks", "custom-post-type-ui" ),
            "singular_name" => __( "Cookbook", "custom-post-type-ui" ),
            "menu_name" => __( "My Cookbooks", "custom-post-type-ui" ),
            "all_items" => __( "All Cookbooks", "custom-post-type-ui" ),
            "add_new" => __( "Add new", "custom-post-type-ui" ),
            "add_new_item" => __( "Add new Cookbook", "custom-post-type-ui" ),
            "edit_item" => __( "Edit Cookbook", "custom-post-type-ui" ),
            "new_item" => __( "New Cookbook", "custom-post-type-ui" ),
            "view_item" => __( "View Cookbook", "custom-post-type-ui" ),
            "view_items" => __( "View Cookbooks", "custom-post-type-ui" ),
            "search_items" => __( "Search Cookbooks", "custom-post-type-ui" ),
            "not_found" => __( "No Cookbooks found", "custom-post-type-ui" ),
            "not_found_in_trash" => __( "No Cookbooks found in trash", "custom-post-type-ui" ),
            "parent" => __( "Parent Cookbook:", "custom-post-type-ui" ),
            "featured_image" => __( "Featured image for this Cookbook", "custom-post-type-ui" ),
            "set_featured_image" => __( "Set featured image for this Cookbook", "custom-post-type-ui" ),
            "remove_featured_image" => __( "Remove featured image for this Cookbook", "custom-post-type-ui" ),
            "use_featured_image" => __( "Use as featured image for this Cookbook", "custom-post-type-ui" ),
            "archives" => __( "Cookbook archives", "custom-post-type-ui" ),
            "insert_into_item" => __( "Insert into Cookbook", "custom-post-type-ui" ),
            "uploaded_to_this_item" => __( "Upload to this Cookbook", "custom-post-type-ui" ),
            "filter_items_list" => __( "Filter Cookbooks list", "custom-post-type-ui" ),
            "items_list_navigation" => __( "Cookbooks list navigation", "custom-post-type-ui" ),
            "items_list" => __( "Cookbooks list", "custom-post-type-ui" ),
            "attributes" => __( "Cookbooks attributes", "custom-post-type-ui" ),
            "name_admin_bar" => __( "Cookbook", "custom-post-type-ui" ),
            "item_published" => __( "Cookbook published", "custom-post-type-ui" ),
            "item_published_privately" => __( "Cookbook published privately.", "custom-post-type-ui" ),
            "item_reverted_to_draft" => __( "Cookbook reverted to draft.", "custom-post-type-ui" ),
            "item_scheduled" => __( "Cookbook scheduled", "custom-post-type-ui" ),
            "item_updated" => __( "Cookbook updated.", "custom-post-type-ui" ),
            "parent_item_colon" => __( "Parent Cookbook:", "custom-post-type-ui" ),
        ];

        $args = [
            "label" => __( "Cookbooks", "custom-post-type-ui" ),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => [ "slug" => "cookbook", "with_front" => true ],
            "query_var" => true,
            "menu_icon" => "dashicons-book-alt",
            "supports" => [ "title", "editor", "thumbnail" ],
            "show_in_graphql" => false,
        ];

        register_post_type( "cookbook", $args );
    }


}