<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

namespace wpWax\OneListing;

class TGM_Config {

	public $prefix;
	public $path;

	public function __construct() {
		$this->prefix = Constants::$theme_prefix;
		$this->path   = Constants::$theme_plugins_dir;

		add_action( 'tgmpa_register', array( $this, 'register_required_plugins' ) );
	}

	public function register_required_plugins() {
		$plugins = array(
			array(
				'name'     => esc_html__( 'OneListing Toolkit', 'onelisting' ),
				'slug'     => 'onelisting-toolkit',
				'source'   => 'http://directorist.com/demo-content/onelisting-toolkit.zip',
				'version'  => '1.4',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Elementor Page Builder', 'onelisting' ),
				'slug'     => 'elementor',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Directorist – Business Directory Plugin', 'onelisting' ),
				'slug'     => 'directorist',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'FormGent', 'onelisting' ),
				'slug'     => 'formgent',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'WpWax Demo Importer', 'onelisting' ),
				'slug'     => 'wpwax-demo-importer',
				'source'   => 'http://directorist.com/demo-content/wpwax-demo-importer.zip',
				'version'  => '1.4',
				'required' => false,
			),
			
		);

		$config = array(
			'id'           => $this->prefix, // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => $this->path, // Default absolute path to bundled plugins.
			'menu'         => $this->prefix . '-install-plugins', // Menu slug.
			'is_automatic' => false, // Automatically activate plugins after installation or not.
			'dismissable'  => true,
		);

		tgmpa( $plugins, $config );
	}
}

new TGM_Config;