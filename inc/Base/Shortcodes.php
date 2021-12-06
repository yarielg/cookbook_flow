<?php
/**
 * Shortcode class contain all the logic to create all the shortcodes used on the site.
 *
 */

namespace Cbf\Inc\Base;


class Shortcodes
{
    public function register(){
        /**
         * Shortcodes
         */
        add_shortcode( 'cbf_login', array($this, 'login') );
        add_shortcode( 'cbf_dashboard', array($this, 'dashboard') );
        add_shortcode( 'cbf_search_recipe', array($this, 'searchRecipe') );
    }


    /**
     * Shortcode to render the user dashboard
     *
     * This shortcode contain the logic to define the user dashboard, vue is used to provide a reactive interface
     *
     * @return string
     */
    public function dashboard(){
        return "<div id='vwp-plugin'></div>";
    }

    public function login(){
        $output = memd_template(CBF_PLUGIN_PATH . 'templates/login.php' , array());
        return $output;
    }

    public function searchRecipe(){

        global $post;
        $post_slug = $post->post_name;

        $output = memd_template(CBF_PLUGIN_PATH . 'templates/search_recipe.php' , array('recipes' => $recipes,'slug' => $post_slug));
        return $output;
    }

}