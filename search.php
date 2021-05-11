<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage OSUBusinessSchool
 */

$context = Timber::get_context();

$context['posts'] = new Timber\PostQuery();

$templates = array( 'search.twig', 'archive.twig', 'index.twig' );

$archive_info = osubs_get_archive_info();
$context['title'] = $archive_info['title'];
$context['description'] = $archive_info['description'];

$context['query'] = get_search_query();
$context['page'] = 'search';

Timber::render( $templates, $context );
