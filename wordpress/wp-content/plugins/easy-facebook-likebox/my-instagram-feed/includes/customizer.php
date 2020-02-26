<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//======================================================================//
// Customizer code Of Feed Them All //
//======================================================================//

if ( !class_exists( "MIF_CUSTOMIZER" ) ) {
    class MIF_CUSTOMIZER
    {
        /*
         * __construct initialize all function of this class.
         * Returns nothing. 
         * Used action_hooks to get things sequentially.
         */
        function __construct()
        {
            /*
             * customize_register hook will add custom files in customizer.
             */
            add_action( 'customize_register', array( $this, 'mif_customizer' ) );
            /*
             * customize_preview_init hook will add our js file in customizer.
             */
            add_action( 'customize_preview_init', array( $this, 'mif_live_preview' ) );
            /*
             * customize_preview_init hook will add our js file in customizer.
             */
            add_action( 'customize_controls_enqueue_scripts', array( $this, 'mif_customizer_scripts' ) );
        }
        
        /* __construct Method ends here. */
        /*
         * fta_customizer_scripts holds cutomizer files.
         */
        function mif_customizer_scripts()
        {
            /*
             * Enqueing customizer style file.
             */
            if ( !wp_style_is( 'mif_customizer_style', 'enqueued' ) ) {
                wp_enqueue_style( 'mif_customizer_style', MIF_PLUGIN_URL . 'assets/css/mif_customizer_style.css' );
            }
        }
        
        /* fta_customizer_scripts Method ends here. */
        /*
         * fta_customizer holds code for customizer area.
         */
        public function mif_customizer( $wp_customize )
        {
            $FTA = new Feed_Them_All();
            $fta_settings = $FTA->fta_get_settings();
            /* Getting the skin id from URL and saving in option for confliction.*/
            
            if ( isset( $_GET['mif_skin_id'] ) ) {
                $skin_id = $_GET['mif_skin_id'];
                update_option( 'efbl_skin_id', $skin_id );
            }
            
            /* Getting the skin id from URL and saving in option for confliction.*/
            
            if ( isset( $_GET['mif_account_id'] ) ) {
                $mif_account_id = $_GET['mif_account_id'];
                update_option( 'mif_account_id', $mif_account_id );
            }
            
            /* Getting back the skin saved ID.*/
            $skin_id = get_option( 'efbl_skin_id', false );
            /* Adding Feed Them All Panel in customizer.*/
            $wp_customize->add_panel( 'mif_skins_panel', array(
                'title' => __( 'Easy Instagram Feed', $FTA->plug_slug ),
            ) );
            /*
             * Getting all the section for Feed Them All Panel.
             */
            $mif_skins_sections = $this->mif_skins_sections();
            /*
             * Checking if any section exists, Adding into customize manager factory one by one. Use fta_skins_sections filter to add or remove your own sections.
             */
            if ( isset( $mif_skins_sections ) ) {
                foreach ( $mif_skins_sections as $section ) {
                    $wp_customize->add_section( $section['id'], array(
                        'title'       => $section['title'],
                        'description' => $section['description'],
                        'priority'    => 100,
                        'panel'       => 'mif_skins_panel',
                    ) );
                }
            }
            /*
             * Getting all the Settings for Feed Them All.
             */
            $mif_skins_settings = $this->mif_skins_settings();
            /*
             * Checking if any setting exists, Adding into customize manager factory one by one. Use fta_skins_settings filter to add or remove your own settings.
             */
            if ( isset( $mif_skins_settings ) ) {
                foreach ( $mif_skins_settings as $setting ) {
                    /*
                     * Getting the type of setting.
                     */
                    $type = $setting['type'];
                    /*
                     * Getting the ID of setting.
                     */
                    $id = $setting['id'];
                    /*
                     * Adding the settings according to the type.
                     */
                    switch ( $type ) {
                        /* If setting type is radio or selectbox. */
                        case "select":
                        case "radio":
                            $transport = 'postMessage';
                            if ( 'mif_skin_' . $skin_id . '[layout_option]' == $id ) {
                                $transport = 'refresh';
                            }
                            // echo "<pre>"; print_r($mif_skins_settings);exit();
                            $wp_customize->add_setting( $id, array(
                                'default'   => $setting['default'],
                                'transport' => $transport,
                                'type'      => 'option',
                            ) );
                            /* Adding control of number of columns if layout set to grid.*/
                            $wp_customize->add_control( $id, array(
                                'label'       => $setting['label'],
                                'section'     => $setting['section'],
                                'settings'    => $id,
                                'description' => $setting['description'],
                                'type'        => $type,
                                'choices'     => $setting['choices'],
                            ) );
                            break;
                            /* If setting type is checkbox. */
                        /* If setting type is checkbox. */
                        case "checkbox":
                            $wp_customize->add_setting( $id, array(
                                'default'   => $setting['default'],
                                'transport' => 'postMessage',
                                'type'      => 'option',
                            ) );
                            /* Adding control of show or hide Follow Button.*/
                            $wp_customize->add_control( $id, array(
                                'label'       => $setting['label'],
                                'section'     => $setting['section'],
                                'settings'    => $id,
                                'description' => $setting['description'],
                                'type'        => $type,
                            ) );
                            break;
                            /* If setting type is Color Picker. */
                        /* If setting type is Color Picker. */
                        case "color_picker":
                            $wp_customize->add_setting( $id, array(
                                'default'   => $setting['default'],
                                'transport' => 'postMessage',
                                'type'      => 'option',
                            ) );
                            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
                                'label'       => $setting['label'],
                                'section'     => $setting['section'],
                                'settings'    => $id,
                                'description' => $setting['description'],
                            ) ) );
                            break;
                            /* If setting type is Range. */
                        /* If setting type is Range. */
                        case "range":
                            $wp_customize->add_setting( $id, array(
                                'default'   => $setting['default'],
                                'transport' => 'postMessage',
                                'type'      => 'option',
                            ) );
                            /* Adding control of show or hide Follow Button.*/
                            $wp_customize->add_control( $id, array(
                                'label'       => $setting['label'],
                                'section'     => $setting['section'],
                                'settings'    => $id,
                                'description' => $setting['description'],
                                'type'        => $type,
                                'input_attrs' => $setting['input_attrs'],
                            ) );
                            break;
                            /* If setting type is Color Picker with opacity. */
                        /* If setting type is Color Picker with opacity. */
                        case "color_picker_alpha":
                            $wp_customize->add_setting( $id, array(
                                'default'   => $setting['default'],
                                'transport' => 'postMessage',
                                'type'      => 'option',
                            ) );
                            /* Adding control of show or hide Follow Button.*/
                            $wp_customize->add_control( new Customize_Alpha_Color_Control( $wp_customize, $id, array(
                                'label'        => $setting['label'],
                                'section'      => $setting['section'],
                                'settings'     => $id,
                                'description'  => $setting['description'],
                                'type'         => $type,
                                'show_opacity' => $setting['show_opacity'],
                                'palette'      => $setting['palette'],
                            ) ) );
                            break;
                            /* If setting type is popup. */
                        /* If setting type is popup. */
                        case "popup":
                            $wp_customize->add_control( new Customize_MIF_PopUp( $wp_customize, $id, array(
                                'label'       => $setting['label'],
                                'settings'    => array(),
                                'section'     => $setting['section'],
                                'description' => $setting['description'],
                                'icon'        => $setting['icon'],
                                'popup_id'    => $id,
                            ) ) );
                            break;
                            /* If setting type is not defined add a text field. */
                        /* If setting type is not defined add a text field. */
                        default:
                            $wp_customize->add_setting( $id, array(
                                'default'   => $setting['default'],
                                'transport' => 'postMessage',
                                'type'      => 'option',
                            ) );
                            /* Adding control of number of columns if layout set to grid.*/
                            $wp_customize->add_control( $id, array(
                                'label'       => $setting['label'],
                                'section'     => $setting['section'],
                                'settings'    => $id,
                                'description' => $setting['description'],
                                'type'        => $type,
                            ) );
                    }
                    /* Switch statement ends here. */
                }
            }
            /* Settings Loop ends here. */
        }
        
        /* fta_customizer Method ends here. */
        /*
         * fta_skins_settings holds All the settings of Feed Them All Skins
         */
        private function mif_skins_sections()
        {
            /*
             * Calling main method of plugin
             */
            $FTA = new Feed_Them_All();
            /*
             * All the scetions for FTA Skins.
             */
            $sections = array(
                'mif_layout' => array(
                'id'          => 'mif_layout',
                'title'       => __( 'Layout', $FTA->plug_slug ),
                'description' => __( 'Select the Layout in real time.', $FTA->plug_slug ),
                'priority'    => 35,
            ),
                'mif_header' => array(
                'id'          => 'mif_header',
                'title'       => __( 'Header', $FTA->plug_slug ),
                'description' => __( 'Customize the Header In Real Time', $FTA->plug_slug ),
                'priority'    => 35,
            ),
                'mif_feed'   => array(
                'id'          => 'mif_feed',
                'title'       => __( 'Feed', $FTA->plug_slug ),
                'description' => __( 'Customize the Single Feed Design In Real Time', $FTA->plug_slug ),
                'priority'    => 35,
            ),
            );
            /*
             * Use fta_skins_sections filter to add new sections into the skins.
             * Returning back all the sections.
             */
            return $sections = apply_filters( 'mif_skins_sections', $sections );
        }
        
        /* fta_skins_sections Method ends here. */
        /*
         * mif_skins_sections holds All the settings of Feed Them All Skins
         */
        private function mif_skins_settings()
        {
            /*
             * Calling main method of plugin
             */
            $FTA = new Feed_Them_All();
            /* Getting the skin id from URL and saving in option for confliction.*/
            
            if ( isset( $_GET['mif_skin_id'] ) ) {
                $skin_id = $_GET['mif_skin_id'];
                update_option( 'mif_skin_id', $skin_id );
            }
            
            /* Getting back the skin saved ID.*/
            $skin_id = get_option( 'mif_skin_id', false );
            /*
             * Adding all the settings
             */
            $settings = array();
            $mif_layout = 'mif_layout';
            if ( efl_fs()->is_plan( 'instagram_premium', true ) or efl_fs()->is_plan( 'combo_premium', true ) ) {
                $mif_layout = 'mif_layoutdsa';
            }
            if ( !efl_fs()->is_plan( 'instagram_premium', true ) or !efl_fs()->is_plan( 'combo_premium', true ) ) {
                $settings['mif_layout_popup'] = array(
                    'id'          => 'mif_layout_popup',
                    'icon'        => 'web',
                    'label'       => __( 'Layouts', $FTA->plug_slug ),
                    'section'     => $mif_layout,
                    'description' => __( "We're sorry, changing layouts is not included in your plan. Please upgrade to premium version to unlock this and all other cool features. <a target=_blank href=https://maltathemes.com/my-instagram-feed-demo/masonary>Check out the demo</a>", $FTA->plug_slug ),
                    'type'        => 'popup',
                );
            }
            // echo "<pre>"; print_r( $settings);exit();
            $settings['mif_skin_' . $skin_id . '[number_of_cols]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[number_of_cols]',
                'default'     => 3,
                'label'       => __( 'Number of columns', $FTA->plug_slug ),
                'section'     => 'mif_layout',
                'description' => __( "Select the number of columns for feeds i.e. works with Grid layout only.", $FTA->plug_slug ),
                'type'        => 'select',
                'choices'     => array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
            ),
            );
            $settings['mif_skin_' . $skin_id . '[show_follow_btn]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[show_follow_btn]',
                'default'     => true,
                'label'       => __( 'Show Follow Button', $FTA->plug_slug ),
                'section'     => 'mif_layout',
                'description' => __( 'Show or Hide follow button', $FTA->plug_slug ),
                'type'        => 'checkbox',
            );
            $settings['mif_skin_' . $skin_id . '[show_load_more_btn]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[show_load_more_btn]',
                'default'     => true,
                'label'       => __( 'Show Load More Button', $FTA->plug_slug ),
                'section'     => 'mif_layout',
                'description' => __( 'Show or Hide load more button', $FTA->plug_slug ),
                'type'        => 'checkbox',
            );
            $settings['mif_skin_' . $skin_id . '[show_header]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[show_header]',
                'default'     => false,
                'label'       => __( 'Show Header', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Show or Hide header.', $FTA->plug_slug ),
                'type'        => 'checkbox',
            );
            $settings['mif_skin_' . $skin_id . '[header_background_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_background_color]',
                'default'     => '#000',
                'label'       => __( 'Header Background Color', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the background color of header.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[header_text_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_text_color]',
                'default'     => '#000',
                'label'       => __( 'Header Text Color', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the content color which are displaying in header.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[title_size]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[title_size]',
                'default'     => '16',
                'label'       => __( 'Title Size', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the text size of profile name.', $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[show_dp]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[show_dp]',
                'default'     => true,
                'label'       => __( 'Show Display Picture', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Show or Hide display picture of your account which are displaying in header.', $FTA->plug_slug ),
                'type'        => 'checkbox',
            );
            $settings['mif_skin_' . $skin_id . '[header_round_dp]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_round_dp]',
                'default'     => true,
                'label'       => __( 'Round Display Picture', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Show rounded or boxed display picture', $FTA->plug_slug ),
                'type'        => 'checkbox',
            );
            $settings['mif_skin_' . $skin_id . '[header_dp_hover_color]'] = array(
                'id'           => 'mif_skin_' . $skin_id . '[header_dp_hover_color]',
                'default'      => 'rgba(0,0,0,0.5)',
                'label'        => __( 'Display Picture Hover Shadow Color', $FTA->plug_slug ),
                'section'      => 'mif_header',
                'description'  => __( "Select the shadow color which shows on dispaly picture's hover.", $FTA->plug_slug ),
                'type'         => 'color_picker_alpha',
                'show_opacity' => true,
                'palette'      => array(
                'rgb(0, 0, 0)',
                'rgb(255, 255, 255)',
                'rgb(221, 51, 51)',
                'rgb(221, 153, 51)',
                'rgb(238, 238, 34)',
                'rgb(129, 215, 66)',
                'rgb(30, 115, 190)',
                'rgb(130, 36, 227)'
            ),
            );
            $settings['mif_skin_' . $skin_id . '[header_dp_hover_icon_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_dp_hover_icon_color]',
                'default'     => '#fff',
                'label'       => __( 'Display Picture Hover Icon color', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the icon color which shows on display picture hover.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[show_no_of_feeds]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[show_no_of_feeds]',
                'default'     => true,
                'label'       => __( 'Show Total Number Of Feeds', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Show or Hide total number of feeds which are displaying in header.', $FTA->plug_slug ),
                'type'        => 'checkbox',
            );
            $settings['mif_skin_' . $skin_id . '[show_no_of_followers]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[show_no_of_followers]',
                'default'     => true,
                'label'       => __( 'Show Total Number Of Followers', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Show or Hide Total Number Of Followers Which are displaying in header.', $FTA->plug_slug ),
                'type'        => 'checkbox',
            );
            $settings['mif_skin_' . $skin_id . '[metadata_size]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[metadata_size]',
                'default'     => '16',
                'label'       => __( 'Size of Total Posts And Followers', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the text size of total posts and followers which are displaying in header.', $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[show_bio]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[show_bio]',
                'default'     => true,
                'label'       => __( 'Show Bio', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Show or Hide Bio', $FTA->plug_slug ),
                'type'        => 'checkbox',
            );
            $settings['mif_skin_' . $skin_id . '[bio_size]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[bio_size]',
                'default'     => '14',
                'label'       => __( 'Text Size of Bio', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the text size of bio.', $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[header_border_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_border_color]',
                'default'     => '#ccc',
                'label'       => __( 'Header Border Color', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the border color of header.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[header_border_style]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_border_style]',
                'default'     => 'none',
                'label'       => __( 'Border Style', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( "Select the border style to make header look more nicer.", $FTA->plug_slug ),
                'type'        => 'select',
                'choices'     => array(
                'solid'  => 'Solid',
                'dashed' => 'Dashed',
                'dotted' => 'Dotted',
                'double' => 'Double',
                'groove' => 'Groove',
                'ridge'  => 'Ridge',
                'inset'  => 'Inset',
                'outset' => 'Outset',
                'none'   => 'None',
            ),
            );
            $settings['mif_skin_' . $skin_id . '[header_border_top]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_border_top]',
                'default'     => 0,
                'label'       => __( 'Border Top', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the border size for top side to make header look more nicer.', $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[header_border_bottom]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_border_bottom]',
                'default'     => 1,
                'label'       => __( 'Border Bottom', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the border size for Bottom side to make header look more nicer.', $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[header_border_left]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_border_left]',
                'default'     => 0,
                'label'       => __( 'Border Left', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the border size for left side to make header look more nicer.', $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[header_border_right]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_border_right]',
                'default'     => 0,
                'label'       => __( 'Border Right', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the border size for right side to make header look more nicer', $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[header_padding_top]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_padding_top]',
                'default'     => 10,
                'label'       => __( 'Padding Top', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the padding for top side make header look more nicer.', $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[header_padding_bottom]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_padding_bottom]',
                'default'     => 10,
                'label'       => __( 'Padding Bottom', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the padding for bottom side to make header look more nicer.', $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[header_padding_left]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_padding_left]',
                'default'     => 10,
                'label'       => __( 'Padding Left', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the padding for left side to make header look more nicer.', $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[header_padding_right]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_padding_right]',
                'default'     => 10,
                'label'       => __( 'Padding Right', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Select the padding for right side to make header look more nicer.', $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['[mif_skin_' . $skin_id . '[header_align]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[header_align]',
                'default'     => 'left',
                'label'       => __( 'Align', $FTA->plug_slug ),
                'section'     => 'mif_header',
                'description' => __( 'Show the header content in center left or right to make header look more nicer.', $FTA->plug_slug ),
                'type'        => 'radio',
                'choices'     => array(
                'left'  => 'Left',
                'none'  => 'Center',
                'right' => 'Right',
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_background_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_background_color]',
                'default'     => 'transparent',
                'label'       => __( 'Background Color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the Background color of feed.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $mif_feed = 'mif_feed';
            if ( efl_fs()->is_plan( 'instagram_premium', true ) or efl_fs()->is_plan( 'combo_premium', true ) ) {
                $mif_feed = 'mif_feeddsa';
            }
            if ( !efl_fs()->is_plan( 'instagram_premium', true ) or !efl_fs()->is_plan( 'combo_premium', true ) ) {
                $settings['mif_feed_image_filter_popup'] = array(
                    'id'          => 'mif_feed_image_filter_popup',
                    'icon'        => 'color_lens',
                    'label'       => __( 'Image Filter', $FTA->plug_slug ),
                    'section'     => $mif_feed,
                    'description' => __( "We're sorry, Image Filter feature is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", $FTA->plug_slug ),
                    'type'        => 'popup',
                );
            }
            $settings['mif_skin_' . $skin_id . '[feed_padding_top]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_padding_top]',
                'default'     => 5,
                'label'       => __( 'Padding from top', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the padding top of feed.', $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_padding_bottom]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_padding_top_bottom]',
                'default'     => 5,
                'label'       => __( 'Padding from Bottom', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the padding bottom of feed.', $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_padding_left]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_padding_left]',
                'default'     => 5,
                'label'       => __( 'Padding Left', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the padding left for feed i.e. doesn't work with Masonary layout.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_padding_right]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_padding_right]',
                'default'     => 5,
                'label'       => __( 'Padding Right', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the padding right for feed i.e. doesn't work with Masonary layout.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_margin_top]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_margin_top]',
                'default'     => 5,
                'label'       => __( 'Margin Top', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the margin top of feed i.e. doesn't work with Masonary layout.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_margin_bottom]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_margin_bottom]',
                'default'     => 5,
                'label'       => __( 'Margin Bottom', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the margin bottom of feed i.e. doesn't work with Masonary layout.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_margin_left]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_margin_left]',
                'default'     => 5,
                'label'       => __( 'Margin Left', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the margin Left of feed i.e. doesn't work with Masonary layout.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_margin_right]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_margin_right]',
                'default'     => 5,
                'label'       => __( 'Margin Right', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the margin Right of feed i.e. doesn't work with Masonary layout.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            // echo '<pre>'; print_r($mif_feed);exit;
            if ( !efl_fs()->is_plan( 'instagram_premium', true ) or !efl_fs()->is_plan( 'combo_premium', true ) ) {
                $settings['mif_feed_show_likes_popup'] = array(
                    'id'          => 'mif_feed_show_likes_popup',
                    'icon'        => 'favorite_border',
                    'label'       => __( 'Show Hearts of feeds', $FTA->plug_slug ),
                    'section'     => $mif_feed,
                    'description' => __( "We're sorry, Show or hide hearts of feeds is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", $FTA->plug_slug ),
                    'type'        => 'popup',
                );
            }
            $settings['mif_skin_' . $skin_id . '[feed_likes_bg_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_likes_bg_color]',
                'default'     => '#000',
                'label'       => __( 'Likes Background Color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the background color of likes.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[feed_likes_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_likes_color]',
                'default'     => '#fff',
                'label'       => __( 'Likes Color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the color of likes.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[feed_likes_padding_top_bottom]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_likes_padding_top_bottom]',
                'default'     => 5,
                'label'       => __( 'Padding Top And Bottom', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the padding top and bottom for likes.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_likes_padding_right_left]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_likes_padding_right_left]',
                'default'     => 10,
                'label'       => __( 'Padding Left And Right', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the padding left and right for likes.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            if ( !efl_fs()->is_plan( 'instagram_premium', true ) or !efl_fs()->is_plan( 'combo_premium', true ) ) {
                $settings['mif_show_comments_popup'] = array(
                    'id'          => 'mif_show_comments_popup',
                    'icon'        => 'mode_comment',
                    'label'       => __( 'Show Comments of feeds', $FTA->plug_slug ),
                    'section'     => $mif_feed,
                    'description' => __( "We're sorry, Show or hide comments of feeds is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", $FTA->plug_slug ),
                    'type'        => 'popup',
                );
            }
            $settings['mif_skin_' . $skin_id . '[feed_comments_bg_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_comments_bg_color]',
                'default'     => '#000',
                'label'       => __( 'Comments Background Color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the background color of comments.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[feed_comments_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_comments_color]',
                'default'     => '#fff',
                'label'       => __( 'Comments Color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the color of comments.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[feed_comments_padding_top_bottom]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_comments_padding_top_bottom]',
                'default'     => 5,
                'label'       => __( 'Padding Top And Bottom', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the padding top and bottom for comments.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_comments_padding_right_left]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_comments_padding_right_left]',
                'default'     => 10,
                'label'       => __( 'Padding Left And Right', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the padding left and right for comments.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            if ( !efl_fs()->is_plan( 'instagram_premium', true ) or !efl_fs()->is_plan( 'combo_premium', true ) ) {
                $settings['mif_feed_caption_popup'] = array(
                    'id'          => 'mif_feed_caption_popup',
                    'icon'        => 'description',
                    'label'       => __( 'Show Feed Caption', $FTA->plug_slug ),
                    'section'     => $mif_feed,
                    'description' => __( "We're sorry, Show or hide caption of feeds is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", $FTA->plug_slug ),
                    'type'        => 'popup',
                );
            }
            $settings['mif_skin_' . $skin_id . '[feed_caption_background_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_caption_background_color]',
                'default'     => '#fff',
                'label'       => __( 'Caption Background Color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the background color of feed caption.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[caption_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[caption_color]',
                'default'     => '#000',
                'label'       => __( 'Caption Color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the feed caption color.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[feed_caption_padding_top_bottom]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_caption_padding_top_bottom]',
                'default'     => 10,
                'label'       => __( 'Padding Top And Bottom', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the padding top and bottom for captions.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_caption_padding_right_left]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_caption_padding_right_left]',
                'default'     => 10,
                'label'       => __( 'Padding Left And Right', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the padding left and right for caption.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            if ( !efl_fs()->is_plan( 'instagram_premium', true ) or !efl_fs()->is_plan( 'combo_premium', true ) ) {
                $settings['mif_feed_external_link_popup'] = array(
                    'id'          => 'mif_feed_external_link_popup',
                    'icon'        => 'link',
                    'label'       => __( 'Show External Link', $FTA->plug_slug ),
                    'section'     => $mif_feed,
                    'description' => __( "We're sorry, Show or external links is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", $FTA->plug_slug ),
                    'type'        => 'popup',
                );
            }
            $settings['mif_skin_' . $skin_id . '[feed_external_background_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_external_background_color]',
                'default'     => '#000',
                'label'       => __( 'External Link Background Color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the background color Of External Link Icon.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[feed_external_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_external_color]',
                'default'     => '#fff',
                'label'       => __( 'External Link Color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the color Of External Link Icon.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[feed_external_padding_top_bottom]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_external_padding_top_bottom]',
                'default'     => 8,
                'label'       => __( 'Padding Top And Bottom', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the padding top and bottom for external link icon.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_external_padding_right_left]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_external_padding_right_left]',
                'default'     => 10,
                'label'       => __( 'Padding Left And Right', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the padding left and right for external link icon.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            if ( !efl_fs()->is_plan( 'instagram_premium', true ) or !efl_fs()->is_plan( 'combo_premium', true ) ) {
                $settings['mif_feed_open_popup_icon_popup'] = array(
                    'id'          => 'mif_feed_open_popup_icon_popup',
                    'icon'        => 'add',
                    'label'       => __( 'Show Open PopUp Icon', $FTA->plug_slug ),
                    'section'     => $mif_feed,
                    'description' => __( "We're sorry, Show or hide open popup icon is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", $FTA->plug_slug ),
                    'type'        => 'popup',
                );
            }
            $settings['mif_skin_' . $skin_id . '[popup_icon_bg_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[popup_icon_bg_color]',
                'default'     => '#000',
                'label'       => __( 'Open PopUp Icon background color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the icon background color which shows on feed hover to open popup.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[popup_icon_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[popup_icon_color]',
                'default'     => '#fff',
                'label'       => __( 'Open PopUp Icon color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the icon color which shows on feed hover to open popup.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[feed_popup_icon_padding_top_bottom]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_popup_icon_padding_top_bottom]',
                'default'     => 8,
                'label'       => __( 'Padding Top And Bottom', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the padding top and bottom for open popup icon.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_popup_icon_padding_right_left]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_popup_icon_padding_right_left]',
                'default'     => 10,
                'label'       => __( 'Padding Left And Right', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the padding left and right for open popup icon.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            if ( !efl_fs()->is_plan( 'instagram_premium', true ) or !efl_fs()->is_plan( 'combo_premium', true ) ) {
                $settings['mif_feed_cta_popup'] = array(
                    'id'          => 'mif_feed_cta_popup',
                    'icon'        => 'favorite_border',
                    'label'       => __( 'Show Feed Call To Action Buttons', $FTA->plug_slug ),
                    'section'     => $mif_feed,
                    'description' => __( "We're sorry, Show or hide call to action buttons is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", $FTA->plug_slug ),
                    'type'        => 'popup',
                );
            }
            $settings['mif_skin_' . $skin_id . '[feed_cta_text_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_cta_text_color]',
                'default'     => '#000',
                'label'       => __( 'Call To Action color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the color of links like(Share and View on Instagram).', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[feed_cta_text_hover_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_cta_text_hover_color]',
                'default'     => '#000',
                'label'       => __( 'Call To Action Hover color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the hover color of links like(Share and View on Instagram).', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[feed_time_text_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_time_text_color]',
                'default'     => '#000',
                'label'       => __( 'Feed Time Color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the color of feed created time.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[feed_hover_bg_color]'] = array(
                'id'           => 'mif_skin_' . $skin_id . '[feed_hover_bg_color]',
                'default'      => 'rgba(0,0,0,0.5)',
                'label'        => __( 'Feed Hover Shadow Color', $FTA->plug_slug ),
                'section'      => 'mif_feed',
                'description'  => __( "Select the shadow color which shows on feed hover.", $FTA->plug_slug ),
                'type'         => 'color_picker_alpha',
                'show_opacity' => true,
                'palette'      => array(
                'rgb(0, 0, 0)',
                'rgb(255, 255, 255)',
                'rgb(221, 51, 51)',
                'rgb(221, 153, 51)',
                'rgb(238, 238, 34)',
                'rgb(129, 215, 66)',
                'rgb(30, 115, 190)',
                'rgb(130, 36, 227)'
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_seprator_color]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_seprator_color]',
                'default'     => '#ccc',
                'label'       => __( 'Feed Seprator Color', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( 'Select the color of feed Seprator.', $FTA->plug_slug ),
                'type'        => 'color_picker',
            );
            $settings['mif_skin_' . $skin_id . '[feed_border_size]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_border_size]',
                'default'     => 1,
                'label'       => __( 'Border Size', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the border size for feeds.", $FTA->plug_slug ),
                'type'        => 'range',
                'input_attrs' => array(
                'min' => 0,
                'max' => 100,
            ),
            );
            $settings['mif_skin_' . $skin_id . '[feed_border_style]'] = array(
                'id'          => 'mif_skin_' . $skin_id . '[feed_border_style]',
                'default'     => 'solid',
                'label'       => __( 'Border Style', $FTA->plug_slug ),
                'section'     => 'mif_feed',
                'description' => __( "Select the border style for feeds.", $FTA->plug_slug ),
                'type'        => 'select',
                'choices'     => array(
                'solid'  => 'Solid',
                'dashed' => 'Dashed',
                'dotted' => 'Dotted',
                'double' => 'Double',
                'groove' => 'Groove',
                'ridge'  => 'Ridge',
                'inset'  => 'Inset',
                'outset' => 'Outset',
                'none'   => 'None',
            ),
            );
            /*
             * Use mif_skins_settings filter to add new settings into the skins.
             * Returning back all the settings.
             */
            return $settings = apply_filters( 'mif_skins_settings', $settings, $skin_id );
        }
        
        /* mif_skins_sections Method ends here. */
        /**
         * Used by hook: 'customize_preview_init'
         * 
         * @see add_action('customize_preview_init',$func)
         */
        public function mif_live_preview()
        {
            /* Getting saved skin id. */
            $skin_id = get_option( 'mif_skin_id', false );
            /* Enqueing script for displaying live changes. */
            wp_enqueue_script(
                'mif_live_preview',
                MIF_PLUGIN_URL . 'assets/js/mif_live_preview.js',
                array( 'jquery', 'customize-preview' ),
                true
            );
            /* Localizing script for getting skin id in js. */
            wp_localize_script( 'mif_live_preview', 'mif_skin_id', $skin_id );
        }
    
    }
    /* FTA_customizer Class ends here. */
    $MIF_CUSTOMIZER = new MIF_CUSTOMIZER();
}
