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

        /**
         * Display zip file in single order
         */
        //add_action( 'woocommerce_admin_order_data_after_order_details', array($this, 'show_cookbook_zip') );

        add_action( 'add_meta_boxes', array($this,'generate_xml_files') );

        //Chat
        add_action( 'add_meta_boxes', array($this,'chat') );

    }

    function chat($checkout){
	    add_meta_box( 'chat_order_cookbook', __('Chat about the cookbook in this order','woocommerce'), array($this,'add_chat'), 'shop_order', 'normal', 'core' );
    }

	function add_chat($post){
		$order_id = $post->ID;
		$cookbook_id = get_post_meta( $order_id, 'cbf_cookbook_id', true );

		$comments = getCookbookComments($cookbook_id);
		?>
        <div class="chat_main">
            <div class="chat_canvas">
                <?php
                if(count($comments) > 0){
	                foreach ($comments as $comment){
		                // var_dump(date('Y-m-d h:i', strtotime($comment['created'])));exit;

		                $position_class = $comment['admin'] == 1 ? 'right' : 'left';
		                echo "<p class='cbf-comment ". $position_class ."'>" . $comment['comment'] ."<span class='time'>" . date('Y-m-d h:i', strtotime($comment['created'])) . "</span></p>";
	                }
                }else{
                    echo '<p class="no-comments">There is not message. Start communicating with the cookbook author</p>';
                }
                ?>
            </div>
            <textarea placeholder="Write a message..." name="" id="cbf_message_value" cols="79" rows="2"></textarea>
            <br>
            <button type="button" data-admin="1" data-cookbook_id="<?php echo $cookbook_id ?>" id="cookbook_send_comment">Sent</button>
        </div>
        <style>
            .chat_canvas{
                height: 300px;
                width: 500px;
                background: white;
                padding: 20px;
                display: flex;
                flex-direction: column;
                border-radius: 8px;
                border: 1px solid black;
                overflow-y: scroll;
            }
            .no-comments{
                text-align: center;
                font-weight: 500;
                margin: auto;
            }
            .cbf-comment{
                background: #51A351;
                padding: 8px;
                border-radius: 4px;
                color: white;
                display: block;
                clear: bottom;
                width: fit-content;
                margin: 2px;
            }
            .cbf-comment.right{
                float: right;
                background: black;
                align-self: end;
            }
            .cbf-comment > span.time{
                font-size: 8px;
                margin-left: 12px;
            }
        </style>
		<?php
	}

    function generate_xml_files( $checkout ) {
        add_meta_box( 'generate_xml_files', __('Cookbook Data ','woocommerce'), array($this,'add_file_markup'), 'shop_order', 'side', 'core' );
    }

    function add_file_markup($post){
        $order_id = $post->ID;
        $cookbook_id = get_post_meta( $order_id, 'cbf_cookbook_id', true );

        if($cookbook_id){
            if(get_post_meta($order_id,'zip_file_generated', true)){
                ?>
                <a href="<?php echo get_post_meta($order_id,'zip_file_url', true); ?>">Download file</a><br><br>
                <?php
            }
            ?>
            <button type="button" data-order_id="<?php echo $order_id ?>" data-cookbook_id="<?php echo $cookbook_id ?>" id="btn_generate_cookbook_file">Generate</button>
            <?php
        }else{
            echo 'There is not a cookbook related with this order';
        }

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