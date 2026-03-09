/**
 * DashVibe — Galaxy Theme
 * Twinkling stars, shooting stars, and cosmic nebula dust
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        var shootingStars = [];
        var shootingStarTimer = 0;

        DashVibeParticles.init({
            count: 120,

            factory: function (w, h, resetY) {
                var isBright = Math.random() > 0.7;
                return {
                    x: Math.random() * w,
                    y: Math.random() * h,
                    size: isBright ? Math.random() * 2.5 + 1 : Math.random() * 1.5 + 0.3,
                    baseOpacity: isBright ? Math.random() * 0.5 + 0.5 : Math.random() * 0.4 + 0.1,
                    opacity: 0,
                    twinkleSpeed: Math.random() * 0.03 + 0.005,
                    twinkleAngle: Math.random() * Math.PI * 2,
                    isBright: isBright,
                    color: isBright
                        ? ['#c084fc', '#a78bfa', '#e0d4f5', '#f0abfc', '#93c5fd'][Math.floor(Math.random() * 5)]
                        : '#e0d4f5',
                    // Stars don't fall — they stay in place
                    speedY: 0,
                    speedX: 0,
                    stationary: true,
                };
            },

            update: function (p, w, h) {
                // Twinkle effect
                p.twinkleAngle += p.twinkleSpeed;
                p.opacity = p.baseOpacity * (0.5 + 0.5 * Math.sin(p.twinkleAngle));

                // Shooting stars
                shootingStarTimer++;
                if (shootingStarTimer > 200 && Math.random() > 0.995) {
                    shootingStarTimer = 0;
                    shootingStars.push({
                        x: Math.random() * w * 0.6,
                        y: Math.random() * h * 0.4,
                        length: Math.random() * 80 + 40,
                        speedX: Math.random() * 8 + 5,
                        speedY: Math.random() * 4 + 2,
                        opacity: 1,
                        width: Math.random() * 1.5 + 0.5,
                    });
                }
            },

            draw: function (ctx, p) {
                // Draw twinkling star
                ctx.save();
                ctx.globalAlpha = p.opacity;

                if (p.isBright) {
                    // Draw star with cross glow
                    ctx.fillStyle = p.color;

                    // Core
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    ctx.fill();

                    // Cross rays
                    ctx.strokeStyle = p.color;
                    ctx.lineWidth = 0.5;
                    ctx.globalAlpha = p.opacity * 0.5;

                    ctx.beginPath();
                    ctx.moveTo(p.x - p.size * 3, p.y);
                    ctx.lineTo(p.x + p.size * 3, p.y);
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y - p.size * 3);
                    ctx.lineTo(p.x, p.y + p.size * 3);
                    ctx.stroke();

                    // Glow
                    ctx.globalAlpha = p.opacity * 0.15;
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.size * 4, 0, Math.PI * 2);
                    var glow = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.size * 4);
                    glow.addColorStop(0, p.color);
                    glow.addColorStop(1, 'transparent');
                    ctx.fillStyle = glow;
                    ctx.fill();

                } else {
                    // Simple dot
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    ctx.fillStyle = p.color;
                    ctx.fill();
                }

                ctx.restore();

                // Draw shooting stars (only from the first particle's draw to avoid duplication)
                if (p === DashVibeParticles.particles[0]) {
                    for (var i = shootingStars.length - 1; i >= 0; i--) {
                        var s = shootingStars[i];

                        ctx.save();
                        ctx.globalAlpha = s.opacity;

                        // Trail
                        var gradient = ctx.createLinearGradient(
                            s.x, s.y,
                            s.x - s.length * (s.speedX / 10),
                            s.y - s.length * (s.speedY / 10)
                        );
                        gradient.addColorStop(0, '#fff');
                        gradient.addColorStop(0.4, '#c084fc');
                        gradient.addColorStop(1, 'transparent');

                        ctx.strokeStyle = gradient;
                        ctx.lineWidth = s.width;
                        ctx.beginPath();
                        ctx.moveTo(s.x, s.y);
                        ctx.lineTo(
                            s.x - s.length * (s.speedX / 10),
                            s.y - s.length * (s.speedY / 10)
                        );
                        ctx.stroke();

                        // Head glow
                        ctx.beginPath();
                        ctx.arc(s.x, s.y, 2, 0, Math.PI * 2);
                        ctx.fillStyle = '#fff';
                        ctx.fill();

                        ctx.restore();

                        // Update shooting star
                        s.x += s.speedX;
                        s.y += s.speedY;
                        s.opacity -= 0.012;

                        // Remove faded shooting stars
                        if (s.opacity <= 0) {
                            shootingStars.splice(i, 1);
                        }
                    }
                }
            }
        });

        // Override the off-screen reset for galaxy (stars are stationary)
        var originalAnimate = DashVibeParticles.animate;
        DashVibeParticles.animate = function () {
            this.ctx.clearRect(0, 0, this.width, this.height);

            for (var i = 0; i < this.particles.length; i++) {
                var p = this.particles[i];

                if (this.config.update) {
                    this.config.update(p, this.width, this.height);
                }

                if (this.config.draw) {
                    this.config.draw(this.ctx, p);
                }

                // Stars don't go off screen, no reset needed
            }

            this.animationId = requestAnimationFrame(this.animate.bind(this));
        };

    });
})();
