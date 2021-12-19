<?php

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

function cbf_normalize_ingredients($ingredients){
     $normalized_ingredients = [];
     foreach ($ingredients as $ingredient){
         if($ingredient->name){
             $normalized_ingredients[] = [
                 'name' => $ingredient->name,
                 'quantity'   => $ingredient->quantity,
                 'unit'  => $ingredient->unit
             ];
         }
     }
     return $normalized_ingredients;
}

function cbf_normalize_categories($category){
        $normalized_categories[] = [
            'term_id' => $category->term_id,
            'term_taxonomy_id'  => $category->term_taxonomy_id,
            'name'   => $category->name,
            'slug'  => $category->slug,
            'description'  => $category->description,
            'parent'  => $category->parent,
            'count'  => $category->count,
            'filter'  => $category->filter,
            'term_group'  => $category->term_group,
        ];
    return $normalized_categories;
}

function cbf_normalize_photos($photos){
    $normalized_photos = [];
    foreach ($photos as $photo){
        $normalized_photos[] = [
            'image' => $photo->id,
        ];
    }
    return $normalized_photos;
}

function cbf_upload_file( $file, $post_id = 0, $desc = null ) {
    if( empty( $file['name'] ) ) {
        return new \WP_Error( 'error', 'File is empty' );
    }

    // Get filename and store it into $file_array
    preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );

    // If error storing temporarily, return the error.
    if ( is_wp_error( $file['tmp_name'] ) ) {
        return new \WP_Error( 'error', 'Error while storing file temporarily' );
    }

    // Store and validate
    $id = media_handle_sideload( $file, $post_id, $desc );

    // Unlink if couldn't store permanently
    if ( is_wp_error( $id ) ) {
        unlink( $file['tmp_name'] );
        return new \WP_Error( 'error', "Couldn't store upload permanently" );
    }

    if ( empty( $id ) ) {
        return new \WP_Error( 'error', "Upload ID is empty" );
    }

    return $id;
}

function insertRecipeCookBook($cookbook_id, $recipes){
    global $wpdb;

    foreach ($recipes as $recipe_id){
        $wpdb->query("INSERT INTO $wpdb->prefix" . "cbf_recipes_cookbooks (recipe_id,cookbook_id) VALUES ('$recipe_id','$cookbook_id')");
    }

}

function deleteAllRecipeCookBooksByRecipeID($id){
    global $wpdb;

    $wpdb->query("DELETE FROM $wpdb->prefix" . "cbf_recipes_cookbooks WHERE recipe_id='$id'");
}

function insertCookbooksToRecipe($cookbooks, $recipe_id){
    global $wpdb;

    deleteAllRecipeCookBooksByRecipeID($recipe_id);

    foreach ($cookbooks as $cookbook_id){
        $wpdb->query("INSERT INTO $wpdb->prefix" . "cbf_recipes_cookbooks (recipe_id,cookbook_id) VALUES ('$recipe_id','$cookbook_id')");
    }

}

function insertCollaboratorUser($user_id,$collaborator_id,$token){
    global $wpdb;

    $wpdb->query("INSERT INTO $wpdb->prefix" . "cbf_users_collaborators (user_id,collaborator_id,token) VALUES ('$user_id','$collaborator_id','$token')");

}

function getCookbooksFromRecipeId($id){
    global $wpdb;

    $cookbook = $wpdb->get_results("SELECT P.post_title,P.ID FROM $wpdb->prefix" . "cbf_recipes_cookbooks RC INNER JOIN $wpdb->prefix" . "posts P ON RC.cookbook_id=P.ID WHERE RC.recipe_id='$id'", ARRAY_A);

    return count($cookbook) > 0 ? $cookbook : [];
}

function getUserCookbook($author_id){

    $cookbooks = [];

    if(intval($author_id) > 0){
        $cookbooks = get_posts(array(
            'post_type' => 'cookbook',
            'author' => intval($author_id),
            'post_status' => array('publish', 'draft')
        ));
    }
    return $cookbooks;
}

function cbf_generate_string($len){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $len; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

function sendCollaboratorInvitation($email,$data){
    $title   = 'You got an invitation to collaborate';
    $content = memd_template(CBF_PLUGIN_PATH . '/templates/collaborator-invitation.php',$data);
    $headers = array('Content-Type: text/html; charset=UTF-8');
    // ...
    wp_mail( $email, $title, $content,$headers);
}

function getCollaboratorOwnerUser($id){
    global $wpdb;

    $results = $wpdb->get_results("SELECT user_id FROM $wpdb->prefix" . "cbf_users_collaborators WHERE collaborator_id='$id' LIMIT 1", OBJECT);

    $owner_id = $results[0]->user_id;

    return count($results) > 0 ?  get_user_by('ID', $owner_id ) : -1;
}

function getCollaboratorsByOwnerId($id){
    global $wpdb;

    $collaborators = array();

    $results = $wpdb->get_results("SELECT u.ID, u.user_email as email, uc.token
                                  FROM $wpdb->prefix" . "cbf_users_collaborators uc
                                  INNER JOIN $wpdb->prefix" . "users u ON uc.collaborator_id = u.ID
                                  WHERE uc.user_id='$id'", ARRAY_A);
    foreach ($results as $collaborator){
        array_push($collaborators, array(
            'ID' => $collaborator['ID'],
            'email' => $collaborator['email'],
            'token' => $collaborator['token'],
            'first' => get_user_meta($collaborator['ID'],'first_name',true),
            'last' => get_user_meta($collaborator['ID'],'last_name',true),
            'status' => get_user_meta($collaborator['ID'],'invitation_status',true)
        ));
    }

    return $collaborators;
}


