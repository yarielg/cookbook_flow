<?php

/*
*
* @package Yariko
*
*/

namespace Cbf\Inc\Base;


class Checkout{


    public function register(){


        /**
         * Validate the fields when placing an order
         */
        add_action('woocommerce_checkout_process', array($this,'validate_fields'));

        /**
         * Save the cookbook fields value as order metas
         */
        add_action( 'woocommerce_checkout_update_order_meta', array($this, 'update_cookbook_fields_order_metas') );

        /**
        * Display Cookbook info on admin order single
        */
        add_action( 'woocommerce_admin_order_data_after_order_details', array($this, 'show_cookbook_info') );

    }

    function show_cookbook_info( $order ){
        $cookbook_id = get_post_meta( $order->get_id(), 'cbf_cookbook_id', true );
        $option = get_post_meta( $order->get_id(), 'cbf_option_type', true );
        $template_id = get_post_meta( $order->get_id(), 'cbf_template', true );

        $cookbook_image =  get_field( 'front_cover_image',$cookbook_id ) ? get_field( 'front_cover_image',$cookbook_id )['sizes']['thumbnail'] : null;

        if($cookbook_id > 0){
            $cookbook = get_post($cookbook_id);
            echo '<div class="order_data_column cookbook_info">';
            echo '<h3>Cookbook Info</h3><br>';
            echo '<div style="background-image: url('.$cookbook_image.');width: 100px;height: 100px;background-size: cover"></div>';
            echo '<p>Name: <strong><a target="_blank" href="' .site_url() . '/wp-admin/post.php?post='.$cookbook_id.'&action=edit">' . $cookbook->post_title . '</a></strong></p>';
            echo '</div>';
        }

        if($option == 1 && $template_id > 0){
            $template = getTemplateACFByID($template_id);

            echo '<div style="margin-left: 30px;" class="order_data_column cookbook_info">';
            echo "<h3>Template Info</h3><br>";
            echo '<div style="background-image: url('.$template['url'].');width: 100px;height: 100px;background-size: cover"></div>';
            echo '<p>Name: <strong> ' . $template['name'] . '</strong></p>';
            echo "</div>";
        }


    }

    function update_cookbook_fields_order_metas($order_id ){
        if(isset($_POST['option_type'])){
            update_post_meta( $order_id, 'cbf_option_type', sanitize_text_field( $_POST['option_type'] ) );
        }

        if(isset($_POST['cookbook_id'])){
            update_post_meta( $order_id, 'cbf_cookbook_id', sanitize_text_field( $_POST['cookbook_id'] ) );
            /**
             * Define the published CBF_SENT (2) state for the cookbook
             */
            update_field( 'state', CBF_SENT ,$_POST['cookbook_id']);
        }

        if ( isset($_POST['template'])) {
            update_post_meta( $order_id, 'cbf_template', sanitize_text_field( $_POST['template'] ) );
        }

    }

    function validate_fields($fields){
        $option_type = sanitize_text_field($_POST['option_type']);
        $cookbook_id = sanitize_text_field($_POST['cookbook_id']);
        $template = sanitize_text_field($_POST['template']);

        if(empty($cookbook_id)){
            wc_add_notice(__("We can't place an order without a cookbook, please repeat the process"), 'error');
        }

        if(empty($template) && intval($option_type) == 1){
            wc_add_notice(__("Choose a template to publish the cookbook selected"), 'error');
        }
    }
}
?>