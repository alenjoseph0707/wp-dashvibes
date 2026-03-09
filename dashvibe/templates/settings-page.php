<?php
/**
 * DashVibe Settings Page Template
 *
 * @package DashVibe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="wrap dashvibe-wrap">

    <!-- Header -->
    <div class="dashvibe-header">
        <div class="dashvibe-header-content">
            <h1>🎨 DashVibe</h1>
            <p class="dashvibe-tagline"><?php esc_html_e( 'Transform your WordPress dashboard with fun visual themes.', 'dashvibe' ); ?></p>
        </div>
        <?php if ( 'none' !== $active_theme && isset( $themes[ $active_theme ] ) ) : ?>
            <div class="dashvibe-active-badge">
                <span class="dashvibe-active-icon"><?php echo esc_html( $themes[ $active_theme ]['icon'] ); ?></span>
                <span><?php echo esc_html( $themes[ $active_theme ]['name'] ); ?> <?php esc_html_e( 'is active', 'dashvibe' ); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Success notice -->
    <?php if ( $switched ) : ?>
        <div class="notice notice-success is-dismissible dashvibe-notice">
            <p>
                <?php if ( 'none' === $active_theme ) : ?>
                    <?php esc_html_e( '🎨 Theme disabled. Your dashboard is back to default.', 'dashvibe' ); ?>
                <?php else : ?>
                    <?php
                    printf(
                        /* translators: %s: theme name with icon */
                        esc_html__( '%s Theme activated! Enjoy the vibes. ✨', 'dashvibe' ),
                        esc_html( $themes[ $active_theme ]['icon'] . ' ' . $themes[ $active_theme ]['name'] )
                    );
                    ?>
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>

    <!-- Theme Grid -->
    <div class="dashvibe-themes-grid">

        <?php foreach ( $themes as $slug => $theme ) :
            $is_active = ( $active_theme === $slug );
            $switch_url = wp_nonce_url(
                admin_url( 'admin.php?page=dashvibe&dashvibe_switch=' . $slug ),
                'dashvibe_switch_theme'
            );
            $deactivate_url = wp_nonce_url(
                admin_url( 'admin.php?page=dashvibe&dashvibe_switch=none' ),
                'dashvibe_switch_theme'
            );
        ?>

        <div class="dashvibe-theme-card <?php echo $is_active ? 'dashvibe-theme-active' : ''; ?>" data-theme="<?php echo esc_attr( $slug ); ?>">

            <!-- Preview Area -->
            <div class="dashvibe-theme-preview dashvibe-preview-<?php echo esc_attr( $slug ); ?>">
                <canvas class="dashvibe-preview-canvas" data-theme="<?php echo esc_attr( $slug ); ?>"></canvas>
                <div class="dashvibe-theme-overlay">
                    <span class="dashvibe-theme-icon"><?php echo esc_html( $theme['icon'] ); ?></span>
                </div>
                <?php if ( $is_active ) : ?>
                    <div class="dashvibe-active-ribbon"><?php esc_html_e( 'ACTIVE', 'dashvibe' ); ?></div>
                <?php endif; ?>
            </div>

            <!-- Info -->
            <div class="dashvibe-theme-info">
                <h3><?php echo esc_html( $theme['icon'] . ' ' . $theme['name'] ); ?></h3>
                <p><?php echo esc_html( $theme['description'] ); ?></p>

                <!-- Color Variants: Dark → Light -->
                <?php
                $current_variant = isset( $active_variants[ $slug ] ) ? $active_variants[ $slug ] : 'medium';
                ?>
                <div class="dashvibe-variants" data-theme="<?php echo esc_attr( $slug ); ?>">
                    <span class="dashvibe-variants-label"><?php esc_html_e( 'Color Style:', 'dashvibe' ); ?></span>
                    <div class="dashvibe-variant-options">
                        <?php foreach ( $theme['variants'] as $var_key => $variant ) :
                            $is_selected = ( $current_variant === $var_key );
                            $gradient = "linear-gradient(135deg, {$variant['primary']}, {$variant['secondary']}, {$variant['accent']})";
                        ?>
                            <button type="button"
                                class="dashvibe-variant-btn <?php echo $is_selected ? 'dashvibe-variant-selected' : ''; ?>"
                                data-theme="<?php echo esc_attr( $slug ); ?>"
                                data-variant="<?php echo esc_attr( $var_key ); ?>"
                                title="<?php echo esc_attr( $variant['label'] ); ?>"
                            >
                                <span class="dashvibe-variant-circle" style="background: <?php echo esc_attr( $gradient ); ?>;"></span>
                                <span class="dashvibe-variant-name"><?php echo esc_html( $variant['label'] ); ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <div class="dashvibe-variant-status" id="dashvibe-vstatus-<?php echo esc_attr( $slug ); ?>"></div>
                </div>

                <!-- Actions -->
                <div class="dashvibe-theme-actions">
                    <?php if ( $is_active ) : ?>
                        <a href="<?php echo esc_url( $deactivate_url ); ?>" class="button dashvibe-btn-deactivate">
                            <?php esc_html_e( '✕ Deactivate', 'dashvibe' ); ?>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url( $switch_url ); ?>" class="button button-primary dashvibe-btn-activate">
                            <?php esc_html_e( '✓ Activate Theme', 'dashvibe' ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <?php endforeach; ?>

    </div>

    <!-- Info Section -->
    <div class="dashvibe-info-section">
        <div class="dashvibe-info-box">
            <h3>💡 <?php esc_html_e( 'Quick Access', 'dashvibe' ); ?></h3>
            <p><?php esc_html_e( 'You can quickly switch themes from the admin bar at the top. Look for the 🎨 DashVibe menu!', 'dashvibe' ); ?></p>
        </div>
        <div class="dashvibe-info-box">
            <h3>⚡ <?php esc_html_e( 'Performance', 'dashvibe' ); ?></h3>
            <p><?php esc_html_e( 'All animations use lightweight HTML5 Canvas — no heavy libraries. Your dashboard stays fast.', 'dashvibe' ); ?></p>
        </div>
        <div class="dashvibe-info-box">
            <h3>🎯 <?php esc_html_e( 'Admin Only', 'dashvibe' ); ?></h3>
            <p><?php esc_html_e( 'Themes only affect the admin dashboard. Your front-end website is never touched.', 'dashvibe' ); ?></p>
        </div>
    </div>

    <!-- Footer -->
    <div class="dashvibe-footer">
        <p>DashVibe v<?php echo esc_html( DASHVIBE_VERSION ); ?> — <?php esc_html_e( 'Made with ❤️ for the WordPress community', 'dashvibe' ); ?></p>
    </div>

</div>
