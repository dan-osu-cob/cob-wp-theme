<?php
/**
 * Newsletter custom widget
 *
 * @package WordPress
 * @subpackage OSUBusinessSchool
 */

if ( ! class_exists( 'OSUBusinessSchoolNewsletterWidget' ) ) :
	class OSUBusinessSchoolNewsletterWidget extends WP_Widget {
		/**
		 * Sets up the widgets name etc
		 */
		public function __construct() {
			$widget_ops = array(
				'description' => 'Custom OSU newsletter widget',
			);
			parent::__construct( 'osu_newsletter_widget', 'OSU: Newsletter', $widget_ops );
		}

		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			Timber::render( 'templates/widgets/newsletter.twig', array_merge( Timber::get_context(), array(
				'title' => get_field( 'title', 'widget_' . $args['widget_id'] ),
			) ) );

			echo $args['after_widget'];
		}

		/**
		 * Outputs the options form on admin
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {
			// outputs the options form on admin
		}

		/**
		 * Processing widget options on save
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			// processes widget options to be saved
		}
	}
endif;

add_action( 'widgets_init', function () {
	register_widget( 'OSUBusinessSchoolNewsletterWidget' );
} );
