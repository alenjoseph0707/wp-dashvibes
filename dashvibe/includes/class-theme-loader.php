<?php
/**
 * DashVibe Theme Loader
 *
 * Loads the active theme's CSS and JS into the admin dashboard.
 *
 * @package DashVibe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class DashVibe_Theme_Loader {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'load_active_theme' ) );
        add_action( 'admin_footer', array( $this, 'render_canvas' ) );
    }

    /**
     * Load the currently active theme's assets
     */
    public function load_active_theme() {
        $active = get_option( 'dashvibe_active_theme', 'none' );

        if ( 'none' === $active ) {
            return;
        }

        $theme = dashvibe()->get_theme( $active );

        if ( ! $theme ) {
            return;
        }

        // Base admin override styles
        wp_enqueue_style(
            'dashvibe-base',
            DASHVIBE_PLUGIN_URL . 'admin/css/base-admin.css',
            array(),
            DASHVIBE_VERSION
        );

        // Theme-specific CSS
        $css_file = DASHVIBE_PLUGIN_URL . 'admin/css/themes/' . $active . '.css';
        wp_enqueue_style(
            'dashvibe-theme-' . $active,
            $css_file,
            array( 'dashvibe-base' ),
            DASHVIBE_VERSION
        );

        // Particle engine
        wp_enqueue_script(
            'dashvibe-particles',
            DASHVIBE_PLUGIN_URL . 'admin/js/particles.js',
            array(),
            DASHVIBE_VERSION,
            true
        );

        // Theme-specific JS
        $js_file = DASHVIBE_PLUGIN_URL . 'admin/js/themes/' . $active . '.js';
        wp_enqueue_script(
            'dashvibe-theme-' . $active,
            $js_file,
            array( 'dashvibe-particles' ),
            DASHVIBE_VERSION,
            true
        );

        // Get colors from the selected variant
        $colors = dashvibe()->get_theme_colors( $active );

        wp_localize_script( 'dashvibe-particles', 'dashvibeTheme', array(
            'slug'   => $active,
            'name'   => $theme['name'],
            'colors' => $colors,
        ) );

        // Inject CSS variables for colors
        $custom_css = ":root {
            --dashvibe-primary: {$colors['primary']};
            --dashvibe-secondary: {$colors['secondary']};
            --dashvibe-accent: {$colors['accent']};
            --dashvibe-highlight: {$colors['highlight']};
            --dashvibe-text: {$colors['text']};
        }";
        wp_add_inline_style( 'dashvibe-base', $custom_css );
    }

    /**
     * Add the canvas element for particle effects
     */
    public function render_canvas() {
        $active = get_option( 'dashvibe_active_theme', 'none' );

        if ( 'none' === $active ) {
            return;
        }

        echo '<canvas id="dashvibe-canvas" style="position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:9999;"></canvas>';
    }
}
