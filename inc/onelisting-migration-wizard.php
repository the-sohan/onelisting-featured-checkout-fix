<?php

use wpWax\OneListing\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Setup wizard class
 *
 * Walkthrough to the basic setup upon installation
 */
class OnelistingSetupWizard
{
    /** @var string Current Step */
    public $step   = '';

    /** @var array Steps for the setup wizard */
    public $steps  = array();

    /**
     * Hook in tabs.
     */
    public function __construct() {

        add_action( 'admin_menu', array( $this, 'admin_menus' ) );
        add_action( 'admin_init', array( $this, 'setup_wizard' ), 999 );
        add_action( 'admin_notices', array( $this, 'render_run_admin_setup_wizard_notice' ) );
    }

    public function render_run_admin_setup_wizard_notice() {

        $setup_wizard = get_option( 'onelisting_migration_setup_widget' );

        if ( $setup_wizard ) {
            return;
        }
        ?>

        <div id="message" class="updated atbdp-message" style="background: linear-gradient(45deg,  #3221a1 0%,#9c37ce 100%); color: #fff;">
            <p><?php echo __( '<strong>Onelisting</strong> to <strong>Best Listing</strong> - Thanks a bunch to have your seats with OneListing for a long way. Letting you inform that from now on, we are going to stop updating OneListing and migrate the whole theme to <strong>Best Listing</strong> that won’t affect your site even a bit.', 'onelisting' ); ?></p>
            <p class="submit">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=onelisting-migration' ) ); ?>" class="button-primary" style="background: linear-gradient(45deg,  #d62b54 0%,#dcba0f 100%);" >
                    <?php esc_html_e( 'Run The Migration Process', 'onelisting' ); ?>
                </a>
            </p>
        </div>
    <?php
    }

    /**
     * Add admin menus/screens.
     */
    public function admin_menus()
    {
        add_submenu_page(null, '', '', 'manage_options', 'onelisting-migration', '');
    }

    /**
     * Show the setup wizard.
     */
    public function setup_wizard()
    {

        if ( empty( $_GET['page'] ) || 'onelisting-migration' !== $_GET['page'] ) {
            return;
        }

        $this->set_steps();

        $this->step = isset($_GET['step']) ? sanitize_key($_GET['step']) : current(array_keys($this->steps));

        $this->enqueue_scripts();

        if (!empty($_POST['save_step']) && isset($this->steps[$this->step]['handler'])) { // WPCS: CSRF ok.
            call_user_func_array($this->steps[$this->step]['handler'], array($this));
        }

        ob_start();
        $this->set_setup_wizard_template();
        exit;
    }

    public function enqueue_scripts()
    {
        wp_register_style('directorist-admin-style', DIRECTORIST_CSS . 'admin-main.css', ATBDP_VERSION, true);
        wp_register_script('directorist-admin-setup-wizard-script', DIRECTORIST_JS . 'admin-setup-wizard.js', array('jquery'), ATBDP_VERSION, true);

        wp_enqueue_style('directorist-admin-style');
        wp_enqueue_script('directorist-admin-setup-wizard-script');
    }

    /**
     * Set wizard steps
     *
     * @since 2.9.27
     *
     * @return void
     */
    protected function set_steps()
    {
        $this->steps = array(
            'introduction' => array(
                'name'    =>  __('Introduction', 'onelisting'),
                'view'    => array( $this, 'directorist_setup_introduction' ),
            ),
            'step-one' => array(
                'name'    =>  __('Step One', 'onelisting'),
                'view'    => array( $this, 'directorist_step_one' ),
                'handler' => array( $this, 'directorist_step_one_save' ),
            ),
            'step-two' => array(
                'name'    =>  __('Step Two', 'onelisting'),
                'view'    => array( $this, 'directorist_step_two' ),
                'handler' => array( $this, 'directorist_step_two_save' ),
            ),
            'step-three' => array(
                'name'    =>  __('Step Three', 'onelisting'),
                'view'    => array( $this, 'directorist_step_three' ),
                'handler' => array( $this, 'directorist_step_three_save' ),
            ),
            'step-four' => array(
                'name'    =>  __('Step Four', 'onelisting'),
                'view'    => array( $this, 'directorist_step_four' ),
            ),
        );
    }

