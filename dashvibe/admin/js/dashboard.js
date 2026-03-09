/**
 * DashVibe Dashboard Script
 * Handles variant selection, AJAX save, live preview, and canvas mini-previews
 */
(function ($) {
    'use strict';

    $(document).ready(function () {

        // =============================================
        // 1. Variant Click → AJAX Save + Live Preview
        // =============================================
        $('.dashvibe-variant-btn').on('click', function () {
            var $btn     = $(this);
            var theme    = $btn.data('theme');
            var variant  = $btn.data('variant');
            var $status  = $('#dashvibe-vstatus-' + theme);
            var $parent  = $btn.closest('.dashvibe-variants');

            // Highlight selected, unhighlight others
            $parent.find('.dashvibe-variant-btn').removeClass('dashvibe-variant-selected');
            $btn.addClass('dashvibe-variant-selected');

            // Show saving indicator
            $status.text('Saving...').css('color', '#6b7280');

            // AJAX save
            $.ajax({
                url: dashvibeData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'dashvibe_set_variant',
                    nonce: dashvibeData.nonce,
                    theme: theme,
                    variant: variant
                },
                success: function (response) {
                    if (response.success) {
                        $status.text('✅ ' + response.data.message).css('color', '#059669');

                        // Live-update the card preview gradient
                        var c = response.data.colors;
                        var $preview = $('.dashvibe-preview-' + theme);
                        $preview.css('background', 'linear-gradient(180deg, ' + c.primary + ' 0%, ' + c.secondary + ' 50%, ' + c.accent + ' 100%)');

                        // If this theme is currently active, hint to reload
                        if (dashvibeData.activeTheme === theme) {
                            $status.append(' — <a href="javascript:location.reload()" style="color:#7c3aed;text-decoration:underline;">Reload to apply</a>');
                        }
                    } else {
                        $status.text('❌ ' + (response.data || 'Failed.')).css('color', '#dc2626');
                    }
                },
                error: function () {
                    $status.text('❌ Network error.').css('color', '#dc2626');
                },
                complete: function () {
                    // Clear status after 4 seconds
                    setTimeout(function () {
                        $status.fadeOut(300, function () {
                            $(this).text('').show();
                        });
                    }, 4000);
                }
            });
        });

        // =============================================
        // 2. Mini Canvas Previews on Theme Cards
        // =============================================
        $('.dashvibe-preview-canvas').each(function () {
            var canvas = this;
            var theme  = $(canvas).data('theme');
            var ctx    = canvas.getContext('2d');
            var rect   = canvas.parentElement.getBoundingClientRect();

            canvas.width  = rect.width;
            canvas.height = rect.height;

            var particles = [];
            var w = canvas.width;
            var h = canvas.height;

            switch (theme) {
                case 'snowfall':
                    initSnowPreview(ctx, particles, w, h);
                    break;
                case 'pizza-party':
                    initPizzaPreview(ctx, particles, w, h);
                    break;
                case 'galaxy':
                    initGalaxyPreview(ctx, particles, w, h);
                    break;
            }
        });

        // Card hover
        $('.dashvibe-theme-card').on('mouseenter', function () {
            $(this).find('.dashvibe-theme-icon').css('transform', 'scale(1.2)');
        }).on('mouseleave', function () {
            $(this).find('.dashvibe-theme-icon').css('transform', 'scale(1)');
        });
    });

    // =============================================
    // Preview Renderers
    // =============================================

    function initSnowPreview(ctx, particles, w, h) {
        for (var i = 0; i < 40; i++) {
            particles.push({
                x: Math.random() * w, y: Math.random() * h,
                size: Math.random() * 3 + 1, speed: Math.random() * 0.8 + 0.2,
                opacity: Math.random() * 0.6 + 0.3, wobble: Math.random() * Math.PI * 2,
            });
        }
        (function animate() {
            ctx.clearRect(0, 0, w, h);
            for (var i = 0; i < particles.length; i++) {
                var p = particles[i];
                p.y += p.speed; p.wobble += 0.01; p.x += Math.sin(p.wobble) * 0.5;
                if (p.y > h) { p.y = -5; p.x = Math.random() * w; }
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(255,255,255,' + p.opacity + ')';
                ctx.fill();
            }
            requestAnimationFrame(animate);
        })();
    }

    function initPizzaPreview(ctx, particles, w, h) {
        var emojis = ['🍕', '🧀', '🍅'];
        for (var i = 0; i < 15; i++) {
            particles.push({
                x: Math.random() * w, y: Math.random() * h,
                size: Math.random() * 14 + 10, speed: Math.random() * 0.5 + 0.15,
                rotation: Math.random() * Math.PI * 2, rotSpeed: Math.random() * 0.02 - 0.01,
                opacity: Math.random() * 0.5 + 0.3,
                emoji: emojis[Math.floor(Math.random() * emojis.length)],
            });
        }
        (function animate() {
            ctx.clearRect(0, 0, w, h);
            for (var i = 0; i < particles.length; i++) {
                var p = particles[i];
                p.y += p.speed; p.rotation += p.rotSpeed;
                if (p.y > h + 20) { p.y = -20; p.x = Math.random() * w; }
                ctx.save();
                ctx.translate(p.x, p.y); ctx.rotate(p.rotation);
                ctx.globalAlpha = p.opacity;
                ctx.font = p.size + 'px serif';
                ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                ctx.fillText(p.emoji, 0, 0);
                ctx.restore();
            }
            requestAnimationFrame(animate);
        })();
    }

    function initGalaxyPreview(ctx, particles, w, h) {
        var colors = ['#c084fc', '#a78bfa', '#e0d4f5', '#f0abfc', '#93c5fd'];
        for (var i = 0; i < 60; i++) {
            var bright = Math.random() > 0.6;
            particles.push({
                x: Math.random() * w, y: Math.random() * h,
                size: bright ? Math.random() * 2 + 0.8 : Math.random() * 1 + 0.3,
                twinkleAngle: Math.random() * Math.PI * 2,
                twinkleSpeed: Math.random() * 0.03 + 0.005,
                baseOpacity: bright ? Math.random() * 0.5 + 0.4 : Math.random() * 0.3 + 0.1,
                color: bright ? colors[Math.floor(Math.random() * colors.length)] : '#e0d4f5',
                bright: bright,
            });
        }
        (function animate() {
            ctx.clearRect(0, 0, w, h);
            for (var i = 0; i < particles.length; i++) {
                var p = particles[i];
                p.twinkleAngle += p.twinkleSpeed;
                var opacity = p.baseOpacity * (0.5 + 0.5 * Math.sin(p.twinkleAngle));
                ctx.save(); ctx.globalAlpha = opacity; ctx.fillStyle = p.color;
                ctx.beginPath(); ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2); ctx.fill();
                if (p.bright) {
                    ctx.globalAlpha = opacity * 0.3;
                    ctx.beginPath(); ctx.arc(p.x, p.y, p.size * 3, 0, Math.PI * 2);
                    var glow = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.size * 3);
                    glow.addColorStop(0, p.color); glow.addColorStop(1, 'transparent');
                    ctx.fillStyle = glow; ctx.fill();
                }
                ctx.restore();
            }
            requestAnimationFrame(animate);
        })();
    }

})(jQuery);
