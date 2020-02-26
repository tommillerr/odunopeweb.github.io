<?php
/*
* Stop execution if someone tried to get file directly.
*/ 
if ( ! defined( 'ABSPATH' ) ) exit;

								//======================================================================
													// Main class of all FTA Skins
								//======================================================================

if(!class_exists('MIF_SKINS')):
class MIF_SKINS {

		/*
		* __construct initialize all function of this class.
		* Returns nothing. 
		* Used action_hooks to get things sequentially.
		*/ 
	function __construct(){	

		/* Action hook fires on admin load. */ 
		add_action( 'init', array($this, 'mif_skins_register'), 20);

		/*
		* Gets all Skins.
		*/ 	
		$this->mif_default_skins();

		/*
		* Gets all Skins.
		*/ 	
		$this->mif_skins();
		}/* __construct Method ends here. */

		/*
		* Register skins posttype.
		*/ 	
		public function mif_skins_register(){

		$FTA = new Feed_Them_All();

		 $fta_settings = $FTA->fta_get_settings();

		// echo "<pre>"; print_r();exit();
		/* Arguments for custom post type of skins. */
			 $args = array(
		      'public' => true,
		      'label'  =>  __('MIF Skins', $FTA->plug_slug),
		      'show_in_menu' => false,
		      'exclude_from_search' => true,
		      'has_archive' => false,
		      'hierarchical' => true,
		      'menu_position'      => null
		    );

		/* register_post_type() registers a custom post type in wp. */ 
		register_post_type( 'mif_skins', $args );
				
		}/* mif_skins_register Method ends here. */

		/*
		* Register Default skins.
		*/ 	
		public function mif_default_skins(){

		$FTA = new Feed_Them_All();

		 $fta_settings = $FTA->fta_get_settings();


		if ( !isset($fta_settings['plugins']['instagram']['default_skin_id'] ) && empty($fta_settings['plugins']['instagram']['default_skin_id'] )):

			 /* Arguments for default skin. */
            $mif_new_skins = array(
                'post_title'   => __( "Instagram Skin", $FTA->plug_slug ),
                'post_content' => __( "This is the demo skin created by Feed Them All plugin automatically with default values. You can edit it and change the look & feel of your Feeds.", $FTA->plug_slug ),
                'post_type'    => 'mif_skins',
                'post_status'  => 'publish',
                'post_author'  => get_current_user_id(),
            );
            $mif_new_skins = apply_filters('mif_default_skin',  $mif_new_skins);

            // echo "<pre>"; print_r($mif_skin);exit();
            $skin_id = wp_insert_post( $mif_new_skins );

             $fta_settings['plugins']['instagram']['default_skin_id'] =  $skin_id;

			 update_option( 'fta_settings', $fta_settings );

		endif;

		if ( !isset($fta_settings['plugins']['instagram']['default_page_id']) && empty($fta_settings['plugins']['instagram']['default_page_id']) ):

			$skin_id = $fta_settings['plugins']['instagram']['default_skin_id']; 
		
			$user_id = null;

				 /*
				  * Getting approved pages.
				 */
				 $approved_pages = array();

				if(isset($fta_settings['plugins']['facebook']['approved_pages']) && !empty($fta_settings['plugins']['facebook']['approved_pages'])) :
				$approved_pages =  $fta_settings['plugins']['facebook']['approved_pages'];
				endif;

				if($approved_pages):
				 reset($approved_pages); $id = key($approved_pages);

				$user_id = $approved_pages[$id]->instagram_accounts->connected_instagram_account->id;

				endif;

			/*
             * $data array contains the data of demo page.
             */
            $mif_default_page = array(
                'post_title'   => __( "Instagram Demo - Customizer", $FTA->plug_slug ),
                'post_content' => __( "[my-instagram-feed user_id='{$user_id}' skin_id='{$skin_id}'] <br> This is a mif demo page created by plugin automatically. Please don't delete to make the plugin work properly.", $FTA->plug_slug ),
                'post_type'    => 'page',
                'post_status'  => 'publish',
            );

            $mif_default_page = apply_filters('mif_default_page',  $mif_default_page);	

            $page_id = wp_insert_post( $mif_default_page );
			$fta_settings['plugins']['instagram']['default_page_id'] =  $page_id;
			update_option( 'fta_settings', $fta_settings );

		endif; 

		}/* mif_default_skins Method ends here. */

