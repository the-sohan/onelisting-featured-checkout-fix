<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

namespace wpWax\OneListing;

if ( ! isset( $content_width ) ) {
	$content_width = 1140;
}

define( 'ONELISTING_THEME', 'free' );

class OneListing_Main {

	public $theme = 'onelisting';

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'load_textdomain' ) );
		add_action( 'after_setup_theme', array( $this, 'include_theme_files' ) );
		$this->includes();
		// Removed remove_demo_content_menu to enable demo importer in free version
		// add_action( 'admin_menu', array( $this, 'remove_demo_content_menu' ), 999 );
	}
	public function load_textdomain() {
		load_theme_textdomain( $this->theme, get_template_directory() . '/languages' );
	}
	// Commented out to enable demo importer in free version
	// public function remove_demo_content_menu() {
	// 	remove_submenu_page( 'tools.php', 'fw-backups-demo-content' );
	// }

	public function include_theme_files() {
		$load_admin_files   = is_admin();
		$load_updater_files = $load_admin_files || ( defined( 'DOING_CRON' ) && DOING_CRON );

		if ( $load_updater_files ) {
			require_once get_template_directory() . '/inc/updater-config.php';
		}

		if ( $load_admin_files ) {
			require_once get_template_directory() . '/inc/pro-notice.php';
			require_once get_template_directory() . '/inc/tgm-config.php';
		}

		require_once get_template_directory() . '/inc/traits/directorist-taxonomy-custom-fields.php';
		

		if ( class_exists( 'Directorist_Base' ) ) {
			require_once get_template_directory() . '/inc/directorist-support.php';
			require_once get_template_directory() . '/migration/class-migration-v8.php';
			
			if ( $load_admin_files && (float)Constants::$theme_version === 1.21 ) {
				require_once get_template_directory() . '/inc/onelisting-migration-wizard.php';
			}
		}

		do_action( 'onelisting_theme_init_after' );
	}

	public function includes() {
		$load_admin_files   = is_admin();
		$load_updater_files = $load_admin_files || ( defined( 'DOING_CRON' ) && DOING_CRON );

		if ( $load_admin_files ) {
			require_once get_template_directory() . '/lib/tgm/class-tgm-plugin-activation.php';
		}

		if ( $load_updater_files ) {
			require_once get_template_directory() . '/lib/directorist-theme-updater/theme-updater-admin.php';
		}

		require_once get_template_directory() . '/inc/constants.php';
		require_once get_template_directory() . '/inc/traits/init.php';
		require_once get_template_directory() . '/inc/helper.php';

		require_once get_template_directory() . '/inc/theme.php';
		require_once get_template_directory() . '/inc/general.php';
		require_once get_template_directory() . '/inc/scripts.php';
		require_once get_template_directory() . '/inc/layout-settings.php'; 
	}
}

new OneListing_Main;
