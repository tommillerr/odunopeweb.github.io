
</div> <!-- MAIN CONTAINER CLOSING-->
<footer class="footer">
	<div class="footer__container">
 		<div class="footer__pagination">
 			<?php next_posts_link('<span class="footer__pagination-x">x</span> next page <span class="footer__pagination-x">x</span>'); ?> 
 			<?php echo paginate_links(array (
				// 'next_text' => '<span class="footer__pagination-x">x</span> next page <span class="footer__pagination-x">x</span>', 
				// 'prev_text' => '<span class="footer__pagination-x">x</span> previous page <span class="footer__pagination-x">x</span>',
				'prev_next' => false,
				 'mid_size' => '1',
				 'type' => 'list',

				 //'numberposts' => '4',
				 'end_size' => '3' // pages to show after ...
	 			)); 
	 		?>
	 		<?php previous_posts_link('<span class="footer__pagination-x">x</span> previous page <span class="footer__pagination-x">x</span>'); ?> 
 		</div>
		<div class="footer__menu-container">
		<nav class="footer__menu-nav">
		<?php 
			$args = array(
				'theme_location' => 'footer', 
				'before' => '<span class="footer__menu-x">x</span><span class="footer__menu-link">', 
				'after'  => '</span><span class="footer__menu-x">x</span>'
			);
		?> <!--nav parameters-->
		<!-- custom define name for menu location 'primary' -->
		<?php wp_nav_menu($args);?> <!-- pull in all pages, insert $args argument and define array of options (links to each page) above, THEN register in functions.php-->
		</nav>
		</div>
	</div>
</footer>
<?php wp_footer();?>
<!--</div> mistake?  BODY CONTAINER-->
</body>

</html>