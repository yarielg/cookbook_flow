
<div class="recipe_featured_container container">
	<div class="row">
		<?php

		if($recipes->have_posts()):
		while($recipes->have_posts()) : $recipes->the_post();
		$flag = get_field('country_recipe', get_the_ID())['value'];
		$images = get_field( 'cbf_photos', get_the_ID() );
		$featured = '';
		//var_dump($images);exit;
		if(count($images) > 0){
			$featured = getFeaturedImageRecipe($images);
		}
		?>
		<div class="col-md-<?php echo $cols ?> mb-5">
			<a href="<?php echo get_permalink( get_the_ID() ) ?>">
				<div class="featured_item" style="background-image: url(<?php echo $featured ?>);background-size: cover;height: 200px">
					<div class="flag">
						<img style="width:<?php echo $a['flag_width'] ?>px; height:  <?php echo $a['flag_height'] ?>px;position: absolute;bottom: 20px;left: 30px;" src="<?php echo !empty($flag) ? CBF_PLUGIN_URL . '/assets/images/flags/'. $flag . '.png' : '' ?>" alt="">
					</div>
				</div>
			</a>
		</div>
<?php

endwhile;
endif;

?>