    /**
     * Introduction step.
     */
    public function directorist_setup_introduction()
    {
    ?>
        <div class="atbdp-c-body">
            <div class="atbdp-c-logo">
                <img src="<?php echo Helper::get_img( 'migration1.png' ); ?>" alt="Directorist" style="width: auto;">
            </div>
            <h1 class="atbdp-c-intro-title"><?php esc_html_e('The Migration Process', 'onelisting'); ?></h1>
            <p>
                <?php _e('Thank you for choosing Directorist to power up your business directory website. Generally, the simpler migration process will require you to follow 4 steps and you will be done in less than several minutes.', 'onelisting'); ?>
            </p>
        </div>

        <div class="atbdp-c-footer">
            <p class="atbdp-setup-actions step">
                <a href="<?php echo esc_url(admin_url()); ?>" class="wbtn wbtn-white"><?php esc_html_e('Not right now', 'onelisting'); ?></a>
                <a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="wbtn wbtn-primary"><?php esc_html_e('Let\'s Go!', 'onelisting'); ?></a>
            </p>
        </div>
    <?php
    }

    public function directorist_step_one() { ?>
        <div class="atbdp-c-header">
            <h1><?php esc_html_e('Install Theme', 'onelisting'); ?></h1>
            <p><?php _e( 'At first in the migration process, install Best Listing theme.', 'onelisting' ); ?></p>
        </div>

        <form method="post">
            <div class="atbdp-c-body">
                <div class="w-form-group">
                    <label for="best_listing_theme"><strong><?php esc_html_e( 'Best Listing Theme', 'onelisting' ); ?></strong></label>
                    <div>
                        <div class="w-toggle-switch">
                            <input type="checkbox" name='best_listing_theme' class="w-switch" id='best_listing_theme' value=1 checked disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="atbdp-c-footer">
                <p class="atbdp-setup-actions step">
                    <?php wp_nonce_field('onelisting-migration'); ?>
                    <input type="submit" class="wbtn wbtn-primary" value="<?php esc_attr_e('Continue', 'onelisting'); ?>" name="save_step" />
                </p>
            </div>
        </form>
        <?php
    }

    public function directorist_step_one_save() {
        
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        //Install Theme
        $skin     = new WP_Ajax_Upgrader_Skin();
        $Theme = new Theme_Upgrader( $skin );
        $Theme->install( 'https://downloads.wordpress.org/theme/best-listing.zip' );

        wp_redirect(esc_url_raw($this->get_next_step_link()));
        exit;
    }

    public function directorist_step_two()
    {
    ?>
        <div class="atbdp-c-header">
            <h1><?php esc_html_e('Install Recommended Plugin', 'onelisting'); ?></h1>
            <p><?php _e( 'Then move on to the 2nd phase which starts with the installation of the recommended plugin.', 'onelisting' ); ?></p>
        </div>

        <form method="post">
            <div class="atbdp-c-body">
                <div class="w-form-group">
                    <label for="ac_best_listing_toolkit"><strong><?php esc_html_e( 'Best Listing Toolkit', 'onelisting' ); ?></strong></label>
                    <div>
                        <div class="w-toggle-switch">
                            <input type="checkbox" name='ac_best_listing_toolkit' class="w-switch" id='ac_best_listing_toolkit' value=1 checked disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="atbdp-c-footer">
                <p class="atbdp-setup-actions step">
                    <?php wp_nonce_field('onelisting-migration'); ?>
                    <input type="submit" class="wbtn wbtn-primary" value="<?php esc_attr_e('Continue', 'onelisting'); ?>" name="save_step" />
                </p>
            </div>
        </form>
    <?php
    }

