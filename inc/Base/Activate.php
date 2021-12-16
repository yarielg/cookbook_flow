<?php

/*
*
* @package yariko
*
*/
namespace Cbf\Inc\Base;

class Activate{

    public static function activate(){
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $table_name1 = $wpdb->prefix . 'cbf_recipes_cookbooks';

        $sql1 = "CREATE TABLE $table_name1 (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          recipe_id INT NOT NULL,
          cookbook_id INT NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql1 );


    }
}
