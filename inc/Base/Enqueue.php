<?php

/*
*
* @package Yariko
*
*/

namespace Cbf\Inc\Base;

class Enqueue{

    public function register(){

        add_action( 'admin_enqueue_scripts', array( $this , 'enqueue_admin' ) ); //action to include script to the backend, in order to include in the frontend is just wp_enqueue_scripts instead admin_enqueue_scripts

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend'));

     //   add_action('plugins_loaded', array($this,'Wrkswp_translate_plugin'));*/


    }

   function enqueue_admin() {
       wp_enqueue_script('admin_js', CBF_PLUGIN_URL . '/assets/js/admin.js' ,array('jquery'),'1.0', true);
       wp_enqueue_script( 'admin_js');
       wp_localize_script( 'admin_js', 'parameters',['ajax_url'=> admin_url('admin-ajax.php')]);
    }


    function enqueue_frontend(){

        wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css');

        wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js',array('jquery'));

	    wp_enqueue_script('main-js', CBF_PLUGIN_URL . '/assets/js/main.js' ,array('jquery'),'1.0', true);

        wp_enqueue_style('vue-custom-icon', 'https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css');

        $pageID = get_the_ID();

        if($pageID == 6){

            $user_data = cbf_get_user_info();            

            wp_enqueue_style('quill_js', 'https://cdn.quilljs.com/1.3.6/quill.snow.css');
            wp_enqueue_style('main_css', CBF_PLUGIN_URL . '/assets/css/main.css');
            wp_enqueue_style('mCustomScrollbar_css', CBF_PLUGIN_URL . '/assets/css/jquery.mCustomScrollbar.min.css');

            wp_enqueue_script('toastr-js', 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js' ,array(),'1.0', true);
            wp_enqueue_script('quill-js', 'https://cdn.quilljs.com/1.3.6/quill.min.js' ,array(),'1.0', true);
            wp_enqueue_script('mCustomScrollbar-js', CBF_PLUGIN_URL . '/assets/js/jquery.mCustomScrollbar.concat.min.js' ,array(),'1.0', true);
            wp_enqueue_script('vue-custom-js', CBF_PLUGIN_URL . '/dist/scripts.js' ,array('jquery','toastr-js','quill-js'),'1.0', true);

            $data = array(
            	'first_panel_block' => get_field('right_first_panel_content', 'option'),
            	'second_panel_block' => get_field('right_second_panel_content', 'option'),
            	'customer_support_message' => get_field('customer_support_message', 'option'),
            );

           // var_dump($user_data['user']->data->display_name);exit;

            wp_localize_script( 'vue-custom-js', 'parameters', ['ajax_url'=> admin_url('admin-ajax.php'), 'plugin_path' => CBF_PLUGIN_URL, 'current_user' =>  $user_data['user'],'user' =>  $user_data['user'], 'account_type' => $user_data['account_type'],'premium' => $user_data['premium'], 'owner' => $user_data['owner'],'data' => $data]);

        }

        wp_enqueue_style( 'main_css', CBF_PLUGIN_URL . '/assets/css/main.css'  );
    }

    /*function Wrkswp_enqueue_admin(){
        //enqueue all our scripts admin
        //wp_enqueue_style( 'bootstrap_css', WRKS_PLUGIN_URL . '/assets/css/admin/bootstrap.min.css'  );

    }*/

}