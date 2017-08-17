<?php
namespace Rivier\Scholarships\Controllers;

use Rivier\Scholarships\Core;
use Rivier\Scholarships\Core\Settings;
use Rivier\Scholarships\Main;

class Taxonomy {

	private static $instance;

	public static function register() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}
	}


	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ), 0 );
	}

	public function register_post_types() {


		register_post_type( Settings::$post_type_riv_scholarship, array(
			'labels'                => array(
				'menu_name'          => __( 'Scholarships', 'rivier' ),
				'name'               => __( 'Scholarships', 'rivier' ),
				'singular_name'      => __( 'Scholarship', 'rivier' ),
				'add_new'            => __( 'Add Scholarship', 'rivier' ),
				'add_new_item'       => __( 'Add New Scholarship', 'rivier' ),
				'edit'               => __( 'Edit', 'rivier' ),
				'edit_item'          => __( 'Edit Scholarship', 'rivier' ),
				'new_item'           => __( 'New Scholarship', 'rivier' ),
				'view'               => __( 'View Scholarship', 'rivier' ),
				'view_item'          => __( 'View Scholarship', 'rivier' ),
				'search_items'       => __( 'Search Scholarships', 'rivier' ),
				'not_found'          => __( 'No Scholarships found', 'rivier' ),
				'not_found_in_trash' => __( 'No Scholarships found in trash', 'rivier' ),
				'parent'             => __( 'Parent Scholarships', 'rivier' )
			),
			'description'           => __( 'This is where you can add new Scholarships to your site.', 'rivier' ),
			'public'                => true,
			'show_ui'               => true,
			'publicly_queryable'    => true,
			'exclude_from_search'   => false,
			'hierarchical'          => false,
			'rewrite'               => array( 'slug' => 'riv_scholarship', 'with_front' => false ),
			'query_var'             => true,
			'supports'              => array( 'title', 'thumbnail', 'editor', 'excerpt' ),
			'has_archive'           => apply_filters( 'rivier-scholarships-example_riv_scholarship_archive_slug', 'riv_scholarships' ),
			'show_in_nav_menus'     => false,
			'capability_type'       => Settings::$post_type_riv_scholarship,
			'map_meta_cap'          => true,
			'show_in_rest'          => true,
			'rest_base'             => 'riv_scholarships',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'capabilities'          => array(
				// meta caps (don't assign these to roles)
				'edit_post'              => 'edit_' . Settings::$post_type_riv_scholarship,
				'read_post'              => 'read_' . Settings::$post_type_riv_scholarship,
				'delete_post'            => 'delete_' . Settings::$post_type_riv_scholarship,
				// primitive/meta caps
				'create_posts'           => 'create_' . Settings::$post_type_riv_scholarship . 's',
				// primitive caps used outside of map_meta_cap()
				'edit_posts'             => 'edit_' . Settings::$post_type_riv_scholarship . 's',
				'edit_others_posts'      => 'edit_others_' . Settings::$post_type_riv_scholarship . 's',
				'publish_posts'          => 'publish_' . Settings::$post_type_riv_scholarship . 's',
				'read_private_posts'     => 'read',
				// primitive caps used inside of map_meta_cap()
				'read'                   => 'read',
				'delete_private_posts'   => 'delete_private_' . Settings::$post_type_riv_scholarship . 's',
				'delete_published_posts' => 'delete_published_' . Settings::$post_type_riv_scholarship . 's',
				'delete_others_posts'    => 'delete_' . Settings::$post_type_riv_scholarship . 's',
				'edit_private_posts'     => 'edit_private_' . Settings::$post_type_riv_scholarship . 's',
				'edit_published_posts'   => 'edit_published_' . Settings::$post_type_riv_scholarship . 's',
			),
		) );

		register_taxonomy( Settings::$tax_scholarship_type, array( Settings::$post_type_riv_scholarship ), array(
				'hierarchical'          => true,
				'update_count_callback' => '_update_post_term_count',
				'labels'                => array(
					'name'          => __( 'Scholarship Types', 'rivier' ),
					'singular_name' => __( 'Scholarship Type', 'rivier' ),
					'search_items'  => __( 'Search Scholarship Types', 'rivier' ),
					'all_items'     => __( 'All Scholarship Types', 'rivier' ),
					'edit_item'     => __( 'Edit Scholarship Type', 'rivier' ),
					'update_item'   => __( 'Update Scholarship Type', 'rivier' ),
					'add_new_item'  => __( 'Add New Scholarship Type', 'rivier' ),
					'new_item_name' => __( 'New Scholarship Type', 'rivier' )
				),
				'show_ui'               => true,
				'show_in_nav_menus'     => false,
				'show_admin_column'     => true,
				'query_var'             => true,
				'rewrite'               => array(
					'slug'       => apply_filters( 'rivier-scholarships-example_riv_scholarship_archive_slug', 'riv_scholarships' ). '/riv_scholarship_type',
					'with_front' => false
				),
				'has-archive'           => true,
				'capabilities'          => array(
					'manage_terms' => Settings::$cap_manage_riv_scholarships,
					'edit_terms'   => Settings::$cap_manage_riv_scholarships,
					'delete_terms' => Settings::$cap_manage_riv_scholarships,
					'assign_terms' => Settings::$cap_assign_terms,
				)
			)
		);

		/*
		$administrator = get_role( 'profile' );
		$administrator->remove_cap( 'publish_posts' );
		$administrator->remove_cap( 'edit_contact' );
		$administrator->remove_cap( 'edit_contacts' );
		$administrator->remove_cap( 'edit_published_contacts' );
		$administrator->remove_cap( 'publish_contacts' );

		$administrator->remove_cap( 'create_contacts' );
		$administrator->remove_cap( 'edit_posts' );
		$administrator->remove_cap( 'create_posts' );
		*/
	}

}
