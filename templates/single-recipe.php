<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();
$recipe_id = get_the_id();
$term_obj_list = get_the_terms( $recipe_id, 'cat_recipe' );
$images = get_field( 'cbf_photos', $recipe_id );
$category = $term_obj_list ? $term_obj_list[0]->name : '';
$ingredients = get_field( 'cbf_ingredients',$recipe_id );

?>



    <div class="container">
        <div class="row">
            <div class="col-12">
                <article id="post-<?php the_ID(); ?>" class="recipe-single">
                    <div class="post-thumbnail">
                        <?php

                        if(count($images) > 0){
                            ?>
                            <img src="<?php echo $images[0]['image']['url'] ?>" alt="">
                            <?php
                        }
                        ?>
                    </div>
                    <span class="badge badge-secondary"><?php echo $category; ?></span>
                    <br><br>
                    <header class="entry-header">
                        <?php
                        the_title( '<h1 class="entry-title">', '</h1>' );
                        ?>
                    </header>

                    <div class="info-div">
                        <label for=""><strong>INGREDIENTS</strong></label>
                        <ul class="recipe_ingredients">
                            <?php
                            foreach ($ingredients as $ingredient){
                                echo "<li>" . $ingredient['unit'] . " " . $ingredient['quantity'] . " " . $ingredient['name'] . "</li>";
                            }
                            ?>
                        </ul>
                    </div>


                    <div class="info-div mr-4">
                        <label for=""><strong>INSTRUCTIONS</strong></label>
                        <?php
                        the_content();
                        ?>
                    </div>

                    <div class="info-div mr-4">
                        <label for=""><strong>PHOTOS</strong></label>
                        <ul class="photo_recipe_gallery">
                            <?php
                            $cont = 0;
                            foreach ($images as $image){
                                if($cont == 0){
                                    $cont++; continue;
                                }

                                echo "<li class='single-image-gallery'><img src='" . $image['image']['url'] . "' alt=''></li>";

                                $cont++;
                            }
                            ?>
                        </ul>
                    </div>

                </article>
            </div>
        </div>
    </div>




<?php get_footer(); ?>
