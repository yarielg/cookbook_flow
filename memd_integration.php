<?php
/*
*
* @package yariko


Plugin Name:  MeMD Integration
Plugin URI:   https://thomasgbennett.com/
Description:  Create an integration with MeMD
Version:      1.0.0
Author:       Bennet Group (Yariel Gordillo)
Author URI:   https://thomasgbennett.com/
Tested up to: 5.3.2
Text Domain:  memd_integration
Domain Path:  /languages
*/

defined('ABSPATH') or die('You do not have access, sally human!!!');

define ( 'MEMD_PLUGIN_VERSION', '1.0.0');

if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php') ){
    require_once  dirname( __FILE__ ) . '/vendor/autoload.php';
}
//Change WRPL for plugin's initials
define('MEMD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define('MEMD_PLUGIN_URL' , plugin_dir_url(  __FILE__  ) );
define('MEMD_ADMIN_URL' , get_admin_url() );
define('MEMD_PLUGIN_DIR_BASENAME' , dirname(plugin_basename(__FILE__)) );

/*define('MEMD_BASE_URI', 'https://staging-services.memd.me');
define('MEMD_CLIENT_ID', 'fyi_ClientAPI');
define('MEMD_CLIENT_SECRET', 'DgOfVl5bEccZg7lp');
define('MEMD_USERNAME', 'FYIAPIUSER');
define('MEMD_PASSWORD', 'GB3g%*kO$XUR9Y33!');*/

//include the helpers
include 'inc/util/helpers.php';

if ( (in_array( 'restrict-content-pro/restrict-content-pro.php', (array) get_option( 'active_plugins', array() ), true ))){
    if( class_exists( 'Wrpl\\Inc\\Init' ) ){
        register_activation_hook( __FILE__ , array('Wrpl\\Inc\\Base\\Activate','activate') );
        register_deactivation_hook( __FILE__ , array('Wrpl\\Inc\\Base\\Deactivate','deactivate') );
        Wrpl\Inc\Init::register_services();
    }
}else{

    add_action('admin_notices', function(){
        ?>
        <div class="notice notice-error is-dismissible">
            <p>Restrict Content Pro plugin is required , please activate it to use <b>MeMD Integration</b> Plugin</p>
        </div>
        <?php
    });
}

if( class_exists( 'Memd\\Inc\\Init' ) ){
    register_activation_hook( __FILE__ , array('Memd\\Inc\\Base\\Activate','activate') );
    register_activation_hook( __FILE__ , array('Memd\\Inc\\Base\\Deactivate','deactivate') );

    Memd\Inc\Init::register_services();
}



