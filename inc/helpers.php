<?php
/**
 * Theme specific helpers.
 *
 * @package WordPress
 * @subpackage OSUBusinessSchool
 */

if ( ! function_exists( 'osubs_verify_nonce' ) ) :
    /**
    * Secure callbacks by verifying WP Nonce
    */
    function osubs_verify_nonce() {
        $nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : null;

        if ( ! $nonce || ! wp_verify_nonce( $nonce, $_REQUEST['action'] ) ) {
            wp_send_json_error( __( 'Not permitted.', 'osubs' ) );
        }
    }
endif;


if ( ! function_exists( 'osubs_get_archive_info' ) ) :
    /**
    * Pulls current archive information
    */
    function osubs_get_archive_info() {
		$archive_title    = '';
		$archive_description = '';

		if ( is_search() ) {
			global $wp_query;

			$archive_title = sprintf(
				'%1$s %2$s',
				__( 'Search results for:', 'osubs' ),
				'&ldquo;' . get_search_query() . '&rdquo;'
			);

			if ( $wp_query->found_posts ) {
				$archive_description = sprintf(
					/* translators: %s: Number of search results. */
					_n(
						'We found %s result for your search.',
						'We found %s results for your search.',
						$wp_query->found_posts,
						'osubs'
					),
					number_format_i18n( $wp_query->found_posts )
				);
			}
		} elseif ( ! is_home() ) {
			$archive_title    = get_the_archive_title();
			$archive_description = get_the_archive_description();
		}

		return array(
			'title' => $archive_title,
			'description' => $archive_description,
		);
    }

    function osubs_update_archive_title( $title ) {
        if ( is_day() ) {
            $title = sprintf( __( 'Archive: %s', 'osubs' ), get_the_date( 'D M Y' ) );
        } elseif ( is_month() ) {
            $title = sprintf( __( 'Archive: %s', 'osubs' ), get_the_date( 'M Y' ) );
        } elseif ( is_year() ) {
            $title = sprintf( __( 'Archive: %s', 'osubs' ), get_the_date( 'Y' ) );
        } elseif ( is_tag() ) {
            $title = sprintf( __( 'Tag: %s', 'osubs' ), single_tag_title( '', false ) );
        } elseif ( is_category() ) {
            $title = sprintf( __( 'Category: %s', 'osubs' ), single_cat_title( '', false ) );
        } elseif ( is_post_type_archive() ) {
            $title = post_type_archive_title( '', false );
        }

        return $title;
    }
    add_filter( 'get_the_archive_title', 'osubs_update_archive_title' );
endif;


if ( ! function_exists( 'osubs_get_term_id' ) ) :
    /**
    * Map terms with term ID
    */
    function osubs_get_term_id( $term ) {
        return $term->term_id;
    }
endif;


if ( ! function_exists( 'osubs_has_any' ) ) :
    /**
    * Check if array has any elements from the second
    */
    function osubs_has_any( $haystack, $needles ) {
        foreach ( $needles as $needle ) {
            if ( ! empty( $haystack[strtolower( $needle )] ) ) {
                return true;
            }
        }

        return false;
    }
endif;


if ( ! function_exists( 'osubs_format_price' ) ) :
    /**
    * Format price values
    */
    function osubs_format_price( $value, $decimals = 0, $dec_point = ',', $thousands_sep = ' ', $suffix = ',-' ) {
        if ( empty( $value ) ) {
            return null;
        }

        return number_format( $value, $decimals, $dec_point, $thousands_sep ) . $suffix;
    }
endif;


if ( ! function_exists( 'osubs_find_by_property' ) ) :
    /**
    * Find element in an array by property name
    */
    function osubs_find_by_property( $haystack, $key, $value ) {
        if ( empty( $haystack ) || empty( $key ) || empty( $value ) ) {
            return null;
        }

        $needle = null;
        foreach ( $haystack as $struct ) {
            $struct = (array) $struct;

            if ( $value == $struct[$key] ) {
                $needle = $struct;
                break;
            }
        }

        return $needle;
    }
endif;


if ( ! function_exists( 'osubs_combine' ) ) :
    /**
    * Highlight part of the input with chosen tag
    */
    function osubs_combine( $value ) {
        if ( empty( $value ) ) {
            return null;
        }

        $pattern = "/([-–—]{1})\s/i";
        $replacement = "$1&nbsp;";

        return preg_replace( $pattern, $replacement, $value );
    }
