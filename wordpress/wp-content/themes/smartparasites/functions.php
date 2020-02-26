<?php 

function load_stylesheets() {
	/*wp_register_style('stylesheet', get_template_directory_uri() . '/style.css', array(), false, 'all');
	wp_register_style('fontawesome', get_template_directory_uri() . '/fontawesome.css', array(), false, 'all');
	/* Must use '/' before stylesheet location
	wp_enqueue_style('stylesheet', 'fontawesome');  Needed? Difference between register & emque?*/
	wp_enqueue_style('stylesheet', get_template_directory_uri() . '/style.css', false, 'all');
	wp_enqueue_style('fontawesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false );
}

function my_theme_scripts_function() {
  wp_enqueue_script( 'jquery', get_template_directory_uri() . '/js/jquery.min.js');
  wp_enqueue_script( 'myscript', get_template_directory_uri() . '/js/myscript.js');
}

add_action('wp_enqueue_scripts','my_theme_scripts_function');

add_action('wp_enqueue_scripts', 'load_stylesheets'); /* Needed */

// WORDPRESS THEME SUPPORT - all theme support fucntions moved here. Function called after
function wordpessThemeSupport () {
	add_theme_support('post-thumbnails'); // add thumbnail option

	//Nav Menus
	register_nav_menus(array( 
		'primary' => __( 'Primary Menu' ), 
		'footer' => __('Footer Menu'), 
	));
}

// REMOVE 'UNCATEGORISED' CATEGORY
function remove_uncategorized_links( $categories ){

	foreach ( $categories as $cat_key => $category ){
		if( 1 == $category->term_id ){
			unset( $categories[ $cat_key ] );
		}
	}

	return $categories;
	
} 

add_filter('get_the_categories', 'remove_uncategorized_links', 1);

add_action('after_setup_theme', 'wordpessThemeSupport');

// WIDGET SIDEBARS
function addWidgetSupport () {
	register_sidebar( array(
		'name' => 'Sidebar 1',
		'id' => 'sidebar-1'
	));
	register_sidebar( array(
		'name' => 'Sidebar 2',
		'id' => 'sidebar-2'
	));
	register_sidebar( array(
		'name' => 'Sidebar 3',
		'id' => 'sidebar-3'
	));
}

add_action('widgets_init', 'addWidgetSupport'); // ADD WIDGET SUPPORT

/*	// Place this function into your functions.php theme file
	function pagination($pages = '', $range = 4) {
		$showitems = ($range * 2)+1; 
		global $paged; 
		if(empty($paged)) $paged = 1; 
		if($pages == '') { 
			global $wp_query; 
			$pages = $wp_query->max_num_pages; 
			if(!$pages) { 
				$pages = 1; 
			} 
		} 
		if(1 != $pages) { 
			echo "<div class=\"pagination\">"; 
			if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; Primera</a>";
			if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Anterior</a>"; 
			for ($i=1; $i <= $pages; $i++) { 
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) { 
					echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>"; 
				} 
			} 
			if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Siguiente &rsaquo;</a>"; 
			if ($paged < $pages-1 && $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&Uacute;ltima &raquo;</a>"; echo "</div>\n"; 
		} 
	}

*/

?>