    /**
     * Save store options.
     */
    public function directorist_step_two_save()
    {
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        // Install Plugin
        $skin     = new WP_Ajax_Upgrader_Skin();
        $Plugin = new Plugin_Upgrader( $skin );
        $Plugin->install( 'https://downloads.wordpress.org/plugin/contact-form-7.zip' );

        wp_redirect(esc_url_raw($this->get_next_step_link()));
        exit;
    }

    public function directorist_step_three()
    {
    ?>
        <div class="atbdp-c-header">
            <h1><?php esc_html_e('Activation Process', 'onelisting'); ?></h1>
            <p>
                <?php _e( 'In the third step, Activate Best Listing Theme and The Best Listing toolkit.', 'onelisting' ); ?>
            </p>
        </div>

        <form method="post">
            <div class="atbdp-c-body">
                <div class="w-form-group">
                    <label for="ac_best_listing_theme"><strong><?php esc_html_e( 'Activate - Best Listing Theme', 'onelisting' ); ?></strong></label>
                    <div>
                        <div class="w-toggle-switch">
                            <input type="checkbox" name='ac_best_listing_theme' class="w-switch" id='ac_best_listing_theme' value=1 checked>
                        </div>
                    </div>
                </div>
                <div class="w-form-group">
                    <label for="ac_best_listing_toolkit"><strong><?php esc_html_e( 'Activate - Best Listing Toolkit', 'onelisting' ); ?></strong></label>
                    <div>
                        <div class="w-toggle-switch">
                            <input type="checkbox" name='ac_best_listing_toolkit' class="w-switch" id='ac_best_listing_toolkit' value=1 checked>
                        </div>
                    </div>
                </div>
            </div>
            <div class="atbdp-c-footer">
                <p class="atbdp-setup-actions step">
                    <?php wp_nonce_field('onelisting-migration'); ?>
                    <input type="submit" class="wbtn wbtn-primary" value="<?php esc_attr_e('Continue', 'onelisting'); ?>" name="save_step" />
                </p>
            </div>
        </form>
    <?php
    }

    public function directorist_step_three_save()
    {

        $result = activate_plugin( 'best-listing-toolkit/best-listing-toolkit.php' );

        if ( ! is_wp_error( $result ) ) {
            activate_plugin( 'best-listing-toolkit/best-listing-toolkit.php' );
        }

        wp_redirect(esc_url_raw($this->get_next_step_link()));
        exit;
    }

    public function directorist_step_four()
    {
        ?>
        <div class="atbdp-c-body">
            <div class="wsteps-done">
                <span class="wicon-done dashicons dashicons-yes"></span>
                <h2>
                    <?php esc_html_e( 'Finally, get your favorite OneListing theme migrated to Best Listing without touching the beauty of the appearance of your OneListing theme. In BestListing, you can enjoy exactly the same as you did with OneListing.', 'onelisting' ); ?>
                </h2>
            </div>
        </div>
        <div class="atbdp-c-footer atbdp-c-footer-center">
            <a href="<?php echo esc_url(admin_url()); ?>" class="w-footer-link"><?php esc_html_e('Return to the WordPress Dashboard', 'onelisting'); ?></a>
        </div>
    <?php
    update_option( 'template', 'best-listing' );
    update_option( 'stylesheet', 'best-listing' );
    update_option( 'onelisting_migration_setup_widget', true );
    }

    public function get_steps()
    {
        return $this->steps;
    }

    public function get_next_step_link()
    {
        $keys = array_keys($this->steps);

        return add_query_arg('step', $keys[array_search($this->step, array_keys($this->steps)) + 1]);
    }

    /**
     * Wizard templates
     *
     * @since 2.9.27
     *
     * @return void
     */
    protected function set_setup_wizard_template()
    {
        $this->setup_wizard_header();
        $this->setup_wizard_steps();
        $this->setup_wizard_content();
        $this->setup_wizard_footer();
    }

