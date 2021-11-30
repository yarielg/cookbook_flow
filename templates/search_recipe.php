<?php
/**
 * cbf_search_recipes shortcode
 *
 * template to render the cbf_search_recipes shortcode, it using a traditional form post action
 */

    $args = array(
        'post_type' => 'recipe',
        'post_status' => array('publish')

    );

    if(isset($_GET['search_query'])){
        $args = array(
            'post_type' => 'recipe',
            'post_status' => array('publish'),
            's' => $_GET['search_query']

        );
    }

    $recipes = get_posts($args);

    foreach ($recipes as $recipe){
        //  if(intval($recipe->post_author) !== intval($author_id)) continue;
        $photos = get_field('cbf_photos', $recipe->ID);
        if(count($photos) > 0){
            $recipe->photo_url = $photos[0]['image']['url'];
        }else{
            $recipe->photo_url = '/wp-content/uploads/2021/11/default.jpg';
        }
    }
    ?>

<div class="container">

    <div class="row">
        <div class="col-12">
            <h4>Search our library of recipes! </h4>
            <p>Get inspiration or add them to your own recipe book from our library of recipes.</p>
        </div>
        <div class="col-12">
            <form action="https://cookbook.nextsitehosting.com/<?=  $slug ?>" method="get">
                <div class="row">
                    <div class="col-md-10 p-0">
                        <input class="search_recipe_full" name="search_query" value="<?= $_GET['search_query'] ?: '' ?>" type="text" placeholder="Search">

                    </div>
                    <div class="col-md-2 p-0">
                        <button type="submit" class="search_recipe_full btn-normal">Search Recipes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-5">
        <?php
        if(count($recipes) > 0){
            foreach ($recipes as $recipe){

                ?>
                <div class="col-md-3 box-panel mb-5 text-center">
                    <a class="search_recipe_link" href="<?= $recipe->guid ?>">
                        <div class="panel-wrapper recipe-img-wrapper" style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.5)), url(<?= $recipe->photo_url ?>);">
                            <h5><?= $recipe->post_title ?></h5>
                        </div>
                    </a>
                </div>
                <?php
            }
        }else{
            ?>
            <div class="col-12 text-center">
                <h3>No recipes were found</h3>
            </div>
            <?php

        }

        ?>
    </div>
</div>
