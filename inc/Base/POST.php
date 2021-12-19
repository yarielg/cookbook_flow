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

    }

    /**
     * Receive the $_POST data to change the collaborator password and logged in
     */
    public function changeCollaboratorPassword(){
        if(isset($_POST['cbf_token']) && isset($_POST['cbf_password']) && isset($_POST['cbf_collaborator_id']) && $_POST['cbf_email']){

            wp_set_password( $_POST['cbf_password'], $_POST['cbf_collaborator_id'] );

            $user = get_user_by('email', $_POST['cbf_email'] );



            if ( !is_wp_error( $user ) ){

                update_user_meta($user->ID,'invitation_status','Accepted');
                wp_clear_auth_cookie();
                wp_set_current_user ( $user->ID );
                wp_set_auth_cookie  ( $user->ID );

                $redirect_to = get_option('siteurl') . '/welcome';
                wp_safe_redirect( $redirect_to );
                exit();
            }

        }else{
            echo "We can accept your invitation at this moment, please contact support.";
            die();
        }
    }


}