<?php
/**
 * Database update and migration functions.
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Migration_V8 {
	protected static $instance = null;

	private function __construct() {
		add_action( 'init', [$this, 'all_listing_layout_migrate'] );
		add_action( 'init', [$this, 'migrate_single_listing_header'] );
		add_filter( 'directorist_single_listing_header_migration_data', [$this, 'listing_header_migration_data'], 10, 2 );
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function listing_header_migration_data( $new_listing_data = [], $old_listing_data = [] ) {

		// Reorganize the data structure as required
		$new_structure = [
			$new_listing_data[1], // slider
			$new_listing_data[0], // actions
			$new_listing_data[2], // title
			$new_listing_data[3], // info
		];
    
		// Mark the migration as completed
		update_option( 'v8_single_listing_header_migration', true );
        
		return $new_structure;
	}

	public static function migrate_single_listing_header() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Check if migration is already completed
		if ( get_option( 'v8_single_listing_header_migration', false ) && get_option( 'directorist_builder_header_migrated', false ) ) {
			return;
		}

		$backup_data_json  = get_option( 'directorist_builder_backup_data' );
		$backup_data_array = json_decode( $backup_data_json, true );

		if ( empty( $backup_data_array ) ) {
			return;
		}

		// Process each directory type in the backup data
		foreach ( $backup_data_array as $directory_type => $data ) {

			$title_options               = $data['single_listing_header']['options']['general']['listing_title'] ?? [];
			$current_listing_header_data = get_term_meta( $directory_type, 'single_listing_header', true );

			// Reorder the data structure as required
			$reordered_listing_header_data = [
				$current_listing_header_data[1], // slider
				$current_listing_header_data[0], // actions
				$current_listing_header_data[2], // title
				$current_listing_header_data[3], // info
			];

			// Update the term meta with the new structure
			update_term_meta( $directory_type, 'single_listing_header', $reordered_listing_header_data );
		}

		// Mark the migration as completed
		update_option( 'v8_single_listing_header_migration', true );
	}

	public function all_listing_layout_migrate() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( empty( get_option( 'directorist_builder_header_migrated', false ) ) ) {
			return;
		}

		if ( get_option( 'v8_all_listing_layout_migration', false ) ) {
			return;
		}

		$directory_types = get_terms( [
			'taxonomy'   => ATBDP_DIRECTORY_TYPE,
			'hide_empty' => false,
		] );

		foreach ( $directory_types as $directory_type ) {
			update_term_meta( $directory_type->term_id, 'all_listing_layout', 'left_sidebar' );
		}

		update_option( 'v8_all_listing_layout_migration', true );

	}
}

Migration_V8::instance();