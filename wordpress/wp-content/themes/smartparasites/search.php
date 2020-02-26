
<?php 

get_header();
?>
	<h2 class="main-container__results-title"> search results for "<?php the_search_query() ?>" </h2>
<?php

if (have_posts()) :
	while (have_posts()) : the_post();  

		get_template_part('content'); 

	endwhile; 

	else:
		echo '<p class="main-container__search-error"> <i class="fa fa-exclamation-circle"></i>  Sorry, your search yielded no results.</p>';

	endif;
	?>

	</div> <!-- MAIN CONTAINER__MAIN CLOSING-->

	<?php get_sidebar(); ?>

<?php
	get_footer();
?>
