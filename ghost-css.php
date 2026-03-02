<?php
/**
 * Plugin Name: Ghost CSS
 * Description: The Invisible Architecture for Modern Web Design.
 * Author: Manuel Schwarz
 * Version: 0.2-dev1
 */

if (! defined('ABSPATH')) exit;
define('GHOST_CSS_VERSION', '0.2-dev1');

// Admin-Dateien laden
require_once plugin_dir_path(__FILE__) . 'admin/css-generator.php';
require_once plugin_dir_path(__FILE__) . 'admin/settings-register.php';
require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';

// --- Admin-Menü ---
function ghost_css_admin_menu() {
    add_options_page(
        'Ghost CSS',
        'Ghost CSS',
        'manage_options',
        'ghost-css',
        'ghost_css_render_settings_page'
    );
}
add_action('admin_menu', 'ghost_css_admin_menu');

// --- Admin-Assets ---
function ghost_css_admin_assets($hook) {
    if ($hook !== 'settings_page_ghost-css') {
        return;
    }

    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style(
        'ghost-css-admin',
        plugin_dir_url(__FILE__) . 'admin/admin.css',
        [],
        filemtime(plugin_dir_path(__FILE__) . 'admin/admin.css')
    );
}
add_action('admin_enqueue_scripts', 'ghost_css_admin_assets');

// --- Frontend CSS ---
function add_ghost_css_files()
{
    $plugin_dir_path = plugin_dir_path(__FILE__);
    $plugin_dir_url  = plugin_dir_url(__FILE__);

    $files = [
        'ghost-css' => 'css/standalone.css',
    ];

    foreach ($files as $handle => $relative_path) {

        $full_path = $plugin_dir_path . $relative_path;
        $full_url  = $plugin_dir_url . $relative_path;
        $version = GHOST_CSS_VERSION;

        if (file_exists($full_path)) {
            $version = filemtime($full_path);
        }

        wp_enqueue_style($handle, $full_url, [], $version);
    }

    // Custom CSS aus uploads laden (überschreibt Defaults)
    $custom_url = ghost_css_get_custom_css_url();
    if ($custom_url) {
        wp_enqueue_style(
            'ghost-css-custom',
            $custom_url,
            ['ghost-css'],
            ghost_css_get_custom_css_version()
        );
    }
}
add_action('wp_enqueue_scripts', 'add_ghost_css_files');
