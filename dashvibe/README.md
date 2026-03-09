# 🎨 DashVibe — Seasonal Dashboard Themes for WordPress

> Transform your boring WordPress admin dashboard into a visual experience with animated themes.

![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-7.2%2B-purple?logo=php)
![License](https://img.shields.io/badge/License-GPLv2-green)
![Version](https://img.shields.io/badge/Version-1.0.0-orange)

---

## ✨ What is DashVibe?

DashVibe is a WordPress plugin that lets you apply fun, animated visual themes to your `wp-admin` dashboard. It includes a particle engine, custom color schemes, and a beautiful theme picker — all without affecting your public website.

## 🎭 Available Themes

| Theme | Description |
|-------|-------------|
| ❄️ **Snowfall** | Falling snowflakes with wobble physics, icy blue palette, frosted glass widget effects |
| 🍕 **Pizza Party** | Floating pizza emoji, cheese particles, warm red/orange/yellow color scheme |
| 🌌 **Galaxy** | Twinkling stars with glow, random shooting stars, deep purple nebula palette |

## 🚀 Features

- **One-Click Activation** — Pick a theme from the visual dashboard and it's live instantly
- **Admin Bar Quick Switch** — Change themes from anywhere in wp-admin via the toolbar menu
- **Lightweight Animations** — Pure HTML5 Canvas with `requestAnimationFrame` — no jQuery animations, no heavy libraries
- **Full Color Scheme** — Each theme overrides sidebar, toolbar, widgets, and content area colors
- **Admin Only** — Zero impact on your public-facing website
- **Clean Code** — Follows WordPress coding standards, singleton pattern, proper hooks & enqueue

## 📦 Installation

### Manual Installation

1. Download or clone this repository
2. Copy the `dashvibe` folder to `/wp-content/plugins/`
3. Activate **DashVibe** from the Plugins page in WordPress
4. Navigate to **DashVibe** in the admin sidebar

### From GitHub

```bash
cd /path/to/wordpress/wp-content/plugins/
git clone https://github.com/yourusername/dashvibe.git
```

Then activate from **Plugins → Installed Plugins**.

## 📁 Project Structure

```
dashvibe/
├── dashvibe.php                 # Main plugin file (entry point)
├── readme.txt                   # WordPress.org readme
├── README.md                    # GitHub readme (this file)
│
├── includes/
│   ├── class-settings.php       # Settings page, menu, save logic
│   └── class-theme-loader.php   # Loads active theme CSS/JS/canvas
│
├── admin/
│   ├── css/
│   │   ├── dashboard.css        # Settings page styles
│   │   ├── base-admin.css       # Shared admin color overrides
│   │   └── themes/
│   │       ├── snowfall.css     # Snowfall-specific styles
│   │       ├── pizza-party.css  # Pizza Party-specific styles
│   │       └── galaxy.css       # Galaxy-specific styles
│   │
│   └── js/
│       ├── dashboard.js         # Settings page preview animations
│       ├── particles.js         # Canvas particle engine
│       └── themes/
│           ├── snowfall.js      # Snowfall particles config
│           ├── pizza-party.js   # Pizza particles config
│           └── galaxy.js        # Galaxy stars config
│
└── templates/
    └── settings-page.php        # Dashboard HTML template
```

## 🛠️ Tech Stack

- **PHP 7.2+** — WordPress Plugin API, hooks, filters, Settings API, User Meta API
- **JavaScript (ES5)** — HTML5 Canvas 2D, `requestAnimationFrame`
- **CSS3** — Custom properties, backdrop-filter, gradients, animations
- **WordPress APIs** — `wp_enqueue_script/style`, `admin_bar_menu`, `wp_admin_css_color`

## 🤝 Contributing

Contributions are welcome! Here's how to get started:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/autumn-leaves-theme`
3. Make your changes
4. Test in a local WordPress environment
5. Commit: `git commit -m 'Add autumn leaves theme'`
6. Push: `git push origin feature/autumn-leaves-theme`
7. Open a Pull Request

### Adding a New Theme

To add a new theme, you need to:

1. Register it in `dashvibe.php` → `register_themes()` method
2. Create `admin/css/themes/your-theme.css` for styles
3. Create `admin/js/themes/your-theme.js` with particle config
4. Add a preview in `admin/js/dashboard.js`

## 📋 Roadmap

- [ ] Per-user theme preferences
- [ ] Auto-switch themes by season/date
- [ ] Custom theme builder (pick particles + colors)
- [ ] More themes: Autumn Leaves, Cherry Blossom, Underwater, Neon City, Retro Arcade
- [ ] Theme intensity slider (subtle → full effect)
- [ ] Sound effects toggle (ambient sounds per theme)
- [ ] WordPress.org plugin directory submission

## 📜 License

GPLv2 or later — [License](https://www.gnu.org/licenses/gpl-2.0.html)

## ⭐ Support

If you like this plugin, give it a star! It helps others discover the project.

---

**Made with ❤️ for the WordPress community**
