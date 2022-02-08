<?php
/**
 * cbf_search_recipes shortcode
 *
 * template to render the cbf_search_recipes shortcode, it using a traditional form post action
 */

    global $post;
    $slug = $post->post_name;

    $args = array(
        'post_type' => 'recipe',
        'post_status' => array('publish'),
	    'posts_per_page' => -1

    );

    if(isset($_GET['pages'])){
	    $slug .= '?pages=' . $_GET['pages'];
	    $args['posts_per_page'] = intval($_GET['pages']);
    }else{
	    $slug .= '?pages=';
    }


    if(isset($_GET['cat'])){
	    $args['tax_query'] = array(
		    array (
			    'taxonomy' => 'cat_recipe',
			    'field' => 'term_id',
			    'terms' => $_GET['cat'],
		    )
	    );

	    $slug .= '&cat=' . $_GET['cat'];
    }

    if(isset($_GET['search_query'])){
        $args['s'] =  $_GET['search_query'];
	    $slug .= '&search_query=' . $_GET['search_query'];
    }

    $query = new WP_Query($args);

    $cat_args = array(
        'taxonomy' => 'cat_recipe',
        'orderby' => 'name',
        'order'   => 'ASC'
    );

    $cats = get_categories($cat_args);

    ?>

<div class="container--fluid">

    <div class="row">
        <div class="col-md-4">
            <h5>Recipe Categories</h5>
            <hr>
            <ul class="category_list">
	            <?php foreach ($cats as $category){ ?>
                    <li class="category_item"><a href="?cat=<?php echo $category->term_id ?>"><span class="badge badge-<?php echo isset($_GET['cat']) && intval($_GET['cat']) == $category->term_id ? 'secondary' : 'default' ?>"><?php echo $category->name ?></span></a></li>
	            <?php } ?>
            </ul>
        </div>

        <div class="col-md-8">
            <div class="">
                <h4>Search our library of recipes! </h4>
                <p>Get inspiration or add them to your own recipe book from our library of recipes.</p>
            </div>
            <hr>
            <form class="container search_recipe_form"  action="<?= site_url(). '/' .  $slug ?>" method="get">
                <div class="row">
                    <div class="col-12 px-0">
                        <a href="<?= site_url(). '/search-recipe' ?>">Reset Search</a>
                        <select name="pages" id="pages" class="float-right">
                            <option <?php !isset($_GET['pages']) ? 'selected' : '' ?> value="">Select</option>
                            <option <?php echo isset($_GET['pages']) && $_GET['pages'] == 5 ? 'selected' : '' ?> value="5">5</option>
                            <option <?php echo isset($_GET['pages']) && $_GET['pages'] == 10 ? 'selected' : '' ?> value="10">10</option>
                            <option <?php echo isset($_GET['pages']) && $_GET['pages'] == 15 ? 'selected' : '' ?> value="15">15</option>
                            <option <?php echo isset($_GET['pages']) && $_GET['pages'] == 20 ? 'selected' : '' ?> value="20">20</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 p-0">
                        <input class="search_recipe_full" name="search_query" value="<?php echo isset($_GET['search_query']) ? $_GET['search_query'] : '' ?>" type="text" placeholder="Search">

                    </div>
                    <div class="col-md-2 p-0">
                        <button type="submit" class="search_recipe_full btn-normal">Search</button>
                    </div>
                </div>
                <br>

                <input type="hidden" name="cat" value="<?php echo isset($_GET['cat']) ? $_GET['cat'] : '' ?>">
            </form>

            <div class="row mt-5">
		        <?php
		        if ($query->have_posts()) {
			        while ($query->have_posts()) {
				        $query->the_post();
				        $recipe = get_post(get_the_ID());
				        $photos = get_field('cbf_photos', $recipe->ID);
				        if(is_array($photos) && count($photos) > 0){
					        $recipe->photo_url = $photos[0]['image']['url'];
				        }else{
					        $recipe->photo_url = '/wp-content/uploads/2021/11/default.jpg';
				        }

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
    </div>

</div>
