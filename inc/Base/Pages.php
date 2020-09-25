<?php

/*
*
* @package Yariko
*
*/

namespace Memd\Inc\Base;

use Memd\Inc\Services\MemdService;

class Pages{

    protected $memd;

    function __construct()
    {
        $this->memd = new MemdService();
    }

    public function register(){
        add_action('admin_menu', function(){
            add_menu_page('Memd Integration', 'Memd Integration', 'manage_options', 'memd-main-menu', array($this,'settings') );
        });

        add_action('admin_menu',function(){
            $page_product =  add_submenu_page( 'memd-main-menu', __('Settings','memd_integration'), __('Settings','memd_integration'),'manage_options', 'memd-settings-menu', array($this,'settings'));
            add_action( 'load-' . $page_product, function(){
                add_action( 'admin_enqueue_scripts',function (){
                    wp_enqueue_style( 'bootstrap_main_css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'  );


                    wp_enqueue_script( 'bootstrap_main_js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'));
                });
            });

         });
    }

    function settings(){

        require_once MEMD_PLUGIN_PATH . 'templates/settings.php';
    }

    

}
?>