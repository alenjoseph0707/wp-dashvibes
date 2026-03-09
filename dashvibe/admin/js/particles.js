/**
 * DashVibe Particle Engine
 *
 * A lightweight HTML5 Canvas particle system for dashboard effects.
 * Each theme registers its own particle behavior.
 */
(function () {
    'use strict';

    window.DashVibeParticles = {

        canvas: null,
        ctx: null,
        particles: [],
        animationId: null,
        width: 0,
        height: 0,
        config: {},

        /**
         * Initialize the particle canvas
         */
        init: function (config) {
            this.canvas = document.getElementById('dashvibe-canvas');
            if (!this.canvas) return;

            this.ctx = this.canvas.getContext('2d');
            this.config = config || {};
            this.resize();
            this.createParticles();
            this.animate();

            window.addEventListener('resize', this.resize.bind(this));
        },

        /**
         * Handle resize
         */
        resize: function () {
            this.width = window.innerWidth;
            this.height = window.innerHeight;
            this.canvas.width = this.width;
            this.canvas.height = this.height;
        },

        /**
         * Create initial particles based on config
         */
        createParticles: function () {
            this.particles = [];
            var count = this.config.count || 60;

            for (var i = 0; i < count; i++) {
                this.particles.push(this.createParticle());
            }
        },

        /**
         * Create a single particle using the theme's factory
         */
        createParticle: function (resetY) {
            if (this.config.factory) {
                return this.config.factory(this.width, this.height, resetY);
            }

            // Default particle
            return {
                x: Math.random() * this.width,
                y: resetY ? -10 : Math.random() * this.height,
                size: Math.random() * 4 + 1,
                speedY: Math.random() * 1 + 0.3,
                speedX: Math.random() * 0.5 - 0.25,
                opacity: Math.random() * 0.7 + 0.3,
            };
        },

        /**
         * Animation loop
         */
        animate: function () {
            this.ctx.clearRect(0, 0, this.width, this.height);

            for (var i = 0; i < this.particles.length; i++) {
                var p = this.particles[i];

                // Use theme's update function or default
                if (this.config.update) {
                    this.config.update(p, this.width, this.height);
                } else {
                    p.y += p.speedY;
                    p.x += p.speedX;
                }

                // Use theme's draw function or default
                if (this.config.draw) {
                    this.config.draw(this.ctx, p);
                } else {
                    this.ctx.beginPath();
                    this.ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    this.ctx.fillStyle = 'rgba(255,255,255,' + p.opacity + ')';
                    this.ctx.fill();
                }

                // Reset particle if off screen
                if (p.y > this.height + 20 || p.x < -20 || p.x > this.width + 20) {
                    this.particles[i] = this.createParticle(true);
                }
            }

            this.animationId = requestAnimationFrame(this.animate.bind(this));
        },

        /**
         * Stop animation
         */
        destroy: function () {
            if (this.animationId) {
                cancelAnimationFrame(this.animationId);
            }
            if (this.canvas) {
                this.ctx.clearRect(0, 0, this.width, this.height);
            }
        }
    };

})();
