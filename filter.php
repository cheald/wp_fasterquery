<?php
/*
Plugin Name: wp_fasterquery
Description: Fix Wordpress' default naive post query to avoid trashing your DB with huge temp tables
Version: 1.0
Author: Chris Heald
*/

$saved_posts_count = 0;
function rewrite_posts_queries_to_use_ids( $input ) {
  global $wpdb;
  $newquery = str_replace("{$wpdb->posts}.* FROM {$wpdb->posts}", "{$wpdb->posts}.ID FROM {$wpdb->posts}", $input);
  return $newquery;
}

function find_posts_from_ids($posts) {
  global $wpdb, $saved_posts_count;
  $ids = array();
  foreach($posts as $post) { $ids[] = $post->ID; }  
  $saved_posts_count = $wpdb->get_var( 'SELECT FOUND_ROWS()' );
  return $wpdb->get_results("SELECT * FROM {$wpdb->posts} WHERE ID IN (" . implode(",", $ids) . ");");
}

function use_save_found_post_count() {
  global $saved_posts_count;
  return $saved_posts_count;
}

add_filter( 'posts_request', 'rewrite_posts_queries_to_use_ids' );
add_filter( 'posts_results', 'find_posts_from_ids' );
add_filter( 'found_posts',   'use_save_found_post_count' );