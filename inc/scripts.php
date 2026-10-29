<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

namespace wpWax\OneListing;

use Directorist\Directorist_Single_Listing;
use Elementor\Plugin;
class Scripts {

	public $version;
	protected static $instance = null;

	public function __construct() {
		$this->version = Constants::$theme_version;

		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 12 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 15 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_woocommerce_block_theme_styles' ), 20 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'gutenberg_scripts' ) );
		add_action( 'update_option_' . Constants::$theme_options, array( $this, 'flush_dynamic_css_cache' ), 10, 2 );
		add_action( 'csf_' . Constants::$theme_options . '_saved', array( $this, 'flush_dynamic_css_cache' ) );
	}

	public static function instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function register_scripts() {

		// Bootstrap
		wp_register_style( 'bootstrap', Helper::get_vendor_assets( 'bootstrap/css/bootstrap.min.css' ), array(), $this->version );
		// Bootstrap RTL
		wp_register_style( 'bootstrap-rtl', Helper::get_vendor_assets( 'bootstrap/css/bootstrap-rtl.css' ), array(), $this->version );
		wp_register_script( 'bootstrap', Helper::get_vendor_assets( 'bootstrap/js/bootstrap.bundle.min.js' ), array( 'jquery' ), $this->version, true );
		// Swiper Slider.
		wp_register_style( 'swiper', Helper::get_vendor_assets( 'swiper/swiper.css' ), array(), $this->version );
		wp_register_script( 'swiper', Helper::get_vendor_assets( 'swiper/swiper.js' ), array( 'jquery' ), $this->version, true );
		// Line Awesome
		wp_register_style( 'line-awesome', Helper::get_vendor_assets( 'line-awesome/css/line-awesome.min.css' ), array(), $this->version );
		// Countdown
		wp_register_script( 'counter-up', Helper::get_vendor_assets( 'counter/counter-up.min.js' ), array( 'jquery' ), $this->version, true );
		// Waypoints
		wp_register_script( 'waypoints', Helper::get_vendor_assets( 'waypoints/waypoints.min.js' ), array( 'jquery' ), $this->version, true );
		// Magnific Popup
		wp_register_script( 'magnific-popup', Helper::get_vendor_assets( 'magnific-popup/jquery.magnific-popup.min.js' ), array( 'jquery' ), $this->version, true );
		wp_register_style( 'magnific-popup', Helper::get_vendor_assets( 'magnific-popup/magnific-popup.css' ), array(), $this->version, 'all' );

		// Main Style
		wp_register_style( 'onelisting-directorist', Helper::get_css( 'directorist' ), array(), $this->version );
		// Directory Listing RTL
		wp_register_style( 'onelisting-directorist-rtl', Helper::get_css( 'directorist-rtl' ), array(), $this->version );
		//Helpgent
		wp_register_style( 'onelisting-helpgent', Helper::get_css( 'helpgent' ), array(), $this->version );
		//Elementor
		wp_register_style( 'onelisting-elementor', Helper::get_css( 'elementor' ), array(), $this->version );
		// Elementor RTL.
		wp_register_style( 'onelisting-elementor-rtl', Helper::get_css( 'elementor-rtl' ), array(), $this->version );
		wp_register_style( 'onelisting-style', Helper::get_css( 'style' ), array(), $this->version );
		// Main RTL Style.
		wp_register_style( 'onelisting-rtl-style', Helper::get_css( 'theme-style-rtl' ), array(), $this->version );
		// Main js
		wp_register_script( 'onelisting-main', Helper::get_js( 'main' ), array( 'jquery' ), $this->version, true );

		$data = array(
			'ajaxurl'         => admin_url( 'admin-ajax.php' ),
			'resmenuWidth'    => isset( Theme::$options['resmenu_width'] ) ? Theme::$options['resmenu_width'] : '991',
			'category_icons'  => class_exists( 'Directorist_Base' ) ? Directorist_Support::get_categories_icon() : array(),
			'category_colors' => class_exists( 'Directorist_Base' ) ? Directorist_Support::get_categories_color() : array(),
		);

		wp_localize_script( 'onelisting-main', 'onelisting_localize_data', $data );
	}

	public function enqueue_scripts() {
		$this->elementor_scripts(); // Elementor Scripts in preview mode
		$this->conditional_scripts();
		$this->dynamic_style();
		$should_enqueue_elementor_style = $this->should_enqueue_elementor_style();

		if ( is_rtl() ) {
			// Bootstrap TRL.
			wp_enqueue_style( 'bootstrap-rtl' );
			// Directory Listing RTL.
			wp_enqueue_style( 'onelisting-directorist-rtl' );
			// Elementor RTL.
			if ( $should_enqueue_elementor_style ) {
				wp_enqueue_style( 'onelisting-elementor-rtl' );
			}
			// Theme CSS RTL.
			wp_enqueue_style( 'onelisting-rtl-style' );
		} else {
			// Bootstrap.
			wp_enqueue_style( 'bootstrap' );
			// Directory Listing.
			wp_enqueue_style( 'onelisting-directorist' );
			//Helpgent
			if( class_exists( 'HelpGent' ) ){
                wp_enqueue_style( 'onelisting-helpgent' );
            }
			// Elementor.
			if ( $should_enqueue_elementor_style ) {
				wp_enqueue_style( 'onelisting-elementor' );
			}
			// Theme CSS.
			wp_enqueue_style( 'onelisting-style' );
		}

		/*
		=======================
			Enqueued JS scripts
		========================*/

		// Theme JS
		wp_enqueue_script( 'onelisting-main' );
		// Bootstrap JS
		wp_enqueue_script( 'bootstrap' );
	}

