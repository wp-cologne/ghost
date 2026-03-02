<?php
if (! defined('ABSPATH')) exit;

function ghost_css_render_settings_page() {
    $settings = ghost_css_get_settings();
    $defaults = ghost_css_get_defaults();
    $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'colors';
    ?>
    <div class="wrap ghost-css-settings">
        <h1>Ghost CSS</h1>

        <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true'): ?>
            <div class="notice notice-success is-dismissible">
                <p>Einstellungen gespeichert. CSS-Datei wurde generiert.</p>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['reset']) && $_GET['reset'] === '1'): ?>
            <div class="notice notice-success is-dismissible">
                <p>Alle Einstellungen wurden auf die Standardwerte zurückgesetzt.</p>
            </div>
        <?php endif; ?>

        <nav class="nav-tab-wrapper ghost-css-tabs">
            <?php
            $tabs = [
                'colors'     => 'Farben',
                'typography' => 'Typografie',
                'spacing'    => 'Spacing',
                'layout'     => 'Layout',
                'radius'     => 'Radius',
            ];
            foreach ($tabs as $tab_key => $tab_label): ?>
                <a href="<?php echo esc_url(add_query_arg('tab', $tab_key)); ?>"
                   class="nav-tab <?php echo $active_tab === $tab_key ? 'nav-tab-active' : ''; ?>">
                    <?php echo esc_html($tab_label); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <form method="post" action="options.php" class="ghost-css-form">
            <?php settings_fields('ghost_css_options'); ?>

            <?php // Alle Felder als hidden mitschicken, damit nicht-aktive Tabs nicht verloren gehen ?>
            <?php foreach ($settings as $key => $value): ?>
                <input type="hidden" name="ghost_css_settings[<?php echo esc_attr($key); ?>]"
                       value="<?php echo esc_attr($value); ?>"
                       class="ghost-css-hidden-<?php echo esc_attr($key); ?>">
            <?php endforeach; ?>

            <?php if ($active_tab === 'colors'): ?>
                <div class="ghost-css-section">
                    <h2>Farben</h2>
                    <p class="description">Definiere die Farbpalette deines Projekts.</p>
                    <table class="form-table">
                        <?php
                        $colors = [
                            'primary'   => 'Primary',
                            'secondary' => 'Secondary',
                            'text'      => 'Text',
                            'bg'        => 'Background',
                            'white'     => 'White',
                            'dark'      => 'Dark',
                            'black'     => 'Black',
                            'muted'     => 'Muted',
                        ];
                        foreach ($colors as $key => $label):
                            $field = 'color_' . $key;
                            ?>
                            <tr>
                                <th scope="row">
                                    <label for="<?php echo esc_attr($field); ?>"><?php echo esc_html($label); ?></label>
                                </th>
                                <td>
                                    <input type="text"
                                           id="<?php echo esc_attr($field); ?>"
                                           name="ghost_css_settings[<?php echo esc_attr($field); ?>]"
                                           value="<?php echo esc_attr($settings[$field]); ?>"
                                           class="ghost-css-color-picker"
                                           data-default-color="<?php echo esc_attr($defaults[$field]); ?>">
                                    <code class="ghost-css-var-name">--<?php echo esc_html($key); ?></code>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

            <?php elseif ($active_tab === 'typography'): ?>
                <div class="ghost-css-section">
                    <h2>Typografie</h2>
                    <p class="description">Fluid Typography basierend auf Modular Scale. Die Schriftgrößen werden automatisch zwischen den Min/Max-Werten skaliert.</p>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="type_base_min">Basis Min (px)</label></th>
                            <td>
                                <input type="number" id="type_base_min"
                                       name="ghost_css_settings[type_base_min]"
                                       value="<?php echo esc_attr($settings['type_base_min']); ?>"
                                       min="10" max="30" step="1" class="small-text">
                                <p class="description">Schriftgröße bei 375px Viewport (Mobile)</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="type_base_max">Basis Max (px)</label></th>
                            <td>
                                <input type="number" id="type_base_max"
                                       name="ghost_css_settings[type_base_max]"
                                       value="<?php echo esc_attr($settings['type_base_max']); ?>"
                                       min="10" max="40" step="1" class="small-text">
                                <p class="description">Schriftgröße bei 1400px Viewport (Desktop)</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="type_scale_min">Scale Ratio Min</label></th>
                            <td>
                                <input type="number" id="type_scale_min"
                                       name="ghost_css_settings[type_scale_min]"
                                       value="<?php echo esc_attr($settings['type_scale_min']); ?>"
                                       min="1.0" max="2.0" step="0.001" class="small-text">
                                <p class="description">Skalierungsfaktor Mobile (1.2 = Minor Third)</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="type_scale_max">Scale Ratio Max</label></th>
                            <td>
                                <input type="number" id="type_scale_max"
                                       name="ghost_css_settings[type_scale_max]"
                                       value="<?php echo esc_attr($settings['type_scale_max']); ?>"
                                       min="1.0" max="2.5" step="0.001" class="small-text">
                                <p class="description">Skalierungsfaktor Desktop (1.333 = Perfect Fourth)</p>
                            </td>
                        </tr>
                    </table>

                    <h3>Vorschau</h3>
                    <div class="ghost-css-type-preview">
                        <?php
                        $steps = ['xs' => -2, 's' => -1, 'm' => 0, 'l' => 1, 'xl' => 2, 'xxl' => 3, '3xl' => 4];
                        foreach ($steps as $name => $step):
                            $min = ghost_css_modular_scale((float) $settings['type_base_min'], (float) $settings['type_scale_min'], $step);
                            $max = ghost_css_modular_scale((float) $settings['type_base_max'], (float) $settings['type_scale_max'], $step);
                            ?>
                            <div class="ghost-css-type-row">
                                <code>--text-<?php echo $name; ?></code>
                                <span><?php echo round($min, 2); ?>px &mdash; <?php echo round($max, 2); ?>px</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php elseif ($active_tab === 'spacing'): ?>
                <div class="ghost-css-section">
                    <h2>Spacing</h2>
                    <p class="description">Alle Abstände basieren auf einer Basis-Einheit mit festen Multiplikatoren.</p>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="space_unit">Basis-Einheit (px)</label></th>
                            <td>
                                <input type="number" id="space_unit"
                                       name="ghost_css_settings[space_unit]"
                                       value="<?php echo esc_attr($settings['space_unit']); ?>"
                                       min="1" max="32" step="1" class="small-text">
                            </td>
                        </tr>
                    </table>

                    <h3>Ergebnis</h3>
                    <div class="ghost-css-spacing-preview">
                        <?php
                        $unit = (float) $settings['space_unit'];
                        $multipliers = ['none' => 0, 'xs' => 0.5, 's' => 0.75, 'm' => 1, 'l' => 3, 'xl' => 4, 'xxl' => 8, 'huge' => 16];
                        foreach ($multipliers as $name => $mult): ?>
                            <div class="ghost-css-spacing-row">
                                <code>--space-<?php echo $name; ?></code>
                                <span><?php echo $unit * $mult; ?>px</span>
                                <span class="ghost-css-multiplier">&times;<?php echo $mult; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php elseif ($active_tab === 'layout'): ?>
                <div class="ghost-css-section">
                    <h2>Layout</h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="container_max_width">Container Max Width (px)</label></th>
                            <td>
                                <input type="number" id="container_max_width"
                                       name="ghost_css_settings[container_max_width]"
                                       value="<?php echo esc_attr($settings['container_max_width']); ?>"
                                       min="600" max="2560" step="1" class="regular-text">
                                <code class="ghost-css-var-name">--container-max-width</code>
                            </td>
                        </tr>
                    </table>
                </div>

            <?php elseif ($active_tab === 'radius'): ?>
                <div class="ghost-css-section">
                    <h2>Border Radius</h2>
                    <p class="description">Alle Radien basieren auf einer Basis-Einheit mit festen Multiplikatoren.</p>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="radius_unit">Basis-Einheit (px)</label></th>
                            <td>
                                <input type="number" id="radius_unit"
                                       name="ghost_css_settings[radius_unit]"
                                       value="<?php echo esc_attr($settings['radius_unit']); ?>"
                                       min="0" max="32" step="1" class="small-text">
                            </td>
                        </tr>
                    </table>

                    <h3>Ergebnis</h3>
                    <div class="ghost-css-spacing-preview">
                        <?php
                        $unit = (float) $settings['radius_unit'];
                        $multipliers = ['none' => 0, 'xs' => 0.5, 's' => 1, 'm' => 2, 'l' => 3, 'xl' => 4, 'xxl' => 8];
                        foreach ($multipliers as $name => $mult): ?>
                            <div class="ghost-css-spacing-row">
                                <code>--radius-<?php echo $name; ?></code>
                                <span><?php echo $unit * $mult; ?>px</span>
                                <span class="ghost-css-multiplier">&times;<?php echo $mult; ?></span>
                            </div>
                        <?php endforeach; ?>
                        <div class="ghost-css-spacing-row">
                            <code>--radius-circle</code>
                            <span>500vh</span>
                            <span class="ghost-css-multiplier">fix</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php submit_button('Einstellungen speichern'); ?>
        </form>

        <form method="post" class="ghost-css-reset-form">
            <?php wp_nonce_field('ghost_css_reset_action', 'ghost_css_reset_nonce'); ?>
            <button type="submit" name="ghost_css_reset" value="1" class="button button-link-delete"
                    onclick="return confirm('Alle Einstellungen auf Standardwerte zurücksetzen?');">
                Alle Einstellungen zurücksetzen
            </button>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Color Picker initialisieren
        $('.ghost-css-color-picker').wpColorPicker({
            change: function(event, ui) {
                // Hidden-Feld aktualisieren
                var name = $(this).attr('name');
                var key = name.match(/\[(.+)\]/)[1];
                $('.ghost-css-hidden-' + key).val(ui.color.toString());
            }
        });

        // Sichtbare Inputs synchronisieren hidden fields
        $('input[name^="ghost_css_settings"]').not('[type="hidden"]').on('change input', function() {
            var name = $(this).attr('name');
            var key = name.match(/\[(.+)\]/)[1];
            $('.ghost-css-hidden-' + key).val($(this).val());
        });
    });
    </script>
    <?php
}
