<?php
/**
* AJAX Callbacks
*
* @package WordPress
* @subpackage OSUBusinessSchool
*/

if ( ! function_exists( 'osubs_contact' ) ) :
    /**
    * Contact form callback
    */
    function osubs_contact() {
        osubs_verify_nonce();

        $contact_form = new ContactForm( $_REQUEST, $_FILES );
        $contact_form->send();
    }
endif;
add_action( 'wp_ajax_contact', 'osubs_contact' );
add_action( 'wp_ajax_nopriv_contact', 'osubs_contact' );
