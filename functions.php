<?php

if ( ! class_exists( 'Timber' ) ) {
    add_action( 'admin_notices', function() {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
    } );
    return;
}

require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/config.php';
require_once get_template_directory() . '/inc/timber.php';
require_once get_template_directory() . '/inc/ajax.php';

if ( ! function_exists( 'osubs_setup' ) ) :
    /**
    * Sets up theme defaults and registers support for various WordPress features.
    *
    * Note that this function is hooked into the after_setup_theme hook, which
    * runs before the init hook. The init hook is too late for some features, such
    * as indicating support for post thumbnails.
    */
    function osubs_setup() {
        /*
         * Makes theme available for translation.
         * Translations can be filed in the /languages/ directory.
         */
        load_theme_textdomain( 'osubs', get_template_directory() . '/languages' );

        // Theme Support
        add_theme_support( 'title-tag' );

		// Gutenberg
		add_theme_support( 'responsive-embeds' );

        // Add excerpt to pages
        add_post_type_support( 'page', 'excerpt' );

        // Image sizes
        add_theme_support( 'images', array(
            '1460x972' => array(
                'width'     => 1460,
                'height'    => 972,
                'crop'      => true,
            ),
            '700x462' => array(
                'width'     => 700,
                'height'    => 462,
                'crop'      => true,
            ),

            // Square
            '1200x1200' => array(
                'width'     => 1200,
                'height'    => 1200,
                'crop'      => true,
            ),
        ) );

        // Sidebars
        add_theme_support( 'sidebars', array(
            'sidebar' => array(
                'name'  => __('Sidebar', 'osubs'),
                'id'    => 'sidebar',
            ),
        ) );

        // Custom posts
        add_theme_support( 'custom-post', array(
		) );

        add_theme_support( 'menus', array(
            'primary'       => __( 'Primary', 'osubs' ),
            'mobile'        => __( 'Mobile', 'osubs' ),
            'categories'    => __( 'Categories', 'osubs' ),
            'bottom'        => __( 'Bottom', 'osubs' ),
            'footer'        => __( 'Footer', 'osubs' ),
        ) );

        // Timber
        $timber = new OSUBusinessSchoolTimber();

        add_filter( 'timber_context', array( $timber, 'extend_context' ) );
        add_filter( 'get_twig', array( $timber, 'extend_twig' ) );

        // Activate ACF Options page
        if ( function_exists( 'acf_add_options_page' ) ) {
            acf_add_options_page();
        }
    }
endif;
add_action( 'after_setup_theme', 'osubs_setup' );


if ( ! function_exists( 'osubs_load_features' ) ) :
    /**
    * Require custom features depending on theme support
    */
    function osubs_load_features() {
        $features = scandir( dirname( __FILE__ ) . '/features/' );

        foreach ( $features as $feature ) {
            if ( current_theme_supports( str_replace( '.php', '', $feature ) ) ) {
                require_once dirname( __FILE__ ) . '/features/' . $feature;
            }
        }
    }
endif;
add_action( 'after_setup_theme', 'osubs_load_features' );


if ( ! function_exists( 'osu_load_widgets' ) ) :
    /**
    * Require custom widgets
    */
    function osubs_load_widgets() {
        $widgets = scandir( dirname( __FILE__ ) . '/inc/widgets/' );

        foreach ( $widgets as $widget ) {
            if ( strpos( $widget, '.php' ) ) {
                require_once dirname( __FILE__ ) . '/inc/widgets/' . $widget;
            }
        }
    }
endif;
add_action( 'after_setup_theme', 'osubs_load_widgets' );
