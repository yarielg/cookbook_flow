<?php

/*
*
* @package Yariko
*
*/

namespace Cbf\Inc\Base;

class POST{

    public function register(){

        /**
         * All POST request
         */
        add_action( 'admin_post_cbf_change_collaborator_password', array($this, 'changeCollaboratorPassword') );
        add_action( 'admin_post_nopriv_cbf_change_collaborator_password', array($this, 'changeCollaboratorPassword') );

	    /**
	     * Save the hubspot settings
	     */
        add_action( 'admin_post_cbf_save_hubspot_settings', array($this, 'saveHubspotSettings') );
        add_action( 'admin_post_nopriv_cbf_save_hubspot_settings', array($this, 'saveHubspotSettings') );

    }

    /**
     * Receive the $_POST data to change the collaborator password and logged in
     */
    public function changeCollaboratorPassword(){
        if(isset($_POST['cbf_token']) && (isset($_POST['cbf_password']) || $_POST['cbf_has_account'] == 1) && isset($_POST['cbf_collaborator_id']) && $_POST['cbf_email'] && $_POST['cbf_owner_id']){

            if($_POST['cbf_has_account'] == 0){
	            wp_set_password( $_POST['cbf_password'], $_POST['cbf_collaborator_id'] );
            }

            $user = get_user_by('email', $_POST['cbf_email'] );
            $owner = get_user_by('id', $_POST['cbf_owner_id'] );

            if ( !is_wp_error( $user ) ){

	            updateCollaboratorUserInvitation($_POST['cbf_owner_id'],$_POST['cbf_collaborator_id'], 'Accepted');

	            //Set the new collaborator account as default
	            $user_id = $_POST['cbf_owner_id'];
	            $email = $owner->user_email;
	            $username = $owner->user_login;
	            $collaborator_id = $_POST['cbf_collaborator_id'];
	            $account_type = 'collaborator';

				//Check if the account's owner is premium
	            $customer = rcp_get_customer_by_user_id($_POST['cbf_owner_id']);
	            $premium = false;
	            if ($customer) {
		            $memberships = $customer->get_memberships();
		            $premium = $memberships[0]->get_gateway() == 'free' || $memberships[0]->get_status() == 'cancelled' ? false : true;
	            }

	            $account_selected = array(
		            'id' => $user_id,
		            'email' => $email,
		            'account_type' => $account_type,
		            'collaborator_id' => $collaborator_id,
		            'username' => $username,
		            'premium' => $premium,
	            );

	            update_user_meta($collaborator_id,'account_selected',serialize($account_selected) );

                wp_clear_auth_cookie();
                wp_set_current_user ( $user->ID );
                wp_set_auth_cookie  ( $user->ID );

                $redirect_to = get_option('siteurl') . '/welcome';
                wp_safe_redirect( $redirect_to );
                exit();
            }

        }else{
            echo "We can't accept your invitation at this moment, please contact support.";
            die();
        }
    }

	/**
	 * Receive the $_POST data to change the collaborator password and logged in
	 */
	public function saveHubspotSettings(){
		$key = $_POST['cbf_hubspot_key'];

		if(isset($key)){
			update_option('cbf_hubspot_premium_list', $_POST['cbf_hubspot_premium_list']  != -1 ? $_POST['cbf_hubspot_premium_list'] : false);
			update_option('cbf_hubspot_free_list', $_POST['cbf_hubspot_free_list'] != -1 ? $_POST['cbf_hubspot_free_list'] : false);
			update_option('cbf_hubspot_key', $key);

			$redirect_to = $_POST['redirect_to'];
			wp_safe_redirect( $redirect_to );
			exit();
		}else{
			echo "We could not connect with Hubspot, please check the credentials";
			die();
		}
	}


}