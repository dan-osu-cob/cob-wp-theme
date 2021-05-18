<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage OSUBusinessSchool
 */

$context = Timber::get_context();

$context['posts'] = new Timber\PostQuery();

$templates = array( 'archive.twig', 'index.twig' );

$archive_info           = osubs_get_archive_info();
$context['title']       = $archive_info['title'];
$context['description'] = $archive_info['description'];

if ( is_category() ) {
	$context['page'] = 'category';
	$context['cat']  = get_query_var( 'cat' );
} elseif ( is_tag() ) {
	$context['page'] = 'tag';
} elseif ( is_post_type_archive() ) {
	$context['page'] = 'archive';
}

Timber::render( $templates, $context );
