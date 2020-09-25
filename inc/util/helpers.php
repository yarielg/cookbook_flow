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