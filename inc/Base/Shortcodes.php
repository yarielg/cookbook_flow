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
        add_shortcode( 'cbf_collaborator_sign_up', array($this, 'collaboratorSignUp') );
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

    /**
     * Shortcode to output the form for collaborators to sign up
     */
    public function collaboratorSignUp(){
        /**
         * 1- get  the parameters
         * 2- update user with the new password
         * 3- login the user
         */
        $data = array();

        if(isset($_GET['token']) && strlen($_GET['token']) == 22){
            $data = array(
                'first' => $_GET['first'],
                'last' => $_GET['last'],
                'email' => $_GET['email'],
                'token' => $_GET['token'],
                'collaborator_id' => $_GET['collaborator_id']
            );

            $output = memd_template(CBF_PLUGIN_PATH . 'templates/collaborator_sign_up.php' , $data);
            return $output;
        }

        return do_shortcode('[login_form]');

    }
}