<?php


namespace Cbf\Inc\Base;


class Shortcodes
{
    public function register(){
        add_shortcode( 'cbf_login', array($this, 'login') );
        add_shortcode( 'acf_example', array($this, 'acf_example') );
        add_shortcode( 'dashboard', array($this, 'dashboard') );

        add_action( 'wp_ajax_add_recipe', array($this, 'addRecipe') );
        add_action( 'wp_ajax_add_recipe_photo', array($this, 'AddPhotoRecipe') );
    }

    public function addRecipe(){
        $ingredients = json_decode(str_replace("\\","",$_POST['ingredients']));
        $photos = json_decode(str_replace("\\","",$_POST['photos']));
        $title = $_POST['title'];
        $category = $_POST['category'];
        $instructions = $_POST['instructions'];
        $author_id = $_POST['author_id'];

        $post_id  = wp_insert_post( array(
              'post_title'    => $title ,
              'post_content'  => $instructions,
              'post_status'   => 'publish',
              'post_type'   => 'recipe',
              'post_author'   => $author_id,
          )
        );

        if($post_id != 0){
            /**
             * Adding the ingredients to ACF
             */
            $ingredients = cbf_normalize_ingredients($ingredients);

            $existing = get_field( 'cbf_ingredients',$post_id );
            if ( ! is_array($existing) ) $existing = [];
            $updated = array_merge($ingredients, $existing);

            if(!update_field( 'cbf_ingredients', $updated,$post_id)){
                echo json_encode(array('success'=> 'false', 'msg' => 'The Recipe could not be inserted, error inserting ingredients'));
                wp_die();
            }

            /**
             * Adding the photos to ACF
             */
            $photos = cbf_normalize_photos($photos);

            $existing = get_field( 'cbf_photos',$post_id );
            if ( ! is_array($existing) ) $existing = [];
            $updated = array_merge($photos, $existing);

            if(!update_field( 'cbf_photos', $updated,$post_id)){
                echo json_encode(array('success'=> 'false', 'msg' => 'The Recipe could not be inserted, error inserting ingredients'));
                wp_die();
            }

            echo json_encode(array('success'=> true, 'msg' => 'Recipe inserted successfully'));
            wp_die();
        }else{
            echo json_encode(array('success'=> 'false', 'msg' => 'The Recipe could not be inserted'));
            wp_die();
        }
    }

    public function AddPhotoRecipe(){
        $photo_id = cbf_upload_file($_FILES['image']);
        if($photo_id > 0){
            echo json_encode(array('success'=> true, 'msg' => 'Photo inserted successfully', 'photo_id' => $photo_id));
            wp_die();
        }else{
            echo json_encode(array('success'=> 'false', 'msg' => 'The Photo could not be inserted'));
            wp_die();
        }
    }

    public function dashboard(){
        return "<div id='vwp-plugin'></div>";
    }

    public function login($atts){
        $output = memd_template(CBF_PLUGIN_PATH . 'templates/login.php' , array());
        return $output;
    }

    public function acf_example(){
        $row = [
            [
                'name' => 'UU',
                'quantity'   => '2',
                'unit'  => 'oz'
            ],[
                'name' => 'YYUUUY',
                'quantity'   => '2',
                'unit'  => 'oz'
            ]
        ];

        $existing = get_field( 'cbf_ingredients',50 );
        if ( ! is_array($existing) ) $existing = [];
        $updated = array_merge($row, $existing);

        update_field( 'cbf_ingredients', $updated,50);
        return 'Yeah';
    }

}