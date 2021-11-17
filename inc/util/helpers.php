<?php
 function memd_generate_rand_id(){
     return substr(md5(rand()),0,15);
 }

 function memd_format_date($date){
     return date( 'Y-m-d h:i:s ',strtotime($date));
 }

function memd_template( $file, $args ){
    // ensure the file exists
    if ( !file_exists( $file ) ) {
        return '';
    }

    // Make values in the associative array easier to access by extracting them
    if ( is_array( $args ) ){
        extract( $args );
    }

    // buffer the output (including the file is "output")
    ob_start();
    include $file;
    return ob_get_clean();
}

/**
 * @param $member_external_id
 * @return boolean
 * @description Return true is a member is a dependent
 */

function memd_is_dependent($member_external_id, $memdInstanceService){
    $flag = false;
    $member = $memdInstanceService->getMember($member_external_id);
    if(isset($member)  && $member['externalsubcriberid'] != '' ){
            $flag = true;
    }
    return $flag;
}

function memd_send_password_reset_mail($user_id){

    $user = get_user_by('id', $user_id);
    $firstname = $user->first_name;
    $email = $user->user_email;
    $adt_rp_key = get_password_reset_key( $user );
    $user_login = $user->user_login;
    $rp_link = '<a href="' . wp_login_url()."/resetpass/?key=$adt_rp_key&login=" . rawurlencode($user_login) . '">' . wp_login_url()."/resetpass/?key=$adt_rp_key&login=" . rawurlencode($user_login) . '</a>';

    if ($firstname == "") $firstname = "There";
    $message = "Hi ".$firstname.",<br>";
    $message .= "Congratulations, you just turned 18, now you can access your account by yourself in ".get_bloginfo( 'name' ).", this is your username address ".$user_login."<br>";
    $message .= "Click here to set the password for your account: <br>";
    $message .= $rp_link.'<br>';

    $subject = __("Your account on ".get_bloginfo( 'name'));
    $headers = array();

    add_filter( 'wp_mail_content_type', function( $content_type ) {return 'text/html';});
    $headers[] = 'From: Your company name brjosue73@gmail.com'."\r\n";
    wp_mail( $email, $subject, $message, $headers);

    // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
    remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

}