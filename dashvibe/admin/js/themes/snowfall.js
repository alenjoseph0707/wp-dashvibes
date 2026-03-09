/**
 * DashVibe — Snowfall Theme
 * Gentle falling snowflakes with drift and wobble
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        DashVibeParticles.init({
            count: 80,

            factory: function (w, h, resetY) {
                return {
                    x: Math.random() * w,
                    y: resetY ? Math.random() * -50 : Math.random() * h,
                    size: Math.random() * 4 + 1,
                    speedY: Math.random() * 1.2 + 0.3,
                    drift: Math.random() * 0.8 - 0.4,
                    wobbleSpeed: Math.random() * 0.02 + 0.01,
                    wobbleAngle: Math.random() * Math.PI * 2,
                    opacity: Math.random() * 0.6 + 0.3,
                    rotation: Math.random() * Math.PI * 2,
                    rotationSpeed: Math.random() * 0.02 - 0.01,
                };
            },

            update: function (p, w, h) {
                p.y += p.speedY;
                p.wobbleAngle += p.wobbleSpeed;
                p.x += Math.sin(p.wobbleAngle) * 0.8 + p.drift;
                p.rotation += p.rotationSpeed;

                // Slow down near bottom (accumulation feel)
                if (p.y > h * 0.85) {
                    p.speedY *= 0.98;
                }
            },

            draw: function (ctx, p) {
                ctx.save();
                ctx.translate(p.x, p.y);
                ctx.rotate(p.rotation);
                ctx.globalAlpha = p.opacity;

                // Draw snowflake shape for larger particles
                if (p.size > 2.5) {
                    ctx.strokeStyle = '#fff';
                    ctx.lineWidth = 0.8;
                    for (var i = 0; i < 6; i++) {
                        ctx.beginPath();
                        ctx.moveTo(0, 0);
                        ctx.lineTo(0, p.size * 2);
                        ctx.stroke();
                        ctx.rotate(Math.PI / 3);
                    }
                } else {
                    // Simple circle for small flakes
                    ctx.beginPath();
                    ctx.arc(0, 0, p.size, 0, Math.PI * 2);
                    ctx.fillStyle = '#fff';
                    ctx.fill();
                }

                ctx.restore();
            }
        });

    });
})();
