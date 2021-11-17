<?php

/*
*
* @package Yariko
*
*/

namespace Cbf\Inc\Base;

class Enqueue{

    public function register(){

       // add_action( 'admin_enqueue_scripts', array( $this , 'Wrkswp_enqueue_admin' ) ); //action to include script to the backend, in order to include in the frontend is just wp_enqueue_scripts instead admin_enqueue_scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'memd_enqueue_frontend'));

     //   add_action('plugins_loaded', array($this,'Wrkswp_translate_plugin'));*/


    }

   /* function Wrkswp_translate_plugin() {
        load_plugin_textdomain( 'bg_sharer_mu', false, WRKSWP_PLUGIN_DIR_BASENAME .'/languages/' );
    }*/

    function memd_enqueue_frontend(){
        //enqueue all our scripts frontend
        wp_enqueue_style( 'bootstrap_css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'  );


        wp_enqueue_script( 'bootstrap_js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'));
        wp_enqueue_script( 'main_js', CBF_PLUGIN_URL . '/assets/js/main.js', array('jquery'));

        wp_enqueue_style( 'main_css', CBF_PLUGIN_URL . '/assets/css/main.css'  );
    }

    /*function Wrkswp_enqueue_admin(){
        //enqueue all our scripts admin
        //wp_enqueue_style( 'bootstrap_css', WRKS_PLUGIN_URL . '/assets/css/admin/bootstrap.min.css'  );

    }*/

}