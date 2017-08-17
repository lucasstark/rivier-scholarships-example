<?php

namespace  Rivier\Scholarships\Core;

use \Rivier\Scholarships\Core\Settings;


class Templates {


	public function __construct() {
		add_filter( 'template_include', array( $this, 'on_template_include' ) );
	}

	public function on_template_include( $template ) {
		$file = false;
		$find = array();

		if ( is_post_type_archive( Settings::$post_type_riv_scholarship ) ) {
				$file   = 'archive-riv_scholarship.php';
				$find[] = $file;
				$find[] = Rivier_Scholarships()->template_directory() . $file;
		} elseif ( is_tax( Settings::$tax_scholarship_type ) ) {
				$file   = 'archive-riv_scholarship.php';
				$find[] = $file;
				$find[] = Rivier_Scholarships()->template_directory() . $file;
		} elseif ( is_singular( Settings::$post_type_riv_scholarship  ) ) {
				$file   = 'single-riv_scholarship.php';
				$find[] = $file;
				$find[] = Rivier_Scholarships()->template_directory() . $file;
		}


		if ( $file ) {
			$template = locate_template( array_unique( $find ) );
			if ( ! $template ) {
				$template = Rivier_Scholarships()->dir() . '/templates/' . $file;
			}
		}

		return $template;
	}

	/**
	 * Get template part (for templates like the content-contact).
	 *
	 * @access public
	 *
	 * @param mixed $slug
	 * @param string $name (default: '')
	 *
	 * @return void
	 */
	public function get_template_part( $slug, $names = '', $args = array() ) {
		$template = '';

		if ( ! is_array( $names ) ) {
			$names = array( $names );
		}

		foreach ( $names as $name ) {

			// Look in yourtheme/slug-name.php and yourtheme/woocommerce/slug-name.php
			if ( $name ) {
				$template = locate_template( array(
					"{$slug}-{$name}.php",
					\Rivier\Scholarships\Instance()->template_directory() . "{$slug}-{$name}.php"
				) );
			}

			// Get default slug-name.php
			if ( ! $template && $name && file_exists(Rivier_Scholarships()->dir() . "/templates/{$slug}-{$name}.php" ) ) {
				$template = Rivier_Scholarships()->dir() . "/templates/{$slug}-{$name}.php";
			}

			if ( $template ) {
				break;
			}
		}

		// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/ecc/slug.php
		if ( ! $template ) {
			$template = locate_template( array( "{$slug}.php",Rivier_Scholarships()->template_directory() . "{$slug}.php" ) );
		}

		if ( ! $template && file_exists(Rivier_Scholarships()->dir() . "/templates/{$slug}.php" ) ) {
			$template = Rivier_Scholarships()->dir() . "/templates/{$slug}.php";
		}

		// Allow 3rd party plugin filter template file from their plugin
		$template = apply_filters( 'get_template_part', $template, $slug, $names );

		if ( $template ) {
			global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;
			if ( is_array( $wp_query->query_vars ) ) {
				extract( $wp_query->query_vars, EXTR_SKIP );
			}

			if ( is_array( $args ) ) {
				extract( $args );
			}

			require( $template );
		}
	}

	/**
	 *
	 * @access public
	 *
	 * @param string $template_name
	 * @param array $args (default: array())
	 * @param string $template_path (default: '')
	 * @param string $default_path (default: '')
	 *
	 * @return void
	 */
	public function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		if ( $args && is_array( $args ) ) {
			extract( $args );
		}

		$located = $this->locate_template( $template_name, $template_path, $default_path );

		if ( ! file_exists( $located ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );

			return;
		}

		// Allow 3rd party plugin filter template file from their plugin
		$located = apply_filters( 'rivier-scholarships-example_get_template', $located, $template_name, $args, $template_path, $default_path );

		do_action( 'rivier-scholarships-example_before_template_part', $template_name, $template_path, $located, $args );

		include( $located );

		do_action( 'rivier-scholarships-example_after_template_part', $template_name, $template_path, $located, $args );
	}

	/**
	 * Locate a template and return the path for inclusion.
	 *
	 * This is the load order:
	 *
	 *        yourtheme        /    $template_path    /    $template_name
	 *        yourtheme        /    $template_name
	 *        $default_path    /    $template_name
	 *
	 * @access public
	 *
	 * @param string $template_name
	 * @param string $template_path (default: '')
	 * @param string $default_path (default: '')
	 *
	 * @return string
	 */
	public function locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = Rivier_Scholarships()->template_directory();
		}

		if ( ! $default_path ) {
			$default_path = Rivier_Scholarships()->dir() . '/templates/';
		}

		// Look within passed path within the theme - this is priority
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name
			)
		);

		// Get default template
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		// Return what we found
		return apply_filters( 'rivier-scholarships-example_locate_template', $template, $template_name, $template_path );
	}

}

