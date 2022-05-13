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
            'primary' => $photo->primary,
            'caption' => $photo->caption,
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

    deleteAllRecipeCookBooksByRecipeID($cookbook_id,'cookbook_id');

    foreach ($recipes as $recipe_id){
        $wpdb->query("INSERT INTO $wpdb->prefix" . "cbf_recipes_cookbooks (recipe_id,cookbook_id) VALUES ('$recipe_id','$cookbook_id')");
    }

}

function deleteAllRecipeCookBooksByRecipeID($id, $field = 'recipe_id'){
    global $wpdb;

    $wpdb->query("DELETE FROM $wpdb->prefix" . "cbf_recipes_cookbooks WHERE $field='$id'");
}

function insertCookbooksToRecipe($cookbooks, $recipe_id){
    global $wpdb;

    deleteAllRecipeCookBooksByRecipeID($recipe_id);

    foreach ($cookbooks as $cookbook_id){
        $wpdb->query("INSERT INTO $wpdb->prefix" . "cbf_recipes_cookbooks (recipe_id,cookbook_id) VALUES ('$recipe_id','$cookbook_id')");
    }

}

function insertCollaboratorUser($user_id,$collaborator_id,$token, $status = 'Sent'){
    global $wpdb;

    $wpdb->query("INSERT INTO $wpdb->prefix" . "cbf_users_collaborators (user_id,collaborator_id,token,status) VALUES ('$user_id','$collaborator_id','$token','$status')");

}

function updateCollaboratorUserInvitation($user_id,$collaborator_id, $status){
	global $wpdb;

	$wpdb->query("UPDATE $wpdb->prefix" . "cbf_users_collaborators set status='$status' WHERE user_id='$user_id' AND collaborator_id='$collaborator_id'");

}


function existCollaboratorOwner($user_id,$collaborator_id){
	global $wpdb;

	$results = $wpdb->get_results("SELECT user_id FROM $wpdb->prefix" . "cbf_users_collaborators WHERE user_id='$user_id' AND collaborator_id='$collaborator_id'", OBJECT);

	return count($results) > 0;

}

function isCollaborator($user_id){
	global $wpdb;

	$results = $wpdb->get_results("SELECT user_id FROM $wpdb->prefix" . "cbf_users_collaborators WHERE collaborator_id='$user_id'", OBJECT);

	return count($results) > 0;

}

function getCookbooksFromRecipeId($id){
    global $wpdb;

    $cookbook = $wpdb->get_results("SELECT P.post_title,P.ID FROM $wpdb->prefix" . "cbf_recipes_cookbooks RC INNER JOIN $wpdb->prefix" . "posts P ON RC.cookbook_id=P.ID WHERE RC.recipe_id='$id'", ARRAY_A);

    return count($cookbook) > 0 ? $cookbook : [];
}

function getRecipesFromCookbookId($id){
    global $wpdb;

    $recipes = $wpdb->get_results("SELECT P.post_title,P.ID FROM $wpdb->prefix" . "cbf_recipes_cookbooks RC INNER JOIN $wpdb->prefix" . "posts P ON RC.recipe_id=P.ID WHERE RC.cookbook_id='$id'", ARRAY_A);

    return count($recipes) > 0 ? $recipes : [];
}

