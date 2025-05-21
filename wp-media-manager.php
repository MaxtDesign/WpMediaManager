<?php
/**
 * Plugin Name: Delete Associated Media (with ACF and WooCommerce Support)
 * Description: Removes associated media from the media library when a post or page is deleted, including media in ACF Gallery fields and WooCommerce product images.
 * Version: 1.3
 * Author: MaxtDesign <Cody Hardman>
 * License: GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Delete associated media files when a post is permanently deleted
 *
 * @param int $post_id The ID of the post being deleted
 * @return void
 */
function delete_associated_media($post_id) {
    // Verify user capabilities
    if (!current_user_can('delete_posts')) {
        return;
    }

    // Skip revision deletions
    if (wp_is_post_revision($post_id)) {
        return;
    }

    $deleted_items = array();
    $failed_items = array();

    // --- Standard WordPress Attachment Handling ---
    $paged = 1;
    $batch_size = 100; // Process attachments in batches

    do {
        $attachments = get_posts(array(
            'post_type' => 'attachment',
            'posts_per_page' => $batch_size,
            'paged' => $paged,
            'post_status' => 'any',
            'post_parent' => $post_id
        ));

        if (empty($attachments)) {
            break;
        }

        foreach ($attachments as $attachment) {
            $result = wp_delete_attachment($attachment->ID, true);
            if ($result) {
                $deleted_items[] = $attachment->ID;
            } else {
                $failed_items[] = $attachment->ID;
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log(sprintf(
                        'WP Media Manager: Failed to delete attachment ID: %d for post ID: %d',
                        $attachment->ID,
                        $post_id
                    ));
                }
            }
        }

        $paged++;
    } while (count($attachments) === $batch_size);

    // --- ACF Gallery Handling ---
    if (function_exists('get_field_objects') && function_exists('acf_get_field')) {
        $fields = get_field_objects($post_id);
        if ($fields) {
            foreach ($fields as $field_name => $field) {
                // Verify field exists and is gallery type
                $field_obj = acf_get_field($field['key']);
                if ($field_obj && $field_obj['type'] === 'gallery') {
                    $gallery_images = $field['value'];
                    if (is_array($gallery_images)) {
                        foreach ($gallery_images as $image_id) {
                            $result = wp_delete_attachment($image_id, true);
                            if ($result) {
                                $deleted_items[] = $image_id;
                            } else {
                                $failed_items[] = $image_id;
                                if (defined('WP_DEBUG') && WP_DEBUG) {
                                    error_log(sprintf(
                                        'WP Media Manager: Failed to delete ACF gallery image ID: %d for post ID: %d',
                                        $image_id,
                                        $post_id
                                    ));
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // --- WooCommerce Product Image Handling ---
    if (class_exists('WooCommerce')) {
        $product = wc_get_product($post_id);
        if ($product) {
            // Delete featured image
            $featured_image_id = $product->get_image_id();
            if ($featured_image_id) {
                $result = wp_delete_attachment($featured_image_id, true);
                if ($result) {
                    $deleted_items[] = $featured_image_id;
                } else {
                    $failed_items[] = $featured_image_id;
                }
            }

            // Delete gallery images
            $gallery_image_ids = $product->get_gallery_image_ids();
            if (!empty($gallery_image_ids)) {
                foreach ($gallery_image_ids as $image_id) {
                    $result = wp_delete_attachment($image_id, true);
                    if ($result) {
                        $deleted_items[] = $image_id;
                    } else {
                        $failed_items[] = $image_id;
                    }
                }
            }

            // Handle Variable Products
            if ($product->is_type('variable')) {
                $variation_ids = $product->get_children();
                foreach ($variation_ids as $variation_id) {
                    $variation = wc_get_product($variation_id);
                    if ($variation) {
                        $variation_image_id = $variation->get_image_id();
                        if ($variation_image_id) {
                            $result = wp_delete_attachment($variation_image_id, true);
                            if ($result) {
                                $deleted_items[] = $variation_image_id;
                            } else {
                                $failed_items[] = $variation_image_id;
                            }
                        }
                    }
                }
            }
        }
    }

    // Log summary if debug is enabled
    if (defined('WP_DEBUG') && WP_DEBUG && (!empty($deleted_items) || !empty($failed_items))) {
        error_log(sprintf(
            'WP Media Manager Summary - Post ID: %d - Successfully deleted: %d items, Failed: %d items',
            $post_id,
            count($deleted_items),
            count($failed_items)
        ));
    }
}

add_action('before_delete_post', 'delete_associated_media', 10, 1); 