<article class="blog-post blog-post--<?php $category = get_the_category();
$firstCategory = str_replace(" ", '-',  $category[0]->cat_name); echo strtolower($firstCategory);?>">

<!-- CATEGORY (TITLE) CODE -->
	<div class="blog-post__head">

	<?php if ( is_home () || is_category() || is_archive() || is_single() ) {?>
	    <?php 
		$categories = get_the_category(); // returns array of all chategories associated with post
		$seperator = ', '; // how to seperate
		$output = ''; // empty for now

		if ($categories) { //if categories array exists / if has category

			foreach ($categories as $category){ // use variable $category for each item in $categories array
				$output .= '<a class="blog-post__category-link" href="' . get_category_link($category->term_id) . '">' . $category->cat_name . '</a>' . $seperator;  
			} 
			echo trim($output, $seperator); // echo final output, triming seperator from last / sole category
		} else {
		
		}
		?> 
	<?php } elseif ( is_search() ) {

	}?>

	<!--POST TITLE -->
	<?php if ( is_home () || is_category() || is_archive() || is_single() ) {?>
 	<h2 class="blog-post__title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2> 
	<?php } elseif ( is_search() ) {?>

		<!-- highlighted search query title -->
	<?php $title = get_the_title(); $keys= explode(" ",$s); $title = preg_replace('/('.implode('|', $keys) .')/iu', '<span class="blog-post__search-match">\0</span>', $title); ?>
		<h2 class="blog-post__title"><a href="<?php the_permalink();?>"><?php echo $title; ?></a></h2>
	
	<?php } elseif ( is_page() ) {?>
 	<h2 class="blog-post__title"><?php the_title();?></h2> 
	<?php 
	}?>

	<!-- THUMBNAIL + CAPTION -->
	<?php if ( is_home () || is_category() || is_archive() || is_single() ) {?>
		<a class="blog-post__thumb-link" href="<?php the_permalink();?>">
			<?php the_post_thumbnail( 'full' ); ; ?> 
		</a>
		<span class="blog-post__thumb-caption"><?php the_post_thumbnail_caption(); ?></span>
	<?php } elseif ( is_search() ) {?>

	<?php 
	}?>

	</div>
	<div class="blog-post__main">
	<!-- CONTENT / EXCERPT -->
		<?php if ( is_home () || is_category() || is_archive() ) {?>
		
		<?php } elseif ( is_search() ) {?>

		<!-- excerpt w/ highlighted search query IN SEARCH RESULTS-->
		<?php $excerpt = get_the_excerpt(); $keys= explode(" ",$s); $excerpt = preg_replace('/('.implode('|', $keys) .')/iu', '<span class="blog-post__search-match">\0</span>', $excerpt); ?>
			<p><?php echo $excerpt;?></p>

		<?php } elseif ( is_single() || is_page() ) {?>

			<p><?php the_content();?></p>

		<?php	
		}?>

<!-- READ MORE -->
	<?php if ( is_home () || is_category() || is_archive() || is_search() ) {?>

		<a class="blog-post__read-more" href="<?php the_permalink();?>">x Read More x</a>

	<?php } elseif ( is_single() ) {?>

	<?php 	
	}?>
	</div><!-- BLOG POST MAIN END -->

</article>