		/*
		* xo_accounts will get the all accounts.
		* Returns accounts  object.
		*/
	public function mif_skins() {

		 $FTA = new Feed_Them_All();

		 $fta_settings = $FTA->fta_get_settings();
			
		/*
		* Arguments for WP_Query().
		*/
		
		$fta_skins = array(
			'posts_per_page' => 1000,
		    'post_type'=> 'mif_skins',
		    'post_status'=> array('publish','draft','pending')
		    ); 
		

		/*
		* Quering all active xOptins.
		* WP_Query() object of wp will be used.
		*/                 
		$fta_skins = new WP_Query($fta_skins);



		/* If any fta_skins are in database. */ 
		if($fta_skins->have_posts() ) { 

		/* Declaring an empty array. */
		$fta_skins_holder = array();	

		/* Looping mif_skins to get all records. */	
		while ( $fta_skins->have_posts() ):


		/* Making it post. */	
		$fta_skins->the_post();

	
		/* Getting the ID. */
		$id = get_the_ID();	

		$design_arr = null;
	  
		
		$design_arr = get_option('mif_skin_'.$id, false);

		 // echo "<pre>"; print_r($design_arr);exit();

		$title = get_the_title();
		
		if(empty($title))
			$title = __('Skin', $FTA->plug_slug);
				
		/* Making an array of skins. */
		$fta_skins_holder[$id]  = array(
		 				'ID' => $id,
		 				'title' => $title,
		 				'description' => get_the_content()

		);

		$defaults = $this->mif_skin_default_values();

		/* If there is no data in live preview section of xOptin setting the default data. */
		$fta_skins_holder[$id]['design'] = wp_parse_args($design_arr, $defaults['design']);
		

		endwhile; // Loop ends here.
		/* Reseting the current query. */
		wp_reset_postdata();

		}

		/* If no data found. */
		else{
			return __( 'No skins found.', $FTA->plug_slug );
		}

		
	
		/* Globalising array to access anywhere. */	
		$GLOBALS['mif_skins'] = $fta_skins_holder;
		// echo "<pre>";
		// print_r($mif_skins_holder);exit;
	}/* xo_Skins Method ends here. */ 

	/*
	* fta_skin_default_values will have default design values of skins.
	*/
	public function mif_skin_default_values(){

		$default_val_arr = array(

					'design' => array(
						'background_color' => '#fff',
						'caption_color' => '#000',
						'header_background_color' => '#fff',
						'btnbordercolor-hover' => '#000',
						'header_text_color' => '#000',
						'feed_time_text_color' => '#000',
						'feed_cta_text_color' => '#000',
						'number_of_cols' => 3,
						'feed_cta_text_hover_color' => '#000',
						'popup_icon_color' => '#fff',
						'feed_hover_bg_color' => 'rgba(0,0,0,0.5',
						'layout_option' => 'grid',
						'title_size' => '16', 
						'metadata_size' => '16',
						'bio_size' => '14',
						'show_comments' => false,
						'show_likes' => false,
						'show_header' => false,
						'show_dp' => true,
						'header_round_dp' => true,
						'header_dp_hover_color' => 'rgba(0,0,0,0.5)',
						'header_dp_hover_icon_color' => '#fff',
						'header_border_color' => '#ccc',
						'header_border_style' => 'none',
						'header_border_top' => '0',
						'header_border_bottom' => '1',
						'header_border_left' => '0',
						'header_border_right' => '0',
						'header_padding_top' => '10',
						'header_padding_bottom' => '10',
						'header_padding_left' => '10',
						'header_padding_right' => '10',
						'feed_image_filter' => 'none',
						'show_likes' => true,
						'header_align' => 'left',
						'feed_background_color' => 'transparent',
						'feed_padding_top_bottom' => 0,
						'feed_padding_top' => 0,
						'feed_padding_bottom' => 0,
						'feed_padding_left' => 0,
						'feed_padding_right' => 0,
						'feed_padding_right_left' => 0,
						'feed_margin_top_bottom' => 5,
						'feed_margin_right_left' => 5,
						'feed_margin_top' => 5,
						'feed_margin_bottom' => 5,
						'feed_margin_left' => 5,
						'feed_margin_right' => 5,
						'feed_likes_bg_color' => '#000',
						'feed_likes_color' => '#fff',
						'feed_likes_padding_top_bottom' => '5',
						'feed_comments_bg_color' => '#000',
						'feed_comments_color' => '#fff',
						'feed_comments_padding_top_bottom' => '5',
						'feed_comments_padding_right_left' => '10',
						'show_feed_caption' => false,
						'feed_caption_background_color' => 'transparent',
						'caption_color' => '#000',
						'feed_caption_padding_top_bottom' => '10',
						'feed_caption_padding_right_left' => '10',
						'show_feed_external_link' => true,
						'feed_external_background_color' => '#000',
						'feed_external_color' => '#fff',
						'feed_external_padding_top_bottom' => '8',
						'feed_external_padding_right_left' => '10',
						'show_feed_open_popup_icon' => true,
						'popup_icon_bg_color' => '#000',
						'popup_icon_color' => '#fff',
						'feed_popup_icon_padding_top_bottom' => '8',
						'feed_popup_icon_padding_right_left' => '10',
						'show_feed_cta' => false,
						'feed_cta_text_color' => '#000',
						'feed_cta_text_hover_color' => '#000',
						'feed_time_text_color' => '#000',
						'feed_hover_bg_color' => 'rgba(0,0,0,0.5)',
						'feed_seprator_color' => '#ccc',
						'feed_border_size' => '1',
						'feed_border_style' => 'solid',
						'feed_likes_padding_right_left' => '10',
						'show_no_of_feeds' => true,
						'show_no_of_followers' => true,
						'show_bio' => true,
						'show_follow_btn' => true,
						'show_load_more_btn' => true
					)
			);

		return $default_val_arr;

	}/* fta_skin_default_values Method ends here. */ 


}/* Class ends here. */ 
/*
* Globalising class to get functionality on other files.
*/
$MIF_SKINS  = new MIF_SKINS();
endif;	