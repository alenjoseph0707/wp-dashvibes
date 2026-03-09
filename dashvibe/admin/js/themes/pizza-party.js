/**
 * DashVibe — Pizza Party Theme
 * Floating pizza slices, cheese drips, and warm particle vibes
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        // Pizza emoji collection for variety
        var pizzaEmojis = ['🍕', '🧀', '🫒', '🍅', '🌶️'];

        DashVibeParticles.init({
            count: 35,

            factory: function (w, h, resetY) {
                var isPizza = Math.random() > 0.4;
                return {
                    x: Math.random() * w,
                    y: resetY ? Math.random() * -80 - 20 : Math.random() * h,
                    size: isPizza ? Math.random() * 20 + 16 : Math.random() * 4 + 2,
                    speedY: Math.random() * 0.8 + 0.2,
                    speedX: Math.random() * 0.4 - 0.2,
                    rotation: Math.random() * Math.PI * 2,
                    rotationSpeed: (Math.random() * 0.03 - 0.015),
                    opacity: Math.random() * 0.5 + 0.4,
                    wobbleAngle: Math.random() * Math.PI * 2,
                    wobbleSpeed: Math.random() * 0.015 + 0.005,
                    isPizza: isPizza,
                    emoji: pizzaEmojis[Math.floor(Math.random() * pizzaEmojis.length)],
                };
            },

            update: function (p, w, h) {
                p.y += p.speedY;
                p.wobbleAngle += p.wobbleSpeed;
                p.x += Math.sin(p.wobbleAngle) * 0.6 + p.speedX;
                p.rotation += p.rotationSpeed;
            },

            draw: function (ctx, p) {
                ctx.save();
                ctx.translate(p.x, p.y);
                ctx.rotate(p.rotation);
                ctx.globalAlpha = p.opacity;

                if (p.isPizza) {
                    // Draw emoji pizza
                    ctx.font = p.size + 'px serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(p.emoji, 0, 0);
                } else {
                    // Cheese/sauce particles
                    var colors = [
                        'rgba(245, 197, 24, ',   // cheese yellow
                        'rgba(212, 131, 15, ',   // warm orange
                        'rgba(220, 80, 20, ',    // tomato red
                    ];
                    var color = colors[Math.floor(Math.random() * colors.length)];

                    ctx.beginPath();
                    ctx.arc(0, 0, p.size, 0, Math.PI * 2);
                    ctx.fillStyle = color + p.opacity + ')';
                    ctx.fill();

                    // Subtle glow
                    ctx.shadowColor = 'rgba(245, 197, 24, 0.3)';
                    ctx.shadowBlur = 6;
                }

                ctx.restore();
            }
        });

    });
})();