	private function should_enqueue_elementor_style() {
		$should_enqueue = false;
		$post_id        = 0;

		if ( ! did_action( 'elementor/loaded' ) ) {
			return $should_enqueue;
		}

		if ( Plugin::$instance->preview->is_preview_mode() || isset( $_GET['elementor-preview'] ) ) {
			$should_enqueue = true;
		} else {
			$post_id = get_queried_object_id();

			if ( $post_id && $this->is_elementor_document( $post_id ) ) {
				$should_enqueue = true;
			} elseif ( is_singular( 'at_biz_dir' ) ) {
				$should_enqueue = $this->current_listing_uses_elementor_single_page();
			}
		}

		return (bool) apply_filters( 'onelisting_should_enqueue_elementor_style', $should_enqueue, $post_id, $this );
	}

	private function is_elementor_document( $post_id ) {
		$document = Plugin::$instance->documents->get( $post_id );

		return ( $document && $document->is_built_with_elementor() );
	}

	private function current_listing_uses_elementor_single_page() {
		if ( ! class_exists( Directorist_Single_Listing::class ) ) {
			return false;
		}

		$listing = Directorist_Single_Listing::instance();

		if ( empty( $listing->type ) || ! $listing->single_page_enabled() ) {
			return false;
		}

		$page_id = (int) get_directorist_type_option( $listing->type, 'single_listing_page' );

		if ( ! $page_id ) {
			return false;
		}

		return $this->is_elementor_document( $page_id );
	}

