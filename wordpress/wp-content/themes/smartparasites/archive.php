
<?php 

get_header();

if (have_posts()) :
	while (have_posts()) : the_post();  

		get_template_part('content'); 

	endwhile; 

	else:
		echo '<p> no content found</p>';
	endif;
	?>

	</div> <!-- MAIN CONTAINER__MAIN CLOSING-->

	<?php get_sidebar(); ?>

<?php
	get_footer();
?>
