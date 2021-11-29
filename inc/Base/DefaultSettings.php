<?php


namespace Cbf\Inc\Base;


class DefaultSettings
{
    function register(){

        /*add_role(
            'primary_member',
            __( 'Primary Member' ),
            array()
        );
        add_role(
            'dependent_member',
            __( 'Dependent Member' ),
            array()
        );

        add_role(
            'dependent_member_child',
            __( 'Dependent Child' ),
            array()
        );*/

        add_action( 'init', array($this,'cptui_register_my_cpts') );

        add_action( 'init', array($this, 'cptui_register_my_taxes_cat_recipe') );


       /* $my_post = array(
            'post_title'    => 'Yariel',
            'post_author'   => 2,
            'post_content'  => 'asdasdasdasd',
            'post_status'   => 'publish',
            'post_type' => 'recipe'
        );

        $id = wp_insert_post( $my_post );*/

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

}