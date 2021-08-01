<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Overwrite parent theme background defaults and registers support for WordPress features.
add_action( 'after_setup_theme', 'lalita_background_setup' );
function lalita_background_setup() {
	add_theme_support( "custom-background",
		array(
			'default-color' 		 => '151515',
			'default-image'          => '',
			'default-repeat'         => 'repeat',
			'default-position-x'     => 'left',
			'default-position-y'     => 'top',
			'default-size'           => 'auto',
			'default-attachment'     => '',
			'wp-head-callback'       => '_custom_background_cb',
			'admin-head-callback'    => '',
			'admin-preview-callback' => ''
		)
	);
}

// Overwrite theme URL
function lalita_theme_uri_link() {
	return 'https://wpkoi.com/havan-wpkoi-wordpress-theme/';
}

// Overwrite parent theme's blog header function
add_action( 'lalita_after_header', 'lalita_blog_header_image', 11 );
function lalita_blog_header_image() {

	if ( ( is_front_page() && is_home() ) || ( is_home() ) ) { 
		$blog_header_image 			=  lalita_get_setting( 'blog_header_image' ); 
		$blog_header_title 			=  lalita_get_setting( 'blog_header_title' ); 
		$blog_header_text 			=  lalita_get_setting( 'blog_header_text' ); 
		$blog_header_button_text 	=  lalita_get_setting( 'blog_header_button_text' ); 
		$blog_header_button_url 	=  lalita_get_setting( 'blog_header_button_url' ); 
		if ( $blog_header_image != '' ) { ?>
		<div class="page-header-image grid-parent page-header-blog" style="background-image: url('<?php echo esc_url($blog_header_image); ?>') !important;">
        	<div class="page-header-noiseoverlay"></div>
        	<div class="page-header-blog-inner">
                <div class="page-header-blog-content-h grid-container">
                    <div class="page-header-blog-content">
                    <?php if ( $blog_header_title != '' ) { ?>
                        <div class="page-header-blog-text">
                            <?php if ( $blog_header_title != '' ) { ?>
                            <h2><?php echo wp_kses_post( $blog_header_title ); ?></h2>
                            <div class="clearfix"></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    </div>
                </div>
                <div class="page-header-blog-content page-header-blog-content-b">
                	<?php if ( $blog_header_text != '' ) { ?>
                	<div class="page-header-blog-text">
						<?php if ( $blog_header_title != '' ) { ?>
                        <p><?php echo wp_kses_post( $blog_header_text ); ?></p>
                        <div class="clearfix"></div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <div class="page-header-blog-button">
                        <?php if ( $blog_header_button_text != '' ) { ?>
                        <a class="read-more button" href="<?php echo esc_url( $blog_header_button_url ); ?>"><?php echo esc_html( $blog_header_button_text ); ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
		</div>
		<?php
		}
	}
}

// The dynamic styles of the parent theme added inline to the parent stylesheet.
// For the customizer functions it is better to enqueue after the child theme stylesheet.
if ( ! function_exists( 'havan_remove_parent_dynamic_css' ) ) {
	add_action( 'init', 'havan_remove_parent_dynamic_css' );
	function havan_remove_parent_dynamic_css() {
		remove_action( 'wp_enqueue_scripts', 'lalita_enqueue_dynamic_css', 50 );
	}
}

// Enqueue this CSS after the child stylesheet, not after the parent stylesheet.
if ( ! function_exists( 'havan_enqueue_parent_dynamic_css' ) ) {
	add_action( 'wp_enqueue_scripts', 'havan_enqueue_parent_dynamic_css', 50 );
	function havan_enqueue_parent_dynamic_css() {
		$css = lalita_base_css() . lalita_font_css() . lalita_advanced_css() . lalita_spacing_css() . lalita_no_cache_dynamic_css();

		// escaped secure before in parent theme
		wp_add_inline_style( 'lalita-child', $css );
	}
}

// Extra cutomizer functions
if ( ! function_exists( 'havan_customize_register' ) ) {
	add_action( 'customize_register', 'havan_customize_register' );
	function havan_customize_register( $wp_customize ) {
				
		// Add Havan customizer section
		$wp_customize->add_section(
			'havan_layout_effects',
			array(
				'title' => __( 'Havan Effects', 'havan' ),
				'priority' => 1,
				'panel' => 'lalita_layout_panel'
			)
		);
		
		// Havan borders
		$wp_customize->add_setting(
			'havan_settings[nav_border]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'havan_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'havan_settings[nav_border]',
			array(
				'type' => 'select',
				'label' => __( 'Havan extra borders', 'havan' ),
				'choices' => array(
					'enable' => __( 'Enable', 'havan' ),
					'disable' => __( 'Disable', 'havan' )
				),
				'settings' => 'havan_settings[nav_border]',
				'section' => 'havan_layout_effects',
				'priority' => 1
			)
		);
		
		// Footer widgets
		$wp_customize->add_setting(
			'havan_settings[footer_widgets]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'havan_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'havan_settings[footer_widgets]',
			array(
				'type' => 'select',
				'label' => __( 'Havan footer widgets', 'havan' ),
				'choices' => array(
					'enable' => __( 'Enable', 'havan' ),
					'disable' => __( 'Disable', 'havan' )
				),
				'settings' => 'havan_settings[footer_widgets]',
				'section' => 'havan_layout_effects',
				'priority' => 1
			)
		);
		
		// Nicescroll
		$wp_customize->add_setting(
			'havan_settings[nicescroll]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'havan_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'havan_settings[nicescroll]',
			array(
				'type' => 'select',
				'label' => __( 'Havan scrollbar style', 'havan' ),
				'choices' => array(
					'enable' => __( 'Enable', 'havan' ),
					'disable' => __( 'Disable', 'havan' )
				),
				'settings' => 'havan_settings[nicescroll]',
				'section' => 'havan_layout_effects',
				'priority' => 20
			)
		);
		
		// Logo effect
		$wp_customize->add_setting(
			'havan_settings[logoeffect]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'havan_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'havan_settings[logoeffect]',
			array(
				'type' => 'select',
				'label' => __( 'Logo effect', 'havan' ),
				'choices' => array(
					'enable' => __( 'Enable', 'havan' ),
					'disable' => __( 'Disable', 'havan' )
				),
				'settings' => 'havan_settings[logoeffect]',
				'section' => 'havan_layout_effects',
				'priority' => 20
			)
		);
		
		// Cursor
		$wp_customize->add_setting(
			'havan_settings[cursor]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'havan_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'havan_settings[cursor]',
			array(
				'type' => 'select',
				'label' => __( 'Cursor effect', 'havan' ),
				'choices' => array(
					'enable' => __( 'Enable', 'havan' ),
					'disable' => __( 'Disable', 'havan' )
				),
				'settings' => 'havan_settings[cursor]',
				'section' => 'havan_layout_effects',
				'priority' => 20
			)
		);
	}
}

//Sanitize choices.
if ( ! function_exists( 'havan_sanitize_choices' ) ) {
	function havan_sanitize_choices( $input, $setting ) {
		// Ensure input is a slug
		$input = sanitize_key( $input );

		// Get list of choices from the control
		// associated with the setting
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it;
		// otherwise, return the default
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
}

//Adds custom classes to the array of body classes.
if ( ! function_exists( 'havan_body_classes' ) ) {
	add_filter( 'body_class', 'havan_body_classes' );
	function havan_body_classes( $classes ) {
		// Get Customizer settings
		$havan_settings = get_option( 'havan_settings' );
		
		$nav_border  	 = 'enable';
		$footer_widgets  = 'enable';
		$nicescroll  	 = 'enable';
		$cursor			 = 'enable';
		$logoeffect		 = 'enable';
		
		if ( isset( $havan_settings['nav_border'] ) ) {
			$nav_border = $havan_settings['nav_border'];
		}
		
		if ( isset( $havan_settings['footer_widgets'] ) ) {
			$footer_widgets = $havan_settings['footer_widgets'];
		}
		
		if ( isset( $havan_settings['nicescroll'] ) ) {
			$nicescroll = $havan_settings['nicescroll'];
		}
		
		if ( isset( $havan_settings['cursor'] ) ) {
			$cursor = $havan_settings['cursor'];
		}
		
		if ( isset( $havan_settings['logoeffect'] ) ) {
			$logoeffect = $havan_settings['logoeffect'];
		}
		
		// Navigation border
		if ( $nav_border != 'disable' ) {
			$classes[] = 'havan-nav-border';
		}
		
		// Footer widgets
		if ( $footer_widgets != 'disable' ) {
			$classes[] = 'havan-footer-widgets';
		}
		
		// Scrollbar style function
		if ( $nicescroll != 'disable' ) {
			$classes[] = 'havan-scrollbar-style';
		}
		
		// Mouse style function
		if ( $cursor != 'disable' ) {
			$classes[] = 'havan-cursor-style';
		}
		
		// Logo effect function
		if ( $logoeffect != 'disable' ) {
			$classes[] = 'havan-logo-effect';
		}
		
		return $classes;
	}
}

// Enqueue scripts
if ( ! function_exists( 'havan_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'havan_scripts' );
	function havan_scripts() {
		
		$havan_settings = get_option( 'havan_settings' );
		$cursor		 = 'enable';
		if ( isset( $havan_settings['cursor'] ) ) {
			$cursor = $havan_settings['cursor'];
		}
		
		if ( $cursor != 'disable' ) {
			wp_enqueue_style( 'havan-magic-mouse', esc_url( get_stylesheet_directory_uri() ) . "/css/magic-mouse.min.css", false, LALITA_VERSION, 'all' );
			wp_enqueue_script( 'havan-magic-mouse', esc_url( get_stylesheet_directory_uri() ) . "/js/magic-mouse.min.js", array( 'jquery'), LALITA_VERSION, true );
		}
		
		wp_enqueue_script( 'havan-search-keyboard-navigation', esc_url( get_stylesheet_directory_uri() ) . "/js/search-keyboard-navigation.js", array( 'jquery'), LALITA_VERSION, true );
	}
}

//Add holder div if havan borders are enabled
if ( ! function_exists( 'havan_border_holders' ) ) {
	add_action( 'lalita_after_footer', 'havan_border_holders', 5 );
	function havan_border_holders() { 
		$havan_settings = get_option( 'havan_settings' );
		$nav_border		 = 'enable';
		if ( isset( $havan_settings['nav_border'] ) ) {
			$nav_border = $havan_settings['nav_border'];
		}
		
		if ( $nav_border != 'disable' ) { ?>
			<div class="havan-border-holders-l"></div>
			<div class="havan-border-holders-r"></div>
		<?php
		}
	}
}