    /**
     * Setup Wizard Header.
     */
    public function setup_wizard_header()
    {
        $hide = ! isset( $_GET['step'] ) ? 'directorist-setup-wizard-vh' : 'directorist-setup-wizard-vh-none';
    ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>

        <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title><?php esc_html_e('Directorist &rsaquo; Setup Wizard', 'onelisting'); ?></title>
            <?php wp_print_scripts('directorist-admin-setup-wizard-script'); ?>
            <?php do_action('admin_print_styles'); ?>
            <?php do_action('admin_head'); ?>
            <?php do_action('directorist_setup_wizard_styles'); ?>
        </head>

        <body class="atbdp-setup wp-core-ui<?php echo get_transient('directorist_setup_wizard_no_wc') ? esc_attr( ' directorist-setup-wizard-activated-wc' ) : '';  ?> <?php echo esc_attr( $hide ); ?>">
            <div class="directorist-setup-wizard-wrapper">
            <?php
            /* $logo_url = ( ! empty( $this->custom_logo ) ) ? $this->custom_logo : plugins_url( 'assets/images/directorist-logo.svg', directorist_FILE );*/
            ?>
            <!--<h1 id="atbdp-logo"><a href="https://wedevs.com/directorist/"><img src="<?php /*echo esc_url( $logo_url ); */ ?>" alt="directorist Logo" width="135" height="auto" /></a></h1>-->
        <?php
    }

    /**
     * Output the steps.
     */
    public function setup_wizard_steps()
    {
        $ouput_steps = $this->steps;
        array_shift($ouput_steps);
        $hide = ! isset( $_GET['step'] ) ? 'atbdp-none' : '';
        ?>

            <ul class="atbdp-setup-steps <?php echo esc_attr( $hide ); ?>">

            

            <div class="atbdp-c-logo">
                <img src="<?php echo Helper::get_img( 'migration.png' ); ?>" alt="Directorist">
            </div>

                <?php foreach ($ouput_steps as $step_key => $step) : ?>
                    <li class="<?php
                        if ($step_key === $this->step && 'step-four' != $step_key ) {
                            echo 'active';
                        } elseif ( array_search( $this->step, array_keys($this->steps ) ) > array_search( $step_key, array_keys( $this->steps ) ) ) {
                            echo 'done';
                        } elseif ( isset( $_GET['step'] ) && 'step-four' == $_GET['step'] ) {
                            echo 'done';
                        }
                        $number = 1;
                        if ( 'step-one' == $step_key ) {
                            $number = 1;
                        } else if ( 'step-two' == $step_key ) {
                            $number = 2;
                        } else if ( 'step-three' == $step_key ) {
                            $number = 3;
                        } else if ( 'step-four' == $step_key ) {
                            $number = 4;
                        }
                        ?>"><span class="atbdp-sw-circle"><span><?php echo esc_html( $number ); ?></span> <span class="dashicons dashicons-yes"></span></span><?php echo esc_html( $step['name'] ); ?> </li>
                <?php endforeach; ?>
            </ul>
        <?php
    }

    /**
     * Output the content for the current step.
     */
    public function setup_wizard_content()
    {
        if ( empty( $this->steps[ $this->step ]['view'] ) ) {
            wp_redirect(esc_url_raw(add_query_arg('step', 'introduction')));
            exit;
        }
        $introduction_class = ! isset( $_GET['step'] ) ? 'atbdp_introduction' : '';
        echo '<div class="atbdp-setup-content '. esc_attr( $introduction_class ) .'">';
        call_user_func($this->steps[$this->step]['view']);
        echo '</div> </div>';
    }

    /**
     * Setup Wizard Footer.
     */
    public function setup_wizard_footer()
    {
        ?>
            <?php if ( 'next_steps' === $this->step ) : ?>
                <a class="atbdp-return-to-dashboard" href="<?php echo esc_url(admin_url()); ?>">
                    <?php esc_html_e( 'Return to the WordPress Dashboard', 'onelisting' ); ?>
                </a>
            <?php endif; ?>
        </body>

        </html>
        <?php
    }
}
new OnelistingSetupWizard();