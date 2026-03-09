<?php
/**
 * DashVibe Settings Page
 *
 * Handles the plugin dashboard where users pick themes and color variants.
 *
 * @package DashVibe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class DashVibe_Settings {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        add_action( 'admin_init', array( $this, 'handle_theme_switch' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_dashboard_assets' ) );

        // AJAX handler for variant switch
        add_action( 'wp_ajax_dashvibe_set_variant', array( $this, 'ajax_set_variant' ) );
    }

    /**
     * Add the DashVibe menu page
     */
    public function add_menu_page() {
        add_menu_page(
            __( 'DashVibe', 'dashvibe' ),
            __( 'DashVibe', 'dashvibe' ),
            'manage_options',
            'dashvibe',
            array( $this, 'render_dashboard' ),
            'dashicons-art',
            59
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting( 'dashvibe_settings', 'dashvibe_active_theme', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'none',
        ) );

        register_setting( 'dashvibe_settings', 'dashvibe_active_variant', array(
            'sanitize_callback' => array( $this, 'sanitize_variant_option' ),
            'default'           => array(),
        ) );
    }

    /**
     * Sanitize variant option
     */
    public function sanitize_variant_option( $value ) {
        if ( ! is_array( $value ) ) {
            return array();
        }
        $clean = array();
        foreach ( $value as $theme => $variant ) {
            $clean[ sanitize_text_field( $theme ) ] = sanitize_text_field( $variant );
        }
        return $clean;
    }

    /**
     * Handle quick-switch from admin bar
     */
    public function handle_theme_switch() {
        if ( ! isset( $_GET['dashvibe_switch'] ) || ! isset( $_GET['_wpnonce'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'dashvibe_switch_theme' ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $theme = sanitize_text_field( $_GET['dashvibe_switch'] );
        $valid_themes = array_keys( dashvibe()->get_themes() );
        $valid_themes[] = 'none';

        if ( in_array( $theme, $valid_themes, true ) ) {
            update_option( 'dashvibe_active_theme', $theme );
        }

        wp_safe_redirect( admin_url( 'admin.php?page=dashvibe&switched=1' ) );
        exit;
    }

    /**
     * AJAX: Set the color variant for a theme
     */
    public function ajax_set_variant() {
        check_ajax_referer( 'dashvibe_ajax', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Permission denied.' );
        }

        $theme_slug  = sanitize_text_field( $_POST['theme'] ?? '' );
        $variant_key = sanitize_text_field( $_POST['variant'] ?? '' );

        // Validate theme
        $theme = dashvibe()->get_theme( $theme_slug );
        if ( ! $theme ) {
            wp_send_json_error( 'Invalid theme.' );
        }

        // Validate variant
        $valid_variants = array( 'dark', 'medium', 'light', 'bright' );
        if ( ! in_array( $variant_key, $valid_variants, true ) ) {
            wp_send_json_error( 'Invalid variant.' );
        }

        // Save
        $all_variants = get_option( 'dashvibe_active_variant', array() );
        $all_variants[ $theme_slug ] = $variant_key;
        update_option( 'dashvibe_active_variant', $all_variants );

        // Return the variant colors so JS can live-preview
        $variant = $theme['variants'][ $variant_key ];

        wp_send_json_success( array(
            'message' => sprintf( '%s variant applied!', $variant['label'] ),
            'variant' => $variant_key,
            'label'   => $variant['label'],
            'colors'  => array(
                'primary'   => $variant['primary'],
                'secondary' => $variant['secondary'],
                'accent'    => $variant['accent'],
                'highlight' => $variant['highlight'],
                'text'      => $variant['text'],
            ),
        ) );
    }

    /**
     * Enqueue dashboard page assets
     */
    public function enqueue_dashboard_assets( $hook ) {
        if ( 'toplevel_page_dashvibe' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'dashvibe-dashboard',
            DASHVIBE_PLUGIN_URL . 'admin/css/dashboard.css',
            array(),
            DASHVIBE_VERSION
        );

        wp_enqueue_script(
            'dashvibe-dashboard',
            DASHVIBE_PLUGIN_URL . 'admin/js/dashboard.js',
            array( 'jquery' ),
            DASHVIBE_VERSION,
            true
        );

        // Get active variants
        $active_variants = get_option( 'dashvibe_active_variant', array() );

        wp_localize_script( 'dashvibe-dashboard', 'dashvibeData', array(
            'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
            'nonce'          => wp_create_nonce( 'dashvibe_ajax' ),
            'activeTheme'    => get_option( 'dashvibe_active_theme', 'none' ),
            'activeVariants' => $active_variants,
        ) );
    }

    /**
     * Render the dashboard page
     */
    public function render_dashboard() {
        $themes          = dashvibe()->get_themes();
        $active_theme    = get_option( 'dashvibe_active_theme', 'none' );
        $active_variants = get_option( 'dashvibe_active_variant', array() );
        $auto_season     = get_option( 'dashvibe_auto_season', 'no' );
        $switched        = isset( $_GET['switched'] ) ? true : false;

        include DASHVIBE_PLUGIN_DIR . 'templates/settings-page.php';
    }
}
