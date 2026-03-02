<?php
if (! defined('ABSPATH')) exit;

function ghost_css_get_defaults() {
    return [
        // Farben
        'color_primary'   => '#c41e3a',
        'color_secondary' => '#2ecc71',
        'color_text'      => '#1f2937',
        'color_bg'        => '#53535f',
        'color_white'     => '#ffffff',
        'color_dark'      => '#111827',
        'color_black'     => '#000000',
        'color_muted'     => '#e5e7eb',

        // Typografie
        'type_base_min'   => 16,
        'type_base_max'   => 18,
        'type_scale_min'  => 1.2,
        'type_scale_max'  => 1.333,

        // Spacing
        'space_unit'      => 8,

        // Layout
        'container_max_width' => 1366,

        // Radius
        'radius_unit'     => 4,
    ];
}

function ghost_css_get_settings() {
    $defaults = ghost_css_get_defaults();
    $saved    = get_option('ghost_css_settings', []);
    return wp_parse_args($saved, $defaults);
}

function ghost_css_modular_scale($base, $ratio, $step) {
    return $base * pow($ratio, $step);
}

function ghost_css_fluid($min_size, $max_size, $min_vw = 375, $max_vw = 1400) {
    $slope          = ($max_size - $min_size) / ($max_vw - $min_vw);
    $y_intersection = -$min_vw * $slope + $min_size;
    $slope_vw       = $slope * 100;

    $min_px = round($min_size, 10) . 'px';
    $max_px = round($max_size, 10) . 'px';

    $y_str = round($y_intersection, 10) . 'px';
    $s_str = round($slope_vw, 10) . 'vw';

    return "clamp({$min_px}, {$y_str} + {$s_str}, {$max_px})";
}

function ghost_css_generate_css($settings = null) {
    if ($settings === null) {
        $settings = ghost_css_get_settings();
    }

    $defaults = ghost_css_get_defaults();
    $css_vars = [];

    // --- Farben ---
    $color_keys = ['primary', 'secondary', 'text', 'bg', 'white', 'dark', 'black', 'muted'];
    foreach ($color_keys as $key) {
        $setting_key = 'color_' . $key;
        if ($settings[$setting_key] !== $defaults[$setting_key]) {
            $css_vars["--{$key}"] = $settings[$setting_key];
        }
    }

    // --- Typografie ---
    $type_changed = (
        (float) $settings['type_base_min']  !== (float) $defaults['type_base_min'] ||
        (float) $settings['type_base_max']  !== (float) $defaults['type_base_max'] ||
        (float) $settings['type_scale_min'] !== (float) $defaults['type_scale_min'] ||
        (float) $settings['type_scale_max'] !== (float) $defaults['type_scale_max']
    );

    if ($type_changed) {
        $css_vars['--root-font-size'] = '100%';
        $steps = [
            'xs'  => -2,
            's'   => -1,
            'm'   => 0,
            'l'   => 1,
            'xl'  => 2,
            'xxl' => 3,
            '3xl' => 4,
        ];

        foreach ($steps as $name => $step) {
            $min = ghost_css_modular_scale((float) $settings['type_base_min'], (float) $settings['type_scale_min'], $step);
            $max = ghost_css_modular_scale((float) $settings['type_base_max'], (float) $settings['type_scale_max'], $step);
            $css_vars["--text-{$name}"] = ghost_css_fluid($min, $max);
        }
    }

    // --- Spacing ---
    if ((float) $settings['space_unit'] !== (float) $defaults['space_unit']) {
        $unit = (float) $settings['space_unit'];
        $space_multipliers = [
            'none' => 0,
            'xs'   => 0.5,
            's'    => 0.75,
            'm'    => 1,
            'l'    => 3,
            'xl'   => 4,
            'xxl'  => 8,
            'huge' => 16,
        ];
        foreach ($space_multipliers as $name => $mult) {
            $value = $unit * $mult;
            $css_vars["--space-{$name}"] = ($value == 0) ? '0' : $value . 'px';
        }
    }

    // --- Layout ---
    if ((float) $settings['container_max_width'] !== (float) $defaults['container_max_width']) {
        $css_vars['--container-max-width'] = (int) $settings['container_max_width'] . 'px';
    }

    // --- Radius ---
    if ((float) $settings['radius_unit'] !== (float) $defaults['radius_unit']) {
        $unit = (float) $settings['radius_unit'];
        $radius_multipliers = [
            'none'   => 0,
            'xs'     => 0.5,
            's'      => 1,
            'm'      => 2,
            'l'      => 3,
            'xl'     => 4,
            'xxl'    => 8,
        ];
        foreach ($radius_multipliers as $name => $mult) {
            $value = $unit * $mult;
            $css_vars["--radius-{$name}"] = ($value == 0) ? '0' : $value . 'px';
        }
        // circle bleibt immer gleich
    }

    // CSS zusammenbauen
    if (empty($css_vars)) {
        return '';
    }

    $lines = [":root {"];
    foreach ($css_vars as $prop => $value) {
        $lines[] = "  {$prop}: {$value};";
    }
    $lines[] = "}";

    return implode("\n", $lines) . "\n";
}

function ghost_css_write_css_file($settings = null) {
    $css = ghost_css_generate_css($settings);

    $upload_dir = wp_upload_dir();
    $dir        = $upload_dir['basedir'] . '/ghost-css';
    $file       = $dir . '/ghost-css-custom.css';

    if (! file_exists($dir)) {
        wp_mkdir_p($dir);
    }

    file_put_contents($file, $css);

    return $file;
}

function ghost_css_get_custom_css_url() {
    $upload_dir = wp_upload_dir();
    $file       = $upload_dir['basedir'] . '/ghost-css/ghost-css-custom.css';

    if (! file_exists($file)) {
        return false;
    }

    // Leere Datei nicht laden
    if (filesize($file) === 0) {
        return false;
    }

    return $upload_dir['baseurl'] . '/ghost-css/ghost-css-custom.css';
}

function ghost_css_get_custom_css_version() {
    $upload_dir = wp_upload_dir();
    $file       = $upload_dir['basedir'] . '/ghost-css/ghost-css-custom.css';

    if (file_exists($file)) {
        return filemtime($file);
    }

    return GHOST_CSS_VERSION;
}
