<?php

/*
*
* @package Yariko
*
*/

namespace Cbf\Inc\Base;

class Ajax{

    public function register(){

        /**
         * All ajax actions
         */
        add_action( 'wp_ajax_add_recipe', array($this, 'addRecipe') );
        add_action( 'wp_ajax_nopriv_add_recipe', array($this, 'addRecipe') );
        add_action( 'wp_ajax_add_photo', array($this, 'AddPhoto') );
        add_action( 'wp_ajax_nopriv_photo', array($this, 'AddPhoto') );

        add_action( 'wp_ajax_get_recipe_categories', array($this, 'getRecipeCategories') );
        add_action( 'wp_ajax_nopriv_get_recipe_categories', array($this, 'getRecipeCategories') );

        add_action( 'wp_ajax_get_your_recipes', array($this, 'getYourRecipes') );
        add_action( 'wp_ajax_nopriv_get_your_recipes', array($this, 'getYourRecipes') );

        add_action( 'wp_ajax_get_recipe', array($this, 'getRecipe') );
        add_action( 'wp_ajax_nopriv_get_recipe', array($this, 'getRecipe') );

        add_action( 'wp_ajax_add_cookbook', array($this, 'addCookbook') );
        add_action( 'wp_ajax_nopriv_add_cookbook', array($this, 'addCookbook') );

        add_action( 'wp_ajax_get_user_cookbooks', array($this, 'getUserCookbook') );
        add_action( 'wp_ajax_nopriv_get_user_cookbooks', array($this, 'getUserCookbook') );
    }

    /**
     * Get user CookBook
     */
    public function getUserCookbook(){
        $author_id = $_POST['author_id'];
        $cookbooks = [];

        if(intval($author_id) > 0){
            $cookbooks = get_posts(array(
                'post_type' => 'cookbook',
                'author' => intval($author_id),
                'post_status' => array('publish', 'draft')
            ));
        }

        echo json_encode(array('success'=> 'true', 'cookbooks' => $cookbooks));
        wp_die();
    }

    /**
     * Get Recipe Categories
     */
    public function getRecipeCategories(){
        $terms = get_terms( array(
            'taxonomy' => 'cat_recipe',
            'hide_empty' => false,
        ) );

        echo json_encode(array('success'=> 'true', 'categories' => $terms));
        wp_die();
    }

    /**
     * Add a Recipe
     */
    public function addRecipe(){

        $cookbooks_ids = !empty($_POST['cookbook_ids']) ? explode(',',$_POST['cookbook_ids']) : [];
        $ingredients = json_decode(str_replace("\\","",$_POST['ingredients']));
        $photos = json_decode(str_replace("\\","",$_POST['photos']));
        $title = $_POST['title'];
        $category = json_decode(str_replace("\\","",$_POST['category']));
        $instructions = str_replace('\\','',$_POST['instructions']);
        $author_id = $_POST['author_id'];
        $status = $_POST['status'];
        $post_id = $_POST['edit'] > 0 ? intval($_POST['edit'] ): -1;
        $cookbook_id = $_POST['cookbook_id'];

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

            /**
             * Creating the recipe relation with a cookbook
             */
            if(count($cookbooks_ids) > 0){
                insertCookbooksToRecipe($cookbooks_ids, $post_id);
            }

            echo json_encode(array('success'=> true, 'msg' => 'Recipe inserted successfully', 'id' => $post_id));
            wp_die();
        }else{
            echo json_encode(array('success'=> 'false', 'msg' => 'The Recipe could not be inserted'));
            wp_die();
        }
    }

    /**
     * Add a cookbook instance(Cookbook = CPT)
     */
    public function addCookbook(){
        $title = $_POST['title'];
        $acknowledgments = $_POST['acknowledgments'];
        $introduction = $_POST['introduction'];
        $dedications = $_POST['dedications'];
        $front = $_POST['front'];
        $back = $_POST['back'];
        $author_id = $_POST['author_id'];
        $recipes = $_POST['recipes'];

        $recipes = explode(',', $recipes);

        $post_id = $_POST['edit'] > 0 ? intval($_POST['edit'] ) : -1;

        // var_dump($_POST);exit;

        if($post_id == -1){
            $post_id  = wp_insert_post( array(
                    'post_title'    => $title ,
                    'post_content'  => '',
                    'post_status'   => 'publish',
                    'post_type'   => 'cookbook',
                    'post_author'   => $author_id,

                )
            );

        }else{
            wp_update_post( array(
                'ID' => $post_id,
                'post_title'    => $title,
            ) );

        }

        $back =  $back > 0 ? update_field( 'back_cover_image', $back,$post_id) : '';
        $front =  $front > 0 ? update_field( 'front_cover_image', $front,$post_id) : '';

        //Updating the ACF related to the new/updated cookbook
        update_field( 'dedication', $dedications,$post_id);
        update_field( 'introduction', $introduction,$post_id);
        update_field( 'acknowledgments', $acknowledgments,$post_id);

        //update_field('recipes', $recipes, $post_id);
        insertRecipeCookBook($post_id,$recipes);

        echo json_encode(array('success'=> 'false', 'msg' => 'The Cookbook could not be inserted'));
        wp_die();
    }

    /**
     * Get a recipe by ID
     */
    public function getRecipe(){
        $id = $_POST['id'];

        $cookbooks = getCookbooksFromRecipeId($id);

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
        $recipe->post_status  = ucfirst($recipe->post_status);

        $term_obj_list = get_the_terms( $id, 'cat_recipe' );

        $recipe->category = $term_obj_list[0]->term_id;
        $recipe->category_name = $term_obj_list[0]->name;
        $recipe->cookbooks  = $cookbooks;

        if($recipe){
            echo json_encode(array('success'=> 'true', 'recipe' => $recipe));
            wp_die();
        }

        echo json_encode(array('success'=> 'false', 'msg' => 'The Recipe could not be fetched'));
        wp_die();

    }

    /**
     * Add Photo
     */
    public function AddPhoto(){
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
                'post_status' => array('publish', 'draft','private')

            ));

            $real_recipes = [];

            foreach ($recipes as $recipe){
                //  if(intval($recipe->post_author) !== intval($author_id)) continue;
                $photos = get_field('cbf_photos', $recipe->ID);
                if(count($photos) > 0){
                    $recipe->photo_url = $photos[0]['image']['url'];
                }else{
                    $recipe->photo_url = '/wp-content/uploads/2021/11/default.jpg';
                }
            }
        }

        echo json_encode(array('success'=> 'true', 'recipes' => $recipes));
        wp_die();
    }

}