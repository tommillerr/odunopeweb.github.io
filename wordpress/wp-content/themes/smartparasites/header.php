<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width"> <!--needed??? -->
	<title><?php bloginfo('name'); ?></title>
	<link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"> <!--MOVE TO STYLESHEET?-->
	<link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
	<?php wp_head(); ?> 
</head>
<body <?php body_class();?>> 
<div id="fb-root"></div> <!-- FOR FACEBOOK SIDEBOX --> 
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v5.0"></script>
<header class="header">
	<div class="header__container">
		 <button type="button" class="header__nav-button">
            <div class="header__nav-button__bars">
                <div class="header__nav-button__bar-1"></div>
                <div class="header__nav-button__bar-2"></div>
                <div class="header__nav-button__bar-3"></div>
            </div>
        </button>
		<div class="header__logo-container">
			<a class="header__logo-link" href="<?php echo home_url();?>">
				<img class="header__logo-img" src="Http://www.artparasites.com/wp-content/themes/wp-theme-ArtParasites/images/artparasites_logo.png"/>
			</a>
		</div>
		<div class="header__menu-search-js">
		<div class="header__menu-container">
			<nav class="header__menu-nav">
			<?php 
				$args = array(
					'theme_location' => 'primary', 
					'before' => '<span class="header__menu-x">x</span><span class="header__menu-link">', 
					'after'  => '</span><span class="header__menu-x">x</span>'
				);
			?>
			<?php wp_nav_menu($args);?> 
			</nav>
		</div>
		<!--SEARCH BAR -->
		<div class="header__search">
			<?php get_search_form() ?>
		</div>
		<div> <!---->
	</div>
</header>
<div class="main-container"> 
	<div class="main-container__main">
