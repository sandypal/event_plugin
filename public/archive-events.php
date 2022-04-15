<?php
/* Template Name: Archive Page Events */
get_header(); ?>
<div class='archive-page'>
	<div class='container'> 
		<h1 class='title'>Events List </h1>
		<div class='main_col'>
        <?php while ( have_posts() ) : the_post(); // standard WordPress loop. 
         	$featureImg =  get_the_post_thumbnail_url();  ?>
				<div class='inner_col'>
					<a href='<?php echo get_the_permalink()?>' class='post_title'>
						<div class="feature_img"><img src ="<?php echo $featureImg; ?>"></div>
						<span class='title'><?php echo get_the_title(); ?></span>
					</a>
				</div>
        <?php endwhile; // end of the loop. ?>
		</div>
	</div><!-- .container -->
</div>
<?php get_footer(); ?>