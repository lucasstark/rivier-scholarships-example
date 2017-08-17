<?php

namespace Rivier\Scholarships\Controllers;

use Rivier\Scholarships\Core;
use Rivier\Scholarships\Core\Settings;
use Rivier\Scholarships\Main;

class Admin {

	private static $instance;

	public static function register() {
		if ( self::$instance == null ) {
			self::$instance = new Admin();
		}
	}

	public function __construct() {

		add_filter( "manage_edit-" . Settings::$post_type_riv_scholarship . "_columns", array( $this, "post_columns" ) );
		add_action( 'manage_' . Settings::$post_type_riv_scholarship . '_posts_custom_column', array(
			$this,
			'post_columns_data'
		), 10, 2 );
		add_filter( 'manage_edit-' . Settings::$post_type_riv_scholarship . '_sortable_columns', array(
			$this,
			'register_sortable'
		) );

	}


	public function register_sortable( $columns ) {
		return $columns;
	}

	public function post_columns( $columns ) {

		$columns                  = array();
		$columns['cb']            = '<input type="checkbox" />';
		$columns['title']         = __( 'Title', 'rivier' );
		$columns['date']                        = __( 'Date' );

		return $columns;
	}

	public function post_columns_data( $column_name, $post_id ) {

		$item = Instance()->server->riv_scholarship->get( $post_id );

		$data = get_post_meta( $post_id, $column_name, true );
		switch ( $column_name ) {
			default:
				break;
		}
	}

}