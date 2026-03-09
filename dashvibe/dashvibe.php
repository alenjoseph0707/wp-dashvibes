<?php
/**
 * Plugin Name: DashVibe - Seasonal Dashboard Themes
 * Plugin URI: https://github.com/yourusername/dashvibe
 * Description: Transform your WordPress admin dashboard with fun seasonal themes — Snowfall, Pizza Party, Galaxy & more!
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://github.com/yourusername
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dashvibe
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin constants
define( 'DASHVIBE_VERSION', '1.0.0' );
define( 'DASHVIBE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DASHVIBE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DASHVIBE_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main DashVibe Plugin Class
 */
class DashVibe {

    /**
     * Available themes registry
     */
    private $themes = array();

    /**
     * Single instance
     */
    private static $instance = null;

    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->register_themes();
        $this->load_includes();
        $this->init_hooks();
    }

    /**
     * Register all available themes
     */
    private function register_themes() {
        $this->themes = array(
            'snowfall' => array(
                'name'        => __( 'Snowfall', 'dashvibe' ),
                'description' => __( 'Falling snow, icy blue palette & frosted glass effects.', 'dashvibe' ),
                'icon'        => '❄️',
                'season'      => 'winter',
                'variants'    => array(
                    'dark' => array(
                        'label'     => __( 'Deep Frost', 'dashvibe' ),
                        'primary'   => '#0a0a1a',
                        'secondary' => '#0e1628',
                        'accent'    => '#0f3460',
                        'highlight' => '#4facfe',
                        'text'      => '#c8d6e5',
                    ),
                    'medium' => array(
                        'label'     => __( 'Winter Night', 'dashvibe' ),
                        'primary'   => '#1a1a2e',
                        'secondary' => '#16213e',
                        'accent'    => '#0f3460',
                        'highlight' => '#e2e8f0',
                        'text'      => '#e2e8f0',
                    ),
                    'light' => array(
                        'label'     => __( 'Ice Blue', 'dashvibe' ),
                        'primary'   => '#1e3a5f',
                        'secondary' => '#2a5080',
                        'accent'    => '#3a7bd5',
                        'highlight' => '#e8f4fd',
                        'text'      => '#f0f6ff',
                    ),
                    'bright' => array(
                        'label'     => __( 'Snow Glow', 'dashvibe' ),
                        'primary'   => '#2c5282',
                        'secondary' => '#3182ce',
                        'accent'    => '#63b3ed',
                        'highlight' => '#ffffff',
                        'text'      => '#f7fafc',
                    ),
                ),
            ),
            'pizza-party' => array(
                'name'        => __( 'Pizza Party', 'dashvibe' ),
                'description' => __( 'Floating pizza slices, warm colors & cheesy vibes!', 'dashvibe' ),
                'icon'        => '🍕',
                'season'      => 'all',
                'variants'    => array(
                    'dark' => array(
                        'label'     => __( 'Charcoal Crust', 'dashvibe' ),
                        'primary'   => '#1a0a05',
                        'secondary' => '#2d1308',
                        'accent'    => '#8b4513',
                        'highlight' => '#d4830f',
                        'text'      => '#f5deb3',
                    ),
                    'medium' => array(
                        'label'     => __( 'Brick Oven', 'dashvibe' ),
                        'primary'   => '#4a1a0a',
                        'secondary' => '#6b2d10',
                        'accent'    => '#d4830f',
                        'highlight' => '#f5c518',
                        'text'      => '#fff5e6',
                    ),
                    'light' => array(
                        'label'     => __( 'Cheesy Warm', 'dashvibe' ),
                        'primary'   => '#7c3310',
                        'secondary' => '#a0522d',
                        'accent'    => '#e8960c',
                        'highlight' => '#ffe066',
                        'text'      => '#fffbf0',
                    ),
                    'bright' => array(
                        'label'     => __( 'Margherita', 'dashvibe' ),
                        'primary'   => '#b84c15',
                        'secondary' => '#d4690e',
                        'accent'    => '#f0a500',
                        'highlight' => '#fff7cc',
                        'text'      => '#ffffff',
                    ),
                ),
            ),
            'galaxy' => array(
                'name'        => __( 'Galaxy', 'dashvibe' ),
                'description' => __( 'Twinkling stars, nebula colors & cosmic vibes.', 'dashvibe' ),
                'icon'        => '🌌',
                'season'      => 'all',
                'variants'    => array(
                    'dark' => array(
                        'label'     => __( 'Deep Space', 'dashvibe' ),
                        'primary'   => '#050510',
                        'secondary' => '#0a0a20',
                        'accent'    => '#3b1f6e',
                        'highlight' => '#9b59b6',
                        'text'      => '#c8b8e0',
                    ),
                    'medium' => array(
                        'label'     => __( 'Nebula', 'dashvibe' ),
                        'primary'   => '#0b0c1e',
                        'secondary' => '#1a1040',
                        'accent'    => '#6c3fa0',
                        'highlight' => '#c084fc',
                        'text'      => '#e0d4f5',
                    ),
                    'light' => array(
                        'label'     => __( 'Stardust', 'dashvibe' ),
                        'primary'   => '#1a1545',
                        'secondary' => '#2d2070',
                        'accent'    => '#8b5cf6',
                        'highlight' => '#d8b4fe',
                        'text'      => '#f3ecff',
                    ),
                    'bright' => array(
                        'label'     => __( 'Aurora', 'dashvibe' ),
                        'primary'   => '#312e81',
                        'secondary' => '#4338ca',
                        'accent'    => '#a78bfa',
                        'highlight' => '#ede9fe',
                        'text'      => '#f9f7ff',
                    ),
                ),
            ),
        );
    }

    /**
     * Load required files
     */
    private function load_includes() {
        require_once DASHVIBE_PLUGIN_DIR . 'includes/class-settings.php';
        require_once DASHVIBE_PLUGIN_DIR . 'includes/class-theme-loader.php';
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Initialize sub-classes
        add_action( 'init', array( $this, 'init_components' ) );

        // Add settings link to plugins page
        add_filter( 'plugin_action_links_' . DASHVIBE_BASENAME, array( $this, 'add_settings_link' ) );

        // Admin bar menu
        add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_menu' ), 100 );

        // Admin bar styles (needs to load on front-end too for admin bar)
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_bar_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_admin_bar_styles' ) );
    }

    /**
     * Initialize components
     */
    public function init_components() {
        DashVibe_Settings::get_instance();
        DashVibe_Theme_Loader::get_instance();
    }

    /**
     * Add settings link on plugins page
     */
    public function add_settings_link( $links ) {
        $settings_link = '<a href="' . admin_url( 'admin.php?page=dashvibe' ) . '">' . __( 'Settings', 'dashvibe' ) . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

    /**
     * Add DashVibe quick-access to admin bar
     */
    public function add_admin_bar_menu( $wp_admin_bar ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $active_theme = get_option( 'dashvibe_active_theme', 'none' );
        $theme_icon = '🎨';

        if ( 'none' !== $active_theme && isset( $this->themes[ $active_theme ] ) ) {
            $theme_icon = $this->themes[ $active_theme ]['icon'];
        }

        // Parent menu
        $wp_admin_bar->add_node( array(
            'id'    => 'dashvibe',
            'title' => '<span class="dashvibe-ab-icon">' . $theme_icon . '</span> <span class="dashvibe-ab-label">DashVibe</span>',
            'href'  => admin_url( 'admin.php?page=dashvibe' ),
            'meta'  => array(
                'class' => 'dashvibe-admin-bar',
                'title' => __( 'DashVibe Dashboard Themes', 'dashvibe' ),
            ),
        ) );

        // Quick-switch theme items
        foreach ( $this->themes as $slug => $theme ) {
            $is_active = ( $active_theme === $slug );
            $wp_admin_bar->add_node( array(
                'parent' => 'dashvibe',
                'id'     => 'dashvibe-theme-' . $slug,
                'title'  => $theme['icon'] . ' ' . $theme['name'] . ( $is_active ? ' ✓' : '' ),
                'href'   => wp_nonce_url(
                    admin_url( 'admin.php?page=dashvibe&dashvibe_switch=' . $slug ),
                    'dashvibe_switch_theme'
                ),
            ) );
        }

        // Disable option
        $wp_admin_bar->add_node( array(
            'parent' => 'dashvibe',
            'id'     => 'dashvibe-theme-none',
            'title'  => '⭕ ' . __( 'Disable Theme', 'dashvibe' ) . ( 'none' === $active_theme ? ' ✓' : '' ),
            'href'   => wp_nonce_url(
                admin_url( 'admin.php?page=dashvibe&dashvibe_switch=none' ),
                'dashvibe_switch_theme'
            ),
        ) );

        // Dashboard link
        $wp_admin_bar->add_node( array(
            'parent' => 'dashvibe',
            'id'     => 'dashvibe-dashboard',
            'title'  => '⚙️ ' . __( 'Open Dashboard', 'dashvibe' ),
            'href'   => admin_url( 'admin.php?page=dashvibe' ),
        ) );
    }

    /**
     * Enqueue admin bar specific styles
     */
    public function enqueue_admin_bar_styles() {
        if ( is_admin_bar_showing() ) {
            wp_add_inline_style( 'admin-bar', '
                .dashvibe-ab-icon { font-style: normal; margin-right: 4px; }
                #wpadminbar .dashvibe-admin-bar > .ab-item { color: #fff !important; }
                #wpadminbar .dashvibe-admin-bar:hover > .ab-item { background: rgba(255,255,255,0.1) !important; }
            ' );
        }
    }

    /**
     * Get all registered themes
     */
    public function get_themes() {
        return $this->themes;
    }

    /**
     * Get a single theme by slug
     */
    public function get_theme( $slug ) {
        return isset( $this->themes[ $slug ] ) ? $this->themes[ $slug ] : false;
    }

    /**
     * Get the effective colors for a theme based on selected variant
     */
    public function get_theme_colors( $slug ) {
        $theme = $this->get_theme( $slug );
        if ( ! $theme ) {
            return false;
        }

        $variants_map = get_option( 'dashvibe_active_variant', array() );
        $variant_key  = isset( $variants_map[ $slug ] ) ? $variants_map[ $slug ] : 'medium'; // default to medium

        if ( isset( $theme['variants'][ $variant_key ] ) ) {
            $variant = $theme['variants'][ $variant_key ];
            return array(
                'primary'   => $variant['primary'],
                'secondary' => $variant['secondary'],
                'accent'    => $variant['accent'],
                'highlight' => $variant['highlight'],
                'text'      => $variant['text'],
            );
        }

        // Fallback to medium
        $variant = $theme['variants']['medium'];
        return array(
            'primary'   => $variant['primary'],
            'secondary' => $variant['secondary'],
            'accent'    => $variant['accent'],
            'highlight' => $variant['highlight'],
            'text'      => $variant['text'],
        );
    }
}

// Boot the plugin
function dashvibe() {
    return DashVibe::get_instance();
}
add_action( 'plugins_loaded', 'dashvibe' );

// Activation hook
register_activation_hook( __FILE__, function() {
    add_option( 'dashvibe_active_theme', 'none' );
    add_option( 'dashvibe_active_variant', array() ); // stores per-theme variant e.g. { 'snowfall': 'dark', 'galaxy': 'light' }
    add_option( 'dashvibe_auto_season', 'no' );
});

// Deactivation hook
register_deactivation_hook( __FILE__, function() {
    // Clean up is optional — keep settings for reactivation
});
