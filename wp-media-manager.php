<?php
/**
 * Plugin Name: Delete Associated Media (with ACF and WooCommerce Support)
 * Description: Removes associated media from the media library when a post or page is deleted, including media in ACF Gallery fields and WooCommerce product images.
 * Version: 1.2
 * Author: Cody Hardman
 */

function delete_associated_media( $post_id ) {

    // --- Standard WordPress Attachment Handling ---
    $attachments = get_posts( array(
        'post_type'      => 'attachment',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'post_parent'    => $post_id
    ) );

    foreach ( $attachments as $attachment ) {
        if ( false === wp_delete_attachment( $attachment->ID ) ) {
            error_log( 'Failed to delete attachment ID: ' . $attachment->ID ); 
        }
    }

    // --- ACF Gallery Handling ---
    if( function_exists('get_field_objects') ) { // Check if ACF is active
        $fields = get_field_objects( $post_id ); 
        if( $fields ) {
            foreach( $fields as $field_name => $field ) {
                if( $field['type'] == 'gallery' ) {
                    $gallery_images = $field['value']; 
                    if( $gallery_images ) {
                        foreach( $gallery_images as $image_id ) {
                            if ( false === wp_delete_attachment( $image_id ) ) {
                                error_log( 'Failed to delete attachment ID (from ACF Gallery): ' . $image_id ); 
                            }
                        }
                    }
                }
            }
        }
    }

    // --- WooCommerce Product Image Handling ---
    if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) { 
        $product = wc_get_product( $post_id );
        if ( $product ) { 

            // Delete the featured image
            $featured_image_id = $product->get_image_id();
            if ( $featured_image_id ) {
                wp_delete_attachment( $featured_image_id );
            }

            // Delete gallery images 
            $gallery_image_ids = $product->get_gallery_image_ids();
            if ( $gallery_image_ids ) {
                foreach ( $gallery_image_ids as $image_id ) {
                    wp_delete_attachment( $image_id );
                }
            }

            // --- Handle Variable Products ---
            if ( $product->is_type( 'variable' ) ) {
                $available_variations = $product->get_available_variations();
                foreach ( $available_variations as $variation ) {
                    $variation_id = $variation['variation_id']; 

                    $variation_product = wc_get_product( $variation_id );
                    $variation_image_id = $variation_product->get_image_id();
                    if ( $variation_image_id ) {
                        wp_delete_attachment( $variation_image_id );
                    }
                }
            }
        }
    }
}

add_action( 'before_delete_post', 'delete_associated_media' ); 