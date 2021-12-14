<?php
/*
*
* @package yariko


Plugin Name:  CookBook Flow
Plugin URI:   https://thomasgbennett.com/
Description:  This plugin is the core for all the logic around recipes and cookbooks flow. XX
Version:      1.0.0
Author:       Thomas Bennett Group
Author URI:   https://thomasgbennett.com/
Tested up to: 5.3.2
Text Domain:  cbf_domain
Domain Path:  /languages
*/

defined('ABSPATH') or die('You do not have access, sally human!!!');

define ( 'CBF_PLUGIN_VERSION', '1.0.0');

if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php') ){
    require_once  dirname( __FILE__ ) . '/vendor/autoload.php';
}

//Change WRPL for plugin's initials
define('CBF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define('CBF_PLUGIN_URL' , plugin_dir_url(  __FILE__  ) );
define('CBF_ADMIN_URL' , get_admin_url() );
define('CBF_PLUGIN_DIR_BASENAME' , dirname(plugin_basename(__FILE__)) );


//include the helpers
include 'inc/util/helpers.php';

if ( (in_array( 'restrict-content-pro/restrict-content-pro.php', (array) get_option( 'active_plugins', array() ), true ))){
    if( class_exists( 'Cbf\\Inc\\Init' ) ){
        register_activation_hook( __FILE__ , array('Cbf\\Inc\\Base\\Activate','activate') );
        register_deactivation_hook( __FILE__ , array('Cbf\\Inc\\Base\\Deactivate','deactivate') );
        Cbf\Inc\Init::register_services();
    }
}else{

    add_action('admin_notices', function(){
        ?>
        <div class="notice notice-error is-dismissible">
            <p>Restrict Content Pro plugin is required , please activate it to use <b>Cookbook Flow </b> Plugin</p>
        </div>
        <?php
    });
}

if( class_exists( 'Memd\\Inc\\Init' ) ){
    register_activation_hook( __FILE__ , array('Memd\\Inc\\Base\\Activate','activate') );
    register_activation_hook( __FILE__ , array('Memd\\Inc\\Base\\Deactivate','deactivate') );

    Memd\Inc\Init::register_services();
}