endif;


if ( ! function_exists( 'osubs_highlight' ) ) :
    /**
    * Highlight part of the input with chosen tag
    */
    function osubs_highlight( $value, $pattern = '**', $tag = 'strong' ) {
        if ( empty( $value ) ) {
            return null;
        }

		$pattern = preg_quote( $pattern );
        $pattern = "/{$pattern}([^*]*){$pattern}/i";
        $replacement = "<{$tag}>$1</{$tag}>";

        return preg_replace( $pattern, $replacement, $value );
    }
endif;


if ( ! function_exists( 'osubs_find' ) ) :
    /**
    * Find part of the string based on given pattern
    */
    function osubs_find( $value, $pattern ) {
        if ( empty( $value ) ) {
            return null;
        }

        preg_match( $pattern, $value, $matches );

        return $matches[1];
    }
endif;


if ( ! function_exists( 'osubs_truncate' ) ) :
    /**
    * Truncate long strings
    */
    function osubs_truncate( $value, $chars, $to_space = true, $replacement = '...' ) {
        $value = strip_tags( $value );

        if ( $chars > strlen( $value ) ) {
            return $value;
        }

        $value = substr( $value, 0, $chars );
        $space_pos = strrpos( $value, ' ' );

        if ( $to_space && $space_pos >= 0 ) {
            $value = substr( $value, 0, strrpos( $value, ' ' ) );
        }

        return( $value . $replacement );
    }
endif;


if ( ! function_exists( 'osubs_sanitize' ) ) :
    /**
    * Sanitize string
    */
    function osubs_sanitize( $value ) {
        return sanitize_title_with_dashes( remove_accents( $value ) );
    }
endif;


if ( ! function_exists( 'osubs_chunk' ) ) :
    /**
    * Chunk array into parts
    */
    function osubs_chunk( $array, $length ) {
        return array_chunk( $array, $length, true );
    }
endif;


if ( ! function_exists( 'osubs_format_number' ) ) :
    /**
    * Utility function to format the numbers,
    * appending "K" if one thousand or greater,
    * "M" if one million or greater,
    * and "B" if one billion or greater (unlikely).
    *
    * @param Number $precision - how many decimal points to display (1.25K)
    */
    function osubs_format_number( $number, $precision = 1 ) {
        if ( $number >= 1000 && $number < 1000000 ) {
            $formatted = number_format( $number / 1000, $precision ) . 'K';
        }
        elseif ( $number >= 1000000 && $number < 1000000000 ) {
            $formatted = number_format( $number / 1000000, $precision ) . 'M';
        }
        elseif ( $number >= 1000000000 ) {
            $formatted = number_format( $number / 1000000000, $precision ) . 'B';
        }
        else {
            $formatted = $number; // Number is less than 1000
        }

        $formatted = preg_replace( '/\.[0]+([KMB]?)$/i', '$1', $formatted );
        return $formatted;
    }
endif;


if ( ! function_exists( 'osubs_get_ip' ) ) :
    /**
    * Utility to retrieve IP address
    */
    function osubs_get_ip() {
        if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        }

        $ip = filter_var( $ip, FILTER_VALIDATE_IP );
        $ip = ( $ip === false ) ? '0.0.0.0' : $ip;
        return $ip;
    }
endif;


if ( ! function_exists( 'osubs_get_current_page' ) ) :
    /**
    * Get current page attribute
    */
    function osubs_get_current_page() {
        if ( get_query_var( 'paged' ) ) {
            return get_query_var( 'paged' );
        }
        elseif ( get_query_var( 'page' ) ) {
            return get_query_var( 'page' );
        }
        else {
            return 1;
        }
    }
endif;


if ( ! function_exists( 'osubs_set_product' ) ) :
    /**
    * Add proper context to woo products
    */
    function osubs_set_product( $post ) {
        global $product, $post;

        if ( is_woocommerce() ) {
            $product = wc_get_product( $post->ID );
        }
    }
endif;


if ( ! function_exists( 'osubs_get_product' ) ) :
    /**
    * Get Woo product object
    */
    function osubs_get_product( $post ) {
        if ( function_exists( 'wc_get_product' ) ) {
            return wc_get_product( $post->ID );
        }
    }
endif;