function getUserCookbook($author_id){

    $cookbooks = [];

    if(intval($author_id) > 0){
        $cookbooks = get_posts(array(
            'post_type' => 'cookbook',
            'author' => intval($author_id),
            'post_status' => array('publish', 'draft'),

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

    wp_mail( $email, $title, $content,$headers);
}

function shareRecipeEmail($email,$data){
	$title   = 'Recipe Shared';
	$content = memd_template(CBF_PLUGIN_PATH . '/templates/share-recipe.php',$data);
	$headers[] = 'Content-Type: text/html; charset=UTF-8';

	add_filter( 'wp_mail_from_name', function() use ($data){
		if(empty($data['sender_name'])){
			return 'The Creative Cookbook';
		}
		return $data['sender_name'] . ' via The Creative Cookbook';
	} );

	return wp_mail( $email, $title, $content,$headers);
}

function getCollaboratorOwnerUser($id){
    global $wpdb;

    $results = $wpdb->get_results("SELECT user_id FROM $wpdb->prefix" . "cbf_users_collaborators WHERE collaborator_id='$id' LIMIT 1", OBJECT);

    $owner_id = $results[0]->user_id;

    return count($results) > 0 ?  get_user_by('ID', $owner_id ) : -1;
}

function getCountriesACF(){
	global $wpdb;

	$result = $wpdb->get_results("SELECT post_content FROM $wpdb->prefix" . "posts WHERE post_excerpt='country_recipe' AND post_type='acf-field' LIMIT 1", OBJECT);

	return count($result) > 0 ? unserialize($result[0]->post_content)['choices'] : [];
}

function getUnitACF(){
	global $wpdb;

	$result = $wpdb->get_results("SELECT post_content FROM $wpdb->prefix" . "posts WHERE post_excerpt='unit' AND post_type='acf-field' LIMIT 1", OBJECT);

	return count($result) > 0 ? unserialize($result[0]->post_content)['choices'] : [];
}

function getCollaboratorsByOwnerId($id){
    global $wpdb;

    $collaborators = array();

    $results = $wpdb->get_results("SELECT u.ID, u.user_email as email, uc.token,uc.status
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
            'status' => $collaborator['status']
        ));
    }

    return $collaborators;
}

function getAccountsByUserId($user_id){
	global $wpdb;

	$user = $wpdb->get_results("SELECT ID as id, user_login as username, user_email as email  FROM  $wpdb->prefix" . "users WHERE ID='$user_id' ", ARRAY_A);

	$collaborators = $wpdb->get_results("SELECT u.ID as id, u.user_login as username, u.user_email as email, uc.collaborator_id  FROM $wpdb->prefix" . "cbf_users_collaborators uc
                                  INNER JOIN $wpdb->prefix" . "users u ON uc.user_id = u.ID
                                  WHERE uc.collaborator_id='$user_id'", ARRAY_A);

	$accounts = array();
	foreach ($user as $u){
		$u['collaborator_id'] = $u['id'];
		$u['account_type'] = 'owner';

		$customer = rcp_get_customer_by_user_id($u['id']);
		$premium = false;
		if ($customer) {
			$memberships = $customer->get_memberships();
			$premium = $memberships[0]->get_gateway() == 'free' || $memberships[0]->get_status() == 'cancelled' ? false : true;
		}

		$u['premium'] = $premium;
		array_push($accounts, $u);
	}

	foreach ($collaborators as $collaborator){
		$collaborator['account_type'] = 'collaborator';

		$customer = rcp_get_customer_by_user_id($collaborator['id']);
		$premium = false;
		if ($customer) {
			$memberships = $customer->get_memberships();
			$premium = $memberships[0]->get_gateway() == 'free' || $memberships[0]->get_status() == 'cancelled' ? false : true;
		}
		$collaborator['premium'] = $premium;
		array_push($accounts, $collaborator);
	}


	return $accounts;
}

function cbf_get_user_info(){
    $account_type = CBF_EMPTY_ACCOUNT;
    $user = null;
    $premium = false;
    $owner = false;

    if(is_user_logged_in()) {

        $account_type = CBF_FREE_ACCOUNT;

        $user = wp_get_current_user();
        $owner = $user;

        if (in_array('cbf_collaborator', (array)$user->roles)) {

            $account_type = CBF_COLLABORATOR_ACCOUNT;
            /**
             * Determine if the collaborator is tied to free/paid owner account
             */
            $owner = getCollaboratorOwnerUser($user->ID);
        }

        /**
         * Check if the current user is premium (current user can be a collaborator or the account owner, in both case we need to determine if the account is premium)
         */
        $owner_id = $owner->ID;
        $customer = rcp_get_customer_by_user_id($owner_id);

        if ($customer) {
            $memberships = $customer->get_memberships();
            $premium = $memberships[0]->get_gateway() == 'free' || $memberships[0]->get_status() == 'cancelled' ? false : true;
            $account_type = $account_type != CBF_COLLABORATOR_ACCOUNT && $premium ? CBF_OWNER_ACCOUNT : $account_type;
        }
    }

    return array('account_type' => $account_type, 'user' => $user, 'owner' => $owner, 'premium' => $premium);
}

/**
 * @param $id
 * @return array|null
 * Get template from id(index)
 */
function getTemplateACFByID($id){
     $template = null;
    if (have_rows('cbf_templates','option')) {
        while (have_rows('cbf_templates','option')) {
            the_row();

            if (get_row_index() == $id) {
                $template = array(
                    'id' => get_row_index(),
                    'name' => get_sub_field('name'),
                    'image' => get_sub_field('image'),
                    'images' => get_sub_field('images'),
                );
            }
        }
    }
    return $template;
}

/**
 * @return array
 * get all the templates from general settings plugin
 */
function getTemplatesACF(){
    $templates= array();
    if (have_rows('cbf_templates','option')) {

        while( have_rows('cbf_templates','option') ) : the_row();

            array_push($templates, array(
                'id' => get_row_index(),
                'name' => get_sub_field('name'),
                'url' => get_sub_field('image')['url'],
                'caption' => get_sub_field('caption'),
	            'images' => get_sub_field('images'),
            ));

        endwhile;
    }
    return $templates;
}

function getServicesACF(){
    $products = get_field('cbf_product_services', 'option');
    $services = array();
    foreach ($products as $product){
        array_push($services, array(
            'id' => $product->ID,
            'name' => $product->post_title,
            'url' => get_the_post_thumbnail_url($product->ID),
        ));
    }
    return  $services;
}

function cbf_append_csv_files($zip, $cookbook_id,$image_paths, $order, $template_path){

	$cookbook = get_post($cookbook_id);

	$path_name = $cookbook_id . '-' . time() .'.csv';
	$csv_file_name = wp_upload_dir()['basedir'] . '/zips/'. $path_name ; //You can give your path to save file.

	$author =  get_field( 'cbf_author_name',$cookbook_id );
	$image_caption_introduction =  get_field( 'cbf_introduction_image_caption',$cookbook_id );
	$dedication_transformed =  str_replace("\r\n", '<BREAK>', get_field('dedication', $cookbook_id));
	$back_cover_story_transformed =  str_replace("\r\n", '<BREAK>', get_field('cbf_back_cover_story', $cookbook_id));
	$introduction_transformed = str_replace("\r\n", '<BREAK>', get_field('introduction', $cookbook_id));
	$introduction_headline_transformed = str_replace("\r\n", '<BREAK>', get_field('cbf_introduction_headline', $cookbook_id));
	$back_cover_headline_transformed = str_replace("\r\n", '<BREAK>', get_field('cbf_back_cover_headline', $cookbook_id));

	$user = $order->get_user();

	$isbn_paper = get_field('isbn_paper' , $order->get_id());
	$isbn_hard = get_field('isbn_hard' , $order->get_id());
	$library = get_field('library_of_congress' , $order->get_id());

	$data = [
		['username', 'email', 'title','author',"'@frontcoverphoto",'year','isbnpaper','isbnhard','libraryofcongresscom','dedication',"'@introphoto",'introcaption','introheadline','introtext','backcoverheadline',"backcoverstory","'@backcoverphoto","'@templatedesignpath"],
		[$user->user_login,$order->get_billing_email(),$cookbook->post_title, "by " . $author,$image_paths['front_image'], date('Y'),$isbn_paper,$isbn_hard,$library, $dedication_transformed,$image_paths['introduction_image'],$image_caption_introduction, $introduction_headline_transformed,$introduction_transformed,$back_cover_headline_transformed,$back_cover_story_transformed,$image_paths['back_image'],$template_path ]
	];

	$f = fopen($csv_file_name, 'w');

	foreach ($data as $row) {
		fputcsv($f, $row);
	}

	//$zip->addFile($csv_file_name ,$cookbook->post_name . '-'. $cookbook->ID . '-' . time() .'.csv');
	$zip->addFile($csv_file_name ,'cookbook-data.csv');

	return $csv_file_name;

}

function cbf_append_csv_recipes($zip,$cookbook_id){

	$recipes = getRecipesFromCookbookId($cookbook_id);
	$recipe_images = array();

	$path_name =   'recipe-' . time() .'.csv';
	$csv_file_name = wp_upload_dir()['basedir'] . '/zips/'. $path_name ; //You can give your path to save file.

	$data = [
		['recipecategory', 'recipetitle', "'@recipephoto",'ingredients','recipeinstructions',"'@recipestoryphoto",'recipestoryheadline','recipestorytext']
	];

	//Getting images from recipes
	if(is_array($recipes) && count($recipes) > 0){
		foreach ($recipes as $recipe){
			$recipe_obj = get_post($recipe['ID']);
			$ingredients_transformed = str_replace("\r\n", '<BREAK>', get_field('cbf_ingredients_text', $recipe['ID']));
			$story_transformed = str_replace("\r\n", '<BREAK>',get_field('story', $recipe['ID']));
			$instructions_transformed = str_replace("\r\n", '<BREAK>', get_field('cbf_instructions', $recipe['ID']));
			$headline_story_transformed = str_replace("\r\n", '<BREAK>',get_field('cbf_headline_story', $recipe['ID']));
			$recipe_title = $recipe_obj->post_title;
			$term_obj_list = get_the_terms( $recipe['ID'], 'cat_recipe' );
			$category_name = $term_obj_list  ? str_replace('&amp;', '&', $term_obj_list[0]->name) : '';

			$images = get_field( 'cbf_photos',$recipe['ID'] );
			$path_to_add_csv = '';
			$path_to_add = '';
			foreach ($images as $image){
				$path = get_attached_file($image['image']['ID']);
				if(!empty($image['image']['filename'])){
					$path_to_add_csv = '\images\\'. $image['image']['filename'];
					$path_to_add = 'images/'. $image['image']['filename'];
					$zip->addFile($path, $path_to_add);
				}
			}

			$images_story = get_field( 'cbf_story_photos',$recipe['ID'] );
			$path_to_add_story = '';
			$path_to_add_story_csv= '';
			foreach ($images_story as $image){
				$path_story = get_attached_file($image['image']['ID']);
				if(!empty($image['image']['filename'])){
					$path_to_add_story_csv = '\images\\'.  $image['image']['filename'];
					$path_to_add_story = 'images/'.  $image['image']['filename'];
					$zip->addFile($path_story,$path_to_add_story);
				}

			}

			$data[] = [$category_name,$recipe_title,$path_to_add_csv,$ingredients_transformed,$instructions_transformed,$path_to_add_story_csv,$headline_story_transformed,$story_transformed];

		}
	}

	$f = fopen($csv_file_name, 'w');

	foreach ($data as $row) {
		fputcsv($f, $row);
	}

	$zip->addFile($csv_file_name ,'recipes-data.csv');

	return $csv_file_name;

}

function cbf_append_xml_files($zip, $cookbook_id){

    $recipes = getRecipesFromCookbookId($cookbook_id);
    $cookbook = get_post($cookbook_id);

    $dom = new \DOMDocument();

    $dom->encoding = 'utf-8';
    $dom->xmlVersion = '1.0';
    $dom->formatOutput = true;
    $path_name = $cookbook_id . '-' . time() .'.xml';
    $xml_file_name = wp_upload_dir()['basedir'] . '/zips/'. $path_name ; //You can give your path to save file.

    //$back_image = get_field( 'back_cover_image',$cookbook_id ) ? get_field( 'back_cover_image',$cookbook_id ) : null;
    $dedication =  get_field( 'dedication',$cookbook_id );
    $acknowledgments = get_field( 'acknowledgments',$cookbook_id );
    $introduction = get_field( 'introduction',$cookbook_id );

    //Root (cookbook)
    $root = $dom->createElement('cookbook');

    //Cookbook Title
    $child_cookbook_title= $dom->createElement('cookbook-title', $cookbook->post_title);
    $root->appendChild($child_cookbook_title);

    //Cookbook Subtitle
    $child_cookbook_subtitle= $dom->createElement('cookbook-subtitle', '');
    $root->appendChild($child_cookbook_subtitle);

    //Cookbook by-line
    $child_cookbook_byline= $dom->createElement('by-line', '');
    $root->appendChild($child_cookbook_byline);

    //Cookbook ISBN
    $child_cookbook_isbn= $dom->createElement('ISBN', '');
    $root->appendChild($child_cookbook_isbn);

    //Dedication
    $child_cookbook_dedication= $dom->createElement('dedication', $dedication);
    $root->appendChild($child_cookbook_dedication);

    //Acknowledgments
    $child_cookbook_acknowledgments= $dom->createElement('acknowledgments', $acknowledgments);
    $root->appendChild($child_cookbook_acknowledgments);

    //Introduction
    $child_cookbook_introduction= $dom->createElement('introduction', $introduction);
    $root->appendChild($child_cookbook_introduction);

    $recipes_node = $dom->createElement('recipes');

    foreach ($recipes as $recipe){

        $recipe_obj = array();

        $recipe_post = get_post($recipe['ID']);

        $story = get_field('story', $recipe['ID']);

        $recipe_node = $dom->createElement('recipe');

        //Recipe Title
        $child_recipe_title = $dom->createElement('recipe-title', $recipe['post_title']);
        $recipe_node->appendChild($child_recipe_title);

        //Recipe Instructions
        /*$child_recipe_instructions = $dom->createElement('recipe-instructions', $recipe_post->post_content);
        $recipe_node->appendChild($child_recipe_instructions);*/

        //Recipe Story
        /*$child_recipe_story = $dom->createElement('recipe-story', $story);
        $recipe_node->appendChild($child_recipe_story);*/

        //$recipe->story = get_field('story', $recipe['ID']);

        $term_obj_list = get_the_terms( $recipe['ID'], 'cat_recipe' );
        //$recipe['category'] = $term_obj_list ? $term_obj_list[0]->term_id : -1;
        $category_name = $term_obj_list  ? $term_obj_list[0]->name : '';

        //Category Node
        $category_node = $dom->createElement('category');

        $child_category_title = $dom->createElement('category-story-title', $category_name);
        $category_node->appendChild($child_category_title);

        $child_category_photo = $dom->createElement('category-photo', '');
        $category_node->appendChild($child_category_photo);

        $child_category_caption = $dom->createElement('category-photo-caption', '');
        $category_node->appendChild($child_category_caption);

        $child_category_text = $dom->createElement('category-photo-text', '');
        $category_node->appendChild($child_category_text);

        $recipe_node->appendChild($category_node);

        //Ingredients
	    $ingredients = get_field( 'cbf_ingredients_text',$recipe['ID'] );
        $ingredients_node = $dom->createElement('ingredients',"![CDATA" . $ingredients . ']]');


       // var_dump($ingredients);exit;

        /*if(is_array($ingredients) && count($ingredients) > 0){
            foreach ($ingredients as $ingredient){
                $ingredient_node = $dom->createElement('ingredient');

                $child_ingredient_name = $dom->createElement('name', $ingredient['name']);
                $ingredient_node->appendChild($child_ingredient_name);

                $child_ingredient_quantity = $dom->createElement('quantity', $ingredient['quantity']);
                $ingredient_node->appendChild($child_ingredient_quantity);

                $child_ingredient_unit = $dom->createElement('unit', $ingredient['unit']);
                $ingredient_node->appendChild($child_ingredient_unit);

                $ingredients_node->appendChild($ingredient_node);
            }
        }*/

        $recipe_node->appendChild($ingredients_node);

        $recipes_node->appendChild($recipe_node);
    }

    $root->appendChild($recipes_node);

    $dom->appendChild($root);
    $dom->save($xml_file_name);

    $zip->addFile($xml_file_name ,$cookbook->post_name . '-'. $cookbook->ID . '-' . time() .'.xml');

    return $xml_file_name;

}

/**
 * Insert comment
 *
 * @param $admin It is flag to know if the comment was sent by the admin or not
 * @param $comment It contains the message itself
 * @param $cookbook_id The cookbook id to know where we need to show the messages
 */
function insertCommentCookbook($admin, $comment,$cookbook_id){
	global $wpdb;

	$created = date('Y-m-d H:i:s');

	$wpdb->query("INSERT INTO $wpdb->prefix" . "cbf_comments (admin,cookbook_id,comment,created) VALUES ('$admin','$cookbook_id','$comment','$created')");

}

/**
 * Get all the comments
 */
function getCookbookComments($cookbook_id){
	global $wpdb;

	$comments = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "cbf_comments WHERE cookbook_id='$cookbook_id'", ARRAY_A);

	return count($comments) > 0 ? $comments : [];
}

/**
 * Get the featured image from recipe photos
 */

function getFeaturedImageRecipe($images){

	$featured = '';

	if(count($images) > 0){
		$featured = $images[0]['image']['url'];
		foreach ($images as $image){
			if($image['primary']){
				$featured = $image['image']['url'];
				break;
			}
		}
	}

	return $featured;
}