	public function enqueue_woocommerce_block_theme_styles() {
		if ( ! class_exists( 'WooCommerce' ) || wp_is_block_theme() ) {
			return;
		}

		if ( ! is_cart() && ! is_checkout() && ! is_account_page() ) {
			return;
		}

		if ( ! defined( 'WC_PLUGIN_FILE' ) ) {
			return;
		}

		$version = defined( 'WC_VERSION' ) ? constant( 'WC_VERSION' ) : '1.0';

		wp_enqueue_style(
			'woocommerce-blocktheme',
			plugins_url( 'assets/css/woocommerce-blocktheme.css', WC_PLUGIN_FILE ),
			array(),
			$version,
			'all'
		);
		wp_style_add_data( 'woocommerce-blocktheme', 'rtl', 'replace' );

		wp_add_inline_style( 'woocommerce-blocktheme',
			':root {
				--wp--preset--font-size--small: 0.875rem;
				--wp--preset--font-size--medium: 1rem;
				--wp--preset--font-size--large: 1.25rem;
				--wp--style--block-gap: 1.5em;
			}'
		);
	}

	public function elementor_scripts() {

		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		if ( Plugin::$instance->preview->is_preview_mode() ) {
			$this->enqueue_swiper_assets();
			wp_enqueue_script( 'counter-up' );
			wp_enqueue_script( 'waypoints' );
		}

	}

	private function enqueue_swiper_assets() {
		wp_enqueue_style( 'swiper' );
		wp_enqueue_script( 'swiper' );
	}

	public function gutenberg_scripts() {
		wp_enqueue_style( 'onelisting-gutenberg', Helper::get_css( 'gutenberg' ), array(), $this->version );
		$css = $this->get_gutenberg_dynamic_css();
		wp_add_inline_style( 'onelisting-gutenberg', $css );
	}

	private function conditional_scripts() {

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		if ( is_home() || is_category() || is_tag() || is_author() || is_date() ) {
			wp_enqueue_script( 'jquery-masonry' );
		}

		if ( class_exists( 'Directorist_Base' ) ) {
			if ( is_singular( 'at_biz_dir' ) ) {
				$this->enqueue_swiper_assets();
				wp_enqueue_style( 'magnific-popup' );
				wp_enqueue_script( 'magnific-popup' );
			}

			if ( atbdp_is_page( 'all_locations' ) || atbdp_is_page( 'category' ) ) {
				wp_enqueue_script( 'jquery-masonry' );
			}
		}

	}

	private function template_style() {
		$css = '';

		if ( Theme::$bgtype == 'bgcolor' ) {
			$bgcolor      = Theme::$bgcolor;
			$banner_style = "background-color:{$bgcolor};";
		} else {
			$bgimg        = Theme::$bgimg;
			$banner_style = "background:url({$bgimg}) no-repeat scroll center center / cover;";
		}

		$css .= ".banner{{$banner_style}}";

		if ( Theme::$bgtype == 'bgimg' ) {
			$opacity = Theme::$options['bgopacity'] / 100;
			$css .= ".header-bgimg .banner:before{background-color: #05071D; opacity: {$opacity};}";
		}

		return $css;
	}

	private function add_prefix_to_css( $css, $prefix ) {
		$parts = explode( '}', $css );
		foreach ( $parts as &$part ) {
			if ( empty( $part ) ) {
				continue;
			}

			$firstPart = substr( $part, 0, strpos( $part, '{' ) + 1 );
			$lastPart  = substr( $part, strpos( $part, '{' ) + 2 );
			$subParts  = explode( ',', $firstPart );
			foreach ( $subParts as &$subPart ) {
				$subPart = str_replace( "\n", '', $subPart );
				$subPart = $prefix . ' ' . trim( $subPart );
			}

			$part = implode( ', ', $subParts ) . $lastPart;
		}

		$prefixedCSS = implode( "}\n", $parts );

		return $prefixedCSS;
	}

	private function optimized_css( $css ) {
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
		$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), ' ', $css );

		return $css;
	}

	private function get_dynamic_css_cache_name( $type ) {
		return 'onelisting_' . ONELISTING_THEME . '_' . $type . '_css';
	}

	private function get_dynamic_css_cache_hash() {
		return md5(
			wp_json_encode(
				array(
					'theme'        => ONELISTING_THEME,
					'version'      => $this->version,
					'options'      => Theme::$options,
					'helpgent'     => class_exists( 'HelpGent' ),
					'template_uri' => get_template_directory_uri(),
				)
			)
		);
	}

	private function get_cached_dynamic_css( $type, $callback ) {
		$hash       = $this->get_dynamic_css_cache_hash();
		$cache_name = $this->get_dynamic_css_cache_name( $type );
		$cached     = get_transient( $cache_name );

		if ( is_array( $cached ) && isset( $cached['hash'], $cached['css'] ) && $cached['hash'] === $hash ) {
			return $cached['css'];
		}

		$css = call_user_func( $callback );

		set_transient(
			$cache_name,
			array(
				'hash' => $hash,
				'css'  => $css,
			),
			DAY_IN_SECONDS
		);

		return $css;
	}

	public function flush_dynamic_css_cache() {
		delete_transient( $this->get_dynamic_css_cache_name( 'frontend_global' ) );
		delete_transient( $this->get_dynamic_css_cache_name( 'gutenberg' ) );
	}

	private function get_frontend_global_dynamic_css() {
		return $this->get_cached_dynamic_css(
			'frontend_global',
			function() {
				ob_start();
				Helper::requires( 'dynamic-styles/frontend.php' );
				$dynamic_css = ob_get_clean();

				return $this->optimized_css( $dynamic_css );
			}
		);
	}

	private function get_gutenberg_dynamic_css() {
		return $this->get_cached_dynamic_css(
			'gutenberg',
			function() {
				ob_start();
				Helper::requires( 'dynamic-styles/common.php' );
				$dynamic_css = ob_get_clean();
				$css         = $this->add_prefix_to_css( $dynamic_css, '.wp-block.block-editor-block-list__block' );

				ob_start();
				Helper::requires( 'dynamic-styles/frontend.php' );
				$css .= ob_get_clean();
				$css .= $this->get_theme_icon_css_variables();

				$css = str_replace( 'gtnbg_root', '', $css );
				$css = str_replace( 'gtnbg_suffix', 'wp-block.block-editor-block-list__block', $css );

				return $this->optimized_css( $css );
			}
		);
	}

	private function get_theme_icon_css_variables() {
		$icons = array(
			'angle-down-solid',
			'angle-up-solid',
			'angle-left-solid',
			'angle-right-solid',
			'facebook',
			'facebook-square',
			'twitter',
			'times-circle',
			'times-solid',
			'user',
			'instagram',
			'paper-plane',
			'paper-plane-solid',
			'pinterest',
			'plus-solid',
			'plus-circle-solid',
			'rss-solid',
			'search-solid',
			'vimeo',
			'user-circle',
			'linkedin-in',
			'link-solid',
			'vk',
			'tumblr',
			'github',
			'reddit',
			'bars-solid',
			'youtube',
			'reply-solid',
			'edit',
			'star-solid',
			'star-half-alt-solid',
			'star-empty',
			'check-solid',
			'minus-solid',
			'quote-left-solid',
			'quote-right-solid',
		);
		$css   = ':root{';

		foreach ( $icons as $icon ) {
			$css .= sprintf(
				'--theme-%1$s:url(%2$s);',
				$icon,
				esc_url_raw( Helper::get_svg_icon( $icon, true ) )
			);
		}

		$css .= '}';

		return $css;
	}

	private function dynamic_style() {
		$dynamic_css = '';
		$dynamic_css .= $this->template_style();
		$dynamic_css .= $this->get_frontend_global_dynamic_css();
		$dynamic_css = $this->optimized_css( $dynamic_css );

		$handler = is_rtl() ? 'onelisting-rtl-style' : 'onelisting-style';
		wp_add_inline_style( $handler, $dynamic_css );
	}

}

Scripts::instance();
