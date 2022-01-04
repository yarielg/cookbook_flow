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

        wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css');
        if(CBF_TEST_ENV){
            wp_enqueue_script('bootstrap-js-slim', 'https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js');
        }

        wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js');

        $pageID = get_the_ID();

        if($pageID == 6){

            $user_data = cbf_get_user_info();

            wp_enqueue_style('vue-custom-icon', 'https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css');

            wp_enqueue_style('quill_js', 'https://cdn.quilljs.com/1.3.6/quill.snow.css');
            wp_enqueue_style('main_css', CBF_PLUGIN_URL . '/assets/css/main.css');

            wp_enqueue_script('toastr-js', 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js' ,array(),'1.0', true);
            wp_enqueue_script('quill-js', 'https://cdn.quilljs.com/1.3.6/quill.min.js' ,array(),'1.0', true);
            wp_enqueue_script('vue-custom-js', CBF_PLUGIN_URL . '/dist/scripts.js' ,array('jquery','toastr-js','quill-js'),'1.0', true);
            wp_localize_script( 'vue-custom-js', 'parameters', ['ajax_url'=> admin_url('admin-ajax.php'),'plugin_path' => CBF_PLUGIN_URL, 'current_user' =>  $user_data['user'],'user' =>  $user_data['user'], 'account_type' => $user_data['account_type'],'premium' => $user_data['premium'], 'owner' => $user_data['owner']]);

        }

        wp_enqueue_style( 'main_css', CBF_PLUGIN_URL . '/assets/css/main.css'  );
    }

    /*function Wrkswp_enqueue_admin(){
        //enqueue all our scripts admin
        //wp_enqueue_style( 'bootstrap_css', WRKS_PLUGIN_URL . '/assets/css/admin/bootstrap.min.css'  );

    }*/

}