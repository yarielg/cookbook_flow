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

        add_action( 'wp_ajax_get_cookbook', array($this, 'getCookbookById') );
        add_action( 'wp_ajax_nopriv_get_cookbook', array($this, 'getCookbookById') );

        add_action( 'wp_ajax_add_cookbook', array($this, 'addCookbook') );
        add_action( 'wp_ajax_nopriv_add_cookbook', array($this, 'addCookbook') );

        add_action( 'wp_ajax_get_user_cookbooks', array($this, 'getUserCookbooks') );
        add_action( 'wp_ajax_nopriv_get_user_cookbooks', array($this, 'getUserCookbooks') );

        add_action( 'wp_ajax_add_collaborator', array($this, 'addCollaborator') );
        add_action( 'wp_ajax_nopriv_add_collaborator', array($this, 'addCollaborator') );

        add_action( 'wp_ajax_get_collaborators', array($this, 'getCollaborators') );
        add_action( 'wp_ajax_nopriv_get_collaborators', array($this, 'getCollaborators') );

        add_action( 'wp_ajax_remove_collaborator', array($this, 'removeCollaborator') );
        add_action( 'wp_ajax_nopriv_remove_collaborator', array($this, 'removeCollaborator') );
    }

    /**
     * Get user CookBook
     */
    public function getUserCookbooks(){
        $author_id = $_POST['author_id'];

        $cookbooks = getUserCookbook($author_id);

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

        $cookbooks_ids = !empty($_POST['cookbooks_ids']) ? explode(',',$_POST['cookbooks_ids']) : [];
        $ingredients = json_decode(str_replace("\\","",$_POST['ingredients']));
        $photos = json_decode(str_replace("\\","",$_POST['photos']));
        $title = $_POST['title'];
        $category = $_POST['category'];
        $instructions = str_replace('\\','',$_POST['instructions']);
        $story = str_replace('\\','',$_POST['story']);
        $author_id = $_POST['author_id'];
        $status = strtolower($_POST['status']);
        $post_id = $_POST['edit'] > 0 ? intval($_POST['edit'] ): -1;

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
            if($category != -1){
                $category_inserted = wp_set_object_terms( $post_id, intval($category), 'cat_recipe' );
                if(!$category_inserted){
                    echo json_encode(array('success'=> 'false', 'msg' => 'There was an error when inserting the recipe category'));

                    wp_die();
                }

            }

            /**
             * Adding/Updating the ingredients to ACF
             */
            if(count($ingredients) > 0){
                $ingredients_normalized = cbf_normalize_ingredients($ingredients);
                // var_dump(update_field( 'cbf_ingredients', $ingredients_normalized,$post_id));exit;
                update_field( 'cbf_ingredients', [],$post_id);
                if(!update_field( 'cbf_ingredients', $ingredients_normalized,$post_id)){
                    echo json_encode(array('success'=> 'false', 'msg' => 'The Recipe could not be inserted, error inserting ingredients'));
                    //   wp_die();
                }

            }

            /**
             * Adding/Updating the photos to ACF
             */
            if(count($photos) > 0){
                $photos = cbf_normalize_photos($photos);

                update_field( 'cbf_photos', [],$post_id);

                if(!update_field( 'cbf_photos', $photos,$post_id)){
                    echo json_encode(array('success'=> 'false', 'msg' => 'The Recipe could not be inserted, error inserting photos'));
                    wp_die();
                }
            }

            /**
             * Creating the recipe relation with a cookbook
             */
            if(count($cookbooks_ids) > 0){
                insertCookbooksToRecipe($cookbooks_ids, $post_id);
            }

            /**
             * Add Story
             */
            update_field( 'story', $story, $post_id);

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

        $cookbooks_recipe = getCookbooksFromRecipeId($id);
        $cookbooks_ids = [];

        foreach ($cookbooks_recipe as $cookbook_recipe){
            $cookbooks_ids[] = $cookbook_recipe['ID'];
        }

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

        $recipe->story = get_field('story', $id);

        $recipe->ingredients = $ingredients;
        $recipe->post_status  = ucfirst($recipe->post_status);

        $term_obj_list = get_the_terms( $id, 'cat_recipe' );

        $recipe->category = $term_obj_list ? $term_obj_list[0]->term_id : -1;
        $recipe->category_name = $term_obj_list  ? $term_obj_list[0]->name : '';
        $recipe->cookbooks_ids = $cookbooks_ids;
        $recipe->cookbooks_selected = getCookbooksFromRecipeId($id);

        if($recipe){
            echo json_encode(array('success'=> 'true', 'recipe' => $recipe));
            wp_die();
        }

        echo json_encode(array('success'=> 'false', 'msg' => 'The Recipe could not be fetched'));
        wp_die();

    }

    /**
     * Get a cookbook by ID
     */
    public function getCookbookById(){
        $id = $_POST['id'];

        if(empty($id)){
            echo json_encode(array('success'=> 'false', 'msg' => 'We could not get the cookbook without a valid id.'));
            wp_die();
        }

        $cookbook = get_post($id);

        $cookbook->front_cover_image = get_field( 'front_cover_image',$id );
        $cookbook->dedication = get_field( 'dedication',$id );
        $cookbook->introduction = get_field( 'introduction',$id );
        $cookbook->back_cover_image = get_field( 'back_cover_image',$id );
        $cookbook->recipes = get_field( 'recipes',$id );

        echo json_encode(array('success'=> 'true', 'cookbook' => $cookbook));
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
            echo json_encode(array('success'=> false, 'msg' => 'The Photo could not be inserted'));
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
                'numberposts' => -1,
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

        echo json_encode(array('success'=> true, 'recipes' => $recipes));
        wp_die();
    }

    /**
     * Add collaborator (sent invitation link by email)
     */
    function addCollaborator(){
        $first = $_POST['first'];
        $last = $_POST['last'];
        $email = $_POST['email'];
        $author_id = $_POST['author_id'];
        $password = cbf_generate_string(12);
        $token = cbf_generate_string(22);

        if(email_exists($email)){
            echo json_encode(array('success'=> false, 'msg' => 'There is a collaborator with the same email'));
            wp_die();
        }

        $user_id = wp_create_user( $email, $password, $email );
        $user = get_user_by( 'id', $user_id );

        update_user_meta($user_id,'first_name',$first);
        update_user_meta($user_id,'last_name',$last);
        update_user_meta($user_id,'invitation_status','Sent');

        insertCollaboratorUser($author_id, $user_id, $token);

        $user->add_role( 'cbf_collaborator' );
        $user->remove_role( 'subscriber' );

        $link = get_option('siteurl') . '/collaborator-sign-up?token=' . $token . '&email='.$email . '&first=' . $first . '&last=' . $last . '&collaborator_id=' . $user_id;

        sendCollaboratorInvitation($email,array('link' => 'https://google.com', 'first' => $first, 'link'=> $link));

        $collaborator = array('first' => $first, 'last' => $last, 'email' => $email, 'token' => $token, 'ID' => $user_id, 'status' => 'Sent');

        echo json_encode(array('success'=> true , 'collaborator' => $collaborator ));
        wp_die();


    }

    /**
     * Get all the collaborators by owner id
     */
    public function getCollaborators(){

        $user_id = $_POST['user_id'];

        $collaborators = getCollaboratorsByOwnerId($user_id);

        echo json_encode(array('success'=> true , 'collaborators' => $collaborators));
        wp_die();
    }

    /**
     * Remove collaboration, action can only be trigger by the owner account
     */
    public function removeCollaborator(){
        $id = $_POST['collaborator_id'];

        if(empty($id)){
            echo json_encode(array('success'=> false , 'msg' => 'There was am error removing the collaborator, collaborator id was not provided'));
            wp_die();
        }

        if(!wp_delete_user( $id )){
            echo json_encode(array('success'=> false , 'msg' => 'There user could not be deleted'));
            wp_die();
        }

        echo json_encode(array('success'=> true , 'msg' => 'Collaborator deleted'));
        wp_die();

    }
}