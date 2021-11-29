<?php


namespace Cbf\Inc\Base;


class Shortcodes
{
    public function register(){
        add_shortcode( 'cbf_login', array($this, 'login') );
        add_shortcode( 'acf_example', array($this, 'acf_example') );
        add_shortcode( 'dashboard', array($this, 'dashboard') );

        add_action( 'wp_ajax_add_recipe', array($this, 'addRecipe') );
        add_action( 'wp_ajax_nopriv_add_recipe', array($this, 'addRecipe') );
        add_action( 'wp_ajax_add_recipe_photo', array($this, 'AddPhotoRecipe') );
        add_action( 'wp_ajax_nopriv_add_recipe_photo', array($this, 'AddPhotoRecipe') );

        add_action( 'wp_ajax_get_recipe_categories', array($this, 'getRecipeCategories') );
        add_action( 'wp_ajax_nopriv_get_recipe_categories', array($this, 'getRecipeCategories') );

        add_action( 'wp_ajax_get_your_recipes', array($this, 'getYourRecipes') );
        add_action( 'wp_ajax_nopriv_get_your_recipes', array($this, 'getYourRecipes') );

        add_action( 'wp_ajax_get_recipe', array($this, 'getRecipe') );
        add_action( 'wp_ajax_nopriv_get_recipe', array($this, 'getRecipe') );
    }

    public function getRecipeCategories(){
        $terms = get_terms( array(
            'taxonomy' => 'cat_recipe',
            'hide_empty' => false,
        ) );

        echo json_encode(array('success'=> 'true', 'categories' => $terms));
        wp_die();
    }

    public function addRecipe(){
        $ingredients = json_decode(str_replace("\\","",$_POST['ingredients']));
        $photos = json_decode(str_replace("\\","",$_POST['photos']));
        $title = $_POST['title'];
        $category = json_decode(str_replace("\\","",$_POST['category']));
        $instructions = str_replace('\\','',$_POST['instructions']);
        $author_id = $_POST['author_id'];
        $status = $_POST['status'];
        $post_id = $_POST['edit'] > 0 ? intval($_POST['edit'] ): -1 ;

        if($post_id == -1){
            $post_id  = wp_insert_post( array(
                    'post_title'    => $title ,
                    'post_content'  => $instructions,
                    'post_status'   => $status,
                    'post_type'   => 'recipe',
                    'post_author'   => $author_id,

                )
            );

        }else{
           wp_update_post( array(
                'ID' => $post_id,
                'post_title'    => $title ,
                'post_content'  => $instructions,
                'post_status'   => $status,
            ) );

        }

        if($post_id != 0){

            //Saving category
            $category_inserted = wp_set_object_terms( $post_id, $category, 'cat_recipe' );

            if(!$category_inserted){
                echo json_encode(array('success'=> 'false', 'msg' => 'There was an error when inserting the recipe category'));
                wp_die();
            }

            /**
             * Adding/Updating the ingredients to ACF
             */
            $ingredients_normalized = cbf_normalize_ingredients($ingredients);
           // var_dump(update_field( 'cbf_ingredients', $ingredients_normalized,$post_id));exit;
            update_field( 'cbf_ingredients', [],$post_id);
            if(!update_field( 'cbf_ingredients', $ingredients_normalized,$post_id)){
                echo json_encode(array('success'=> 'false', 'msg' => 'The Recipe could not be inserted, error inserting ingredients'));
             //   wp_die();
            }

            //var_dump($post_id);exit;

            /**
             * Adding/Updating the photos to ACF
             */

            $photos = cbf_normalize_photos($photos);

            update_field( 'cbf_photos', [],$post_id);

            if(!update_field( 'cbf_photos', $photos,$post_id)){
                echo json_encode(array('success'=> 'false', 'msg' => 'The Recipe could not be inserted, error inserting photos'));
                wp_die();
            }

            echo json_encode(array('success'=> true, 'msg' => 'Recipe inserted successfully'));
            wp_die();
        }else{
            echo json_encode(array('success'=> 'false', 'msg' => 'The Recipe could not be inserted'));
            wp_die();
        }
    }

    public function getRecipe(){
        $id = $_POST['id'];

        $recipe = get_post($id);

        $images = get_field( 'cbf_photos',$id );
        $photos = [];
        foreach ($images as $image){
            $photos[] = [
                "id" => $image['image']['id'],
                "url" => $image['image']['url'],
            ];
        }

        $recipe->photos = $photos;

        $ingredients_wo_key = get_field( 'cbf_ingredients',$id );
        $ingredients = [];
        $cont = 1;
        foreach ($ingredients_wo_key as $ingredient){
            $ingredients[] = [
                'key' => $cont++,
                'name' => $ingredient['name'],
                'quantity' => $ingredient['quantity'],
                'unit' => $ingredient['unit'],
            ];
        }

        $recipe->ingredients = $ingredients;

        $term_obj_list = get_the_terms( $id, 'cat_recipe' );

        $recipe->category = $term_obj_list[0]->term_id;

        if($recipe){
            echo json_encode(array('success'=> 'true', 'recipe' => $recipe));
            wp_die();
        }

        echo json_encode(array('success'=> 'false', 'msg' => 'The Recipe could not be fetched'));
        wp_die();

    }

    /**
     * Remove photo when adding/editing recipes
     */
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

    /**
     * Get the user recipes
     */
    public function getYourRecipes(){

        $author_id = $_POST['author_id'];
        $recipes = [];

        if(intval($author_id) > 0){
            $recipes = get_posts(array(
                'post_type' => 'recipe',
                'author' => intval($author_id),
                'post_status' => array('publish', 'draft')

            ));

            $real_recipes = [];

            foreach ($recipes as $recipe){
              //  if(intval($recipe->post_author) !== intval($author_id)) continue;
                $photos = get_field('cbf_photos', $recipe->ID);
                if(count($photos) > 0){
                    $recipe->photo_url = $photos[0]['image']['url'];
                }else{
                    $recipe->photo_url = 'https://cookbook.nextsitehosting.com/wp-content/uploads/2021/11/default.jpg';
                }
            }
        }

        echo json_encode(array('success'=> 'true', 'recipes' => $recipes));
        wp_die();
}

    public function dashboard(){
        return "<div id='vwp-plugin'></div>";
    }

    public function login($atts){
        $output = memd_template(CBF_PLUGIN_PATH . 'templates/login.php' , array());
        return $output;
    }


}