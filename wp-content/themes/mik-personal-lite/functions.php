<?php
/**
 * Theme functions and definitions
 *
 * @package mik_personal_lite
 */ 

if ( ! function_exists( 'mik_personal_lite_enqueue_styles' ) ) :
	/**
	 * Load assets.
	 *
	 * @since 1.0.0
	 */
	function mik_personal_lite_enqueue_styles() {
		wp_enqueue_style( 'mik-style-parent', get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'mik-personal-lite-style', get_stylesheet_directory_uri() . '/style.css', array( 'mik-style-parent' ), '1.0.0' );
	}
endif;
add_action( 'wp_enqueue_scripts', 'mik_personal_lite_enqueue_styles', 99 );

function mik_personal_lite_remove_action() {
	remove_action( 'mik_header_start_action', 'mik_header_start', 10 );
	remove_action( 'mik_primary_content_action', 'mik_add_slider_section', 10 );
	remove_action( 'mik_primary_content_action', 'mik_add_introduction_section', 10 );
    remove_action( 'mik_primary_content_action', 'mik_add_featured_section', 10 );
    remove_action( 'mik_primary_content_action', 'mik_add_cta_section', 10 );

    add_action( 'mik_primary_content_action', 'mik_add_slider_section', 10 );
    add_action( 'mik_primary_content_action', 'mik_add_introduction_section', 20 );
    add_action( 'mik_primary_content_action', 'mik_add_cta_section', 40 );
}
add_action( 'init', 'mik_personal_lite_remove_action');

if ( ! function_exists( 'mik_personal_lite_theme_defaults' ) ) :
    /**
     * Customize theme defaults.
     *
     * @since 1.0.0
     *
     * @param array $defaults Theme defaults.
     * @param array Custom theme defaults.
     */
    function mik_personal_lite_theme_defaults( $defaults ) {
        $defaults['enable_slider'] = false;
        $defaults['blog_column_type'] = 'column-3';

        return $defaults;
    }
endif;
add_filter( 'mik_default_theme_options', 'mik_personal_lite_theme_defaults', 99 );

if ( ! function_exists( 'mik_personal_lite_header_start' ) ) :
	/**
	 * Header starts html codes
	 *
	 * @since Mik 1.0.0
	 */
	function mik_personal_lite_header_start() { 
        $slider_enable = mik_theme_option('enable_slider', false );
		?>
		<header id="masthead" class="site-header left-align ?>">
		<div class="wrapper">
	<?php }
endif;
add_action( 'mik_header_start_action', 'mik_personal_lite_header_start', 10 );

if ( ! function_exists( 'mik_personal_lite_add_featured_section' ) ) :
    /**
    * Add featured section
    *
    *@since Mik 1.0.0
    */
    function mik_personal_lite_add_featured_section() {

        // Check if featured is enabled on frontpage
        $featured_enable = apply_filters( 'mik_section_status', 'enable_featured', '' );

        if ( ! $featured_enable )
            return false;

        if ( ! is_singular() ) {
            $paged = get_query_var( 'paged' );
            if ( $paged !== 0 )
                return false;
        }

        // Get featured section details
        $section_details = array();
        $section_details = apply_filters( 'mik_filter_featured_section_details', $section_details );

        if ( empty( $section_details ) ) 
            return;

        // Render featured section now.
        mik_personal_lite_render_featured_section( $section_details );
    }
endif;
add_action( 'mik_primary_content_action', 'mik_personal_lite_add_featured_section', 30 );

if ( ! function_exists( 'mik_personal_lite_render_featured_section' ) ) :
  /**
   * Start featured section
   *
   * @return string featured content
   * @since Mik 1.0.0
   *
   */
   function mik_personal_lite_render_featured_section( $content_details = array() ) {
        if ( empty( $content_details ) )
            return;

        $dot = mik_theme_option( 'enable_featured_dot' );
        $title = mik_theme_option( 'featured_title', '' );
        ?>
        <div id="featured-posts" class="relative homepage-section">
            <div class="wrapper page-section">
                <?php if ( ! empty( $title ) ) : ?>
                    <header class="page-header">
                        <h2 class="section-title <?php echo $dot ? 'add-separator' : ''; ?>"><?php echo esc_html( $title ); ?></h2>
                    </header>
                <?php endif; ?>

                <div class="section-content left-align column-3">
                    <?php foreach ( $content_details as $content ) : ?>
                            <article class="hentry">
                                <div class="post-wrapper">
                                    <?php if ( ! empty( $content['image'] ) ) : ?>
                                        <div class="featured-image">
                                            <a href="<?php echo esc_url( $content['url'] ); ?>">
                                                <img src="<?php echo esc_url( $content['image'] ); ?>" alt="<?php echo esc_attr( $content['title'] ); ?>">
                                            </a>
                                        </div><!-- .recent-image -->
                                    <?php endif; ?>

                                    <div class="entry-container">
                                        <div class="entry-meta">
                                            <span class="posted-on">
                                                <a href="<?php echo esc_url( $content['url'] ); ?>">
                                                    <time>
                                                        <?php 
                                                            echo mik_get_svg( array( 'icon' => 'calendar' ) );
                                                            echo date_i18n( get_option('date_format'), strtotime ( get_the_date( '', $content['id'] ) ) ); 
                                                        ?>
                                                    </time>
                                                </a>
                                            </span>
                                        </div>

                                        <header class="entry-header">
                                            <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                        </header>

                                        <div class="entry-content">
                                            <p><?php echo esc_html( $content['excerpt'] ); ?></p>
                                        </div><!-- .entry-content -->

                                    </div><!-- .entry-container -->
                                </div><!-- .post-wrapper -->
                            </article>
                        <?php endforeach; ?>
                </div><!-- .section-content -->
            </div><!-- .wrapper -->
        </div><!-- #featured-posts -->
    <?php 
    }
endif;
