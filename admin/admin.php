<?php

/**
 * Image Sizes Panel Admin Class
 * 
 * @package Image Sizes Panel
 * @since 0.1
 */

// Actions
add_action( 'admin_head', array( 'Image_Sizes_Panel_Admin', 'admin_enqueue_scripts' ) );
add_action( 'add_meta_boxes', array( 'Image_Sizes_Panel_Admin', 'add_image_sizes_meta_box' ) );

class Image_Sizes_Panel_Admin {

	/**
	 * Admin Styles
	 */
	public static function admin_enqueue_scripts() {

		$screen = get_current_screen();

		if ( 'post' == $screen->base && 'attachment' == $screen->id ) {

			?>

			<style>

			#image_sizes_panel table {
				width: 100%;
			}

			#image_sizes_panel table th,
			#image_sizes_panel table td {
				font-weight: normal;
				text-align: left;
				vertical-align: top;
			}

			#image_sizes_panel table td {
				text-align: right;
			}

			#image_sizes_panel table .undefined th {
				text-decoration: line-through;
			}

			</style>

			<?php

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

				echo '<tr class="' . $class . '"><th>' . sprintf( $format, $size ) . '</th><td>' . $width . ' &times ' . $height . '</td></tr>';

			}
			echo '</table>';

		} else {

			echo '<p>No image sizes</p>';

		}

	}

}
