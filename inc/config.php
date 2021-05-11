<?php
/**
 * Custom config actions
 *
 * @package WordPress
 * @subpackage OSUBusinessSchool
 */

if ( ! function_exists( 'osubs_remove_admin_bar_items' ) ) :
    /**
    * Remove unused admin pages
    */
    function osubs_remove_admin_bar_items() {
        global $wp_admin_bar;

        $wp_admin_bar->remove_menu('comments');
    }
endif;
add_filter( 'wp_before_admin_bar_render', 'osubs_remove_admin_bar_items' );


if ( ! function_exists( 'osubs_mime_types' ) ) :
    /**
    * Allow SVG Upload
    */
    function osubs_mime_types( $mimes ) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
endif;
add_filter( 'upload_mimes', 'osubs_mime_types', 99 );


// Change JPEG image quality
add_filter( 'jpeg_quality', function() { return 90; } );
