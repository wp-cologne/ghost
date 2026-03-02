<?php
if (! defined('ABSPATH')) exit;

function ghost_css_register_settings() {
    register_setting('ghost_css_options', 'ghost_css_settings', [
        'sanitize_callback' => 'ghost_css_sanitize_settings',
    ]);
}
add_action('admin_init', 'ghost_css_register_settings');

function ghost_css_sanitize_settings($input) {
    $defaults  = ghost_css_get_defaults();
    $sanitized = [];

    // Farben
    $color_keys = ['primary', 'secondary', 'text', 'bg', 'white', 'dark', 'black', 'muted'];
    foreach ($color_keys as $key) {
        $field = 'color_' . $key;
        if (isset($input[$field]) && preg_match('/^#[0-9a-fA-F]{6}$/', $input[$field])) {
            $sanitized[$field] = $input[$field];
        } else {
            $sanitized[$field] = $defaults[$field];
        }
    }

    // Typografie
    $type_fields = [
        'type_base_min'  => [10, 30],
        'type_base_max'  => [10, 40],
        'type_scale_min' => [1.0, 2.0],
        'type_scale_max' => [1.0, 2.5],
    ];
    foreach ($type_fields as $field => $range) {
        if (isset($input[$field]) && is_numeric($input[$field])) {
            $val = (float) $input[$field];
            $sanitized[$field] = max($range[0], min($range[1], $val));
        } else {
            $sanitized[$field] = $defaults[$field];
        }
    }

    // Spacing
    if (isset($input['space_unit']) && is_numeric($input['space_unit'])) {
        $sanitized['space_unit'] = max(1, min(32, (float) $input['space_unit']));
    } else {
        $sanitized['space_unit'] = $defaults['space_unit'];
    }

    // Layout
    if (isset($input['container_max_width']) && is_numeric($input['container_max_width'])) {
        $sanitized['container_max_width'] = max(600, min(2560, (int) $input['container_max_width']));
    } else {
        $sanitized['container_max_width'] = $defaults['container_max_width'];
    }

    // Radius
    if (isset($input['radius_unit']) && is_numeric($input['radius_unit'])) {
        $sanitized['radius_unit'] = max(0, min(32, (float) $input['radius_unit']));
    } else {
        $sanitized['radius_unit'] = $defaults['radius_unit'];
    }

    // CSS-Datei generieren
    ghost_css_write_css_file($sanitized);

    return $sanitized;
}

// Reset-Handler
function ghost_css_handle_reset() {
    if (
        ! isset($_POST['ghost_css_reset']) ||
        ! isset($_POST['ghost_css_reset_nonce']) ||
        ! wp_verify_nonce($_POST['ghost_css_reset_nonce'], 'ghost_css_reset_action')
    ) {
        return;
    }

    if (! current_user_can('manage_options')) {
        return;
    }

    delete_option('ghost_css_settings');
    ghost_css_write_css_file(ghost_css_get_defaults());

    wp_redirect(add_query_arg([
        'page'  => 'ghost-css',
        'reset' => '1',
    ], admin_url('options-general.php')));
    exit;
}
add_action('admin_init', 'ghost_css_handle_reset');
