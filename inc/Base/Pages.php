<?php

/*
*
* @package Yariko
*
*/

namespace Cbf\Inc\Base;

use Cbf\Inc\Services\HubspotService;

class Pages{

    public function register(){
        add_action('admin_menu', function(){
            add_menu_page('Hubspot Integrations', 'Hubspot Integrations', 'manage_options', 'cbf-hubspot-settings', array($this,'settings') );
        });

    }

    function settings(){

    	$hubspot = new HubspotService();


    	$lists = $hubspot->getLists();
    	if(isset($lists['lists'])){
    		$lists = $lists['lists'];
	    }
		//8eb58d35-a8f6-4ef1-8c8a-fba26fccf4e7
    	$key = get_option('cbf_hubspot_key', false);
	    $premium_list = get_option('cbf_hubspot_premium_list', false) ? get_option('cbf_hubspot_premium_list', false) : '';
	    $free_list = get_option('cbf_hubspot_free_list',false) ? get_option('cbf_hubspot_free_list',false) : '';

        require_once CBF_PLUGIN_PATH . 'templates/settings.php';
    }

    

}
?>