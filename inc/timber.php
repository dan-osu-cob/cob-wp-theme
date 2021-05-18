<?php
/**
 * Extend Timber
 *
 * @package WordPress
 * @subpackage OSUBusinessSchool
 */

if ( ! class_exists( 'OSUBusinessSchoolTimber' ) ) :
	class OSUBusinessSchoolTimber extends TimberSite {
		public function __construct() {
			parent::__construct();
		}

		public function extend_context( $context ) {
			global $woocommerce;

			$this->context = $context;
			$theme         = wp_get_theme();

			Timber::$dirname = 'templates';

			$this->context['site']          = $this;
			$this->context['author']        = $theme->get( 'Author' );
			$this->context['author_uri']    = $theme->get( 'AuthorURI' );
			$this->context['version']       = $theme->get( 'Version' );
			$this->context['current_url']   = Timber\URLHelper::get_current_url();
			$this->context['is_front_page'] = is_front_page();
			$this->context['is_singular']   = is_singular();
			$this->context['posts_page']    = get_option( 'page_for_posts' );

			$this->context['options']    = get_fields( 'options' );
			$this->context['theme_link'] = get_template_directory_uri() . '/static/dist';

			$this->context['navs'] = $this->osubs_get_navs();

			return $this->context;
		}

		public function extend_twig( $twig ) {
			$filters = [
				'has_any',
				'find_by_property',
				'format_price',
				'combine',
				'highlight',
				'find',
				'truncate',
				'sanitize',
				'chunk',
			];

			$functions = [
				'get_nav',
				'get_terms',
				'get_mailchimp_form_url',
				'get_svg_content',
			];

			foreach ( $filters as $filter ) {
				$twig->addFilter( new Twig_SimpleFilter( $filter, 'osubs_' . $filter ) );
			}

			foreach ( $functions as $function ) {
				$twig->addFunction( new Twig_SimpleFunction( $function, array( $this, 'osubs_' . $function ) ) );
			}

			$twig->addExtension( new Twig_Extension_StringLoader() );

			return $twig;
		}

		public function osubs_get_navs() {
			$existingNavs = [];
			$navs         = [ 'primary', 'mobile', 'categories', 'footer', 'bottom' ];

			foreach ( $navs as $nav ) {
				if ( has_nav_menu( $nav ) ) {
					$name                  = str_replace( '-', '_', $nav );
					$existingNavs[ $name ] = new Timber\Menu( $nav );
				}
			}

			return $existingNavs;
		}

		public function osubs_get_nav( $nav_id ) {
			return new Timber\Menu( $nav_id );
		}

		public function osubs_get_terms( $term, $limit = 0 ) {
			$terms = Timber::get_terms( array(
				'number'     => $limit,
				'taxonomy'   => $term,
				'hide_empty' => false,
			) );

			return ! empty( $terms ) ? $terms : null;
		}

		public function osubs_get_mailchimp_form_url() {
			$options = get_fields( 'options' );

			$query = http_build_query( array(
				'u'  => $options['newsletter_form']['user_id'],
				'id' => $options['newsletter_form']['form_id'],
			) );

			return $options['newsletter_form']['form_url'] . '?' . $query;
		}

		public function osubs_get_svg_content( $url ) {
			$svg_file = file_get_contents( $url );

			$find_string = '<svg';
			$position    = strpos( $svg_file, $find_string );

			$svg_content = substr( $svg_file, $position );

			return $svg_content;
		}
	}
endif;
