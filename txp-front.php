<?php

/**
 * Function to create fronend of txp-slider
 *
 * @since    1.0.0
 */ 

// Disable direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} 


function txp_slider_render_front_slider( $attr ) {

	$posts = '';
	$output = '';
	$slides = '';

	$blog_url = $attr['siteURL']."wp-json/wp/v2/posts?per_page=".$attr['numberOfPosts'];

	$response = wp_remote_get( $blog_url );

	// Exit if error.
	if ( is_wp_error( $response ) ) {
		return;
	}
	
	// Get the body.
	$posts = json_decode( wp_remote_retrieve_body( $response ) );

	// Exit if nothing is returned.
	if ( empty( $posts ) ) {
		return;
	}

	if ( ! empty( $posts ) ) {

		foreach ( $posts as $post ) {
			//print_r($post);
			$slides .= '<div class="swiper-slide" style="background-image : url(' . esc_url($post->episode_featured_image) . ') ">';
			$slides .= '<div class="txp-slider-gradient-overlay" style="background-image: ' . esc_attr($attr['gradient']) . '; opacity: ' . esc_attr($attr['overlayBgOpecity'])/100 . ' " ></div>';

			$slides .= '<div class="txp-slider-content-wrap">';

			$slides .= '<h2 class="txp-post-title">' . esc_html($post->title->rendered) . '</h2>';
			$slides .= '<div class="txp-publish-date">' . esc_attr(date( 'F d, Y', strtotime( $post->modified ))) . '</div>';
			$slides .= '<a class="txp-post-link" href=' . esc_url($post->link) . ' rel="nofollow" target="_blank">' . esc_attr__("View Post", "txp-slider") . '</a>';

			$slides .= '</div>';

			$slides .= '</div>';
		}	

	}	

	$output .= '<div class="txp-slider-wrap">';
	$output .= '<div class="swiper txp-slider-swiper">';
	$output .= '<div class="swiper-wrapper">';

	$output .= $slides;
	
	$output .= '</div>';
	$output .= '</div>';
	$output .= '</div>';


	//print_r($attr);

	return $output ?? '<strong>Sorry. No posts matching your criteria!</strong>';
}