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
        add_shortcode( 'cbf_featured_recipes', array($this, 'featuredRecipes') );
    }

	/**
	 * Shortcode to render featured recipes
	 */
	function featuredRecipes($atts){

		$a = shortcode_atts( array(
			'cols' => 4,
			'flag_width' => 100,
			'flag_height' => 50,
		), $atts );

		$args = array(
			'post_type' => 'recipe',
			'posts_per_page' => $a['cols'],
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => 'featured_recipe',
					'value' => '1',
					'compare' => '==' // not really needed, this is the default
				)
			)
		);

		$cols = in_array(intval($a['cols']), array(3,4)) ?  (intval($a['cols']) == 3 ? 4 : 3) : 3;

		$recipes = new \WP_Query($args);

		$output = memd_template(CBF_PLUGIN_PATH . 'templates/featured-recipes.php' , array('a' => $a, 'cols' => $cols, 'recipes' => $recipes));
		return $output;
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

        $output = memd_template(CBF_PLUGIN_PATH . 'templates/search_recipe.php' , array());
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