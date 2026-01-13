<?php
/**
 * Plugin Name: Ghost CSS
 * Description: The Invisible Architecture for Modern Web Design.
 * Author: Manuel Schwarz
 * Version: 0.2-dev1
 */

if (! defined('ABSPATH')) exit;
define('GHOST_CSS_VERSION', '0.2-dev1');

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
}
add_action('wp_enqueue_scripts', 'add_ghost_css_files');