<?php

/**
 * Image Sizes Panel Admin Class
 * 
 * @package Image Sizes Panel
 * @since 0.1
 */

// Actions
add_action( 'admin_enqueue_scripts', array( 'Image_Sizes_Panel_Admin', 'admin_enqueue_scripts' ) );
add_action( 'add_meta_boxes', array( 'Image_Sizes_Panel_Admin', 'add_image_sizes_meta_box' ) );

class Image_Sizes_Panel_Admin {

	/**
	 * Admin Enqueue Scripts & Styles
	 */
	public static function admin_enqueue_scripts() {

		$screen = get_current_screen();

		// Only load scripts & styles on media edit pages
		if ( 'post' == $screen->base && 'attachment' == $screen->id ) {

			wp_enqueue_style( 'image-sizes-panel', IMAGE_SIZES_PANEL_URL . 'admin/css/admin.css' );
			wp_enqueue_script( 'image-sizes-panel', IMAGE_SIZES_PANEL_URL . 'admin/js/admin.js', array( 'jquery' ) );

		}

	}

	/**
	 * Add Image Sizes Meta Box
	 */
	public static function add_image_sizes_meta_box() {

		add_meta_box(
			'image_sizes_panel',
			__( 'Image Sizes', IMAGE_SIZES_PANEL_TEXTDOMAIN ),
			array( 'Image_Sizes_Panel_Admin', 'image_sizes_meta_box' ),
			'attachment',
			'side'
		);

	}

	/**
	 * Image Sizes Meta Box
	 *
	 * @param  object  $post  Post.
	 */
	public static function image_sizes_meta_box( $post ) {

		$defined_sizes = get_intermediate_image_sizes();
		$image_sizes = get_intermediate_image_sizes();
		$metadata = wp_get_attachment_metadata( $post->ID );
		$generated_sizes = array();

		// Merge defined image sizes with generated image sizes
		if ( isset( $metadata['sizes'] ) && count( $metadata['sizes'] ) > 0 ) {
			$generated_sizes = array_keys( $metadata['sizes'] );
			$image_sizes = array_unique( array_merge( $image_sizes, $generated_sizes ) );
		}

		sort( $image_sizes );

		if ( count( $image_sizes ) > 0 ) {

			echo '<table>';
			echo '<tr>';
			echo '<th class="info">Info</th>';
			echo '<th class="size">Size</th>';
			echo '<th class="dim">Dimensions</th>';
			echo '</tr>';

			foreach ( $image_sizes as $size ) {

				$src = wp_get_attachment_image_src( $post->ID, $size );

				if ( isset( $metadata['sizes'][ $size ] ) ) {
					$width = $metadata['sizes'][ $size ]['width'];
					$height = $metadata['sizes'][ $size ]['height'];
				} else {
					$width = $src[1];
					$height = $src[2];
				}

				if ( in_array( $size, $generated_sizes ) ) {
					$class = 'generated';
					$format = '<a href="' . $src[0] . '" target="images_sizes_panel">%s</a>';
				} else {
					$class = 'not-generated';
					$format = '%s';
				}

				$class = in_array( $size, $generated_sizes ) ? 'generated' : 'not-generated';
				if ( ! in_array( $size, $defined_sizes ) ) {
					$class = 'undefined';
				}

				$message = '';

				if ( ! in_array( $size, $generated_sizes ) ) {
					$message .= 'Image file not generated. Will use next largest image size.';
				}

				if ( ! in_array( $size, $defined_sizes ) ) {
					$message .= 'Image size no longer defined but file still exists. ';
				}

				if ( ! empty( $message ) ) {
					$message = sprintf( '<div class="info-content">%s</class>', trim( $message ) );
				}

				echo '<tr id="image-sizes-panel-' . sanitize_html_class( $size ) . '" class="' . $class . '">';
				echo '<td class="info"><a href="#image-sizes-panel-' . sanitize_html_class( $size ) . '" class="dashicons dashicons-info"></a></td>';
				echo '<td class="size"><span class="name">' . sprintf( $format, $size ) . '</span>' . $message . '</td>';
				echo '<td class="dim">' . $width . ' &times ' . $height . '</td>';
				echo '</tr>';

			}

			$full = wp_get_attachment_image_src( $post->ID, 'full' );
			echo '<tr id="image-sizes-panel-full" class="full">';
			echo '<td class="info"><a href="#image-sizes-panel-full" class="dashicons dashicons-info"></a></td>';
			echo '<td class="size"><span class="name"><a href="' . $full[0] . '" target="images_sizes_panel">full</a></span></td>';
			echo '<td class="dim">' . $full[1] . ' &times ' . $full[2] . '</td>';
			echo '</tr>';

			echo '</table>';

		} else {

			echo '<p>No image sizes</p>';

		}

	}

}
