<?php
/*
 * Plugin Name: WooCommerce Product Sections
 * Text Domain: woocommerce-product-sections
 * Domain Path: /languages
 */

register_activation_hook(__FILE__, 'wps_activate');
register_deactivation_hook(__FILE__, 'wps_deactivate');

function wps_activate() {

}

function wps_deactivate() {

}

add_action('add_meta_boxes', 'wps_add_product_section_editor');
function wps_add_product_section_editor() {
    add_meta_box(
        'wps_product_section_box',
        __('Product Sections', 'woocommerce-product-sections'),
        'wps_display_product_section_list',
        'product'
    );
}

function wps_display_product_section_list() {
    echo '<div class="wps-product-sections">';
    echo '  <div class="wps-product-section-template">';
    echo '      <span class="wps-product-section-drag">↕️</span>';
    echo '      <input type="hidden">';
    echo '      <input type="text">';
    echo '      <select required>';
    printf('        <option value="table">%s</option>', __('Table', 'woocommerce-product-sections'));
    printf('        <option value="accordion">%s</option>', __('Accordion', 'woocommerce-product-sections'));
    printf('        <option value="text">%s</option>', __('Text', 'woocommerce-product-sections'));
    echo '      </select>';
    printf('    <button class="button wps-product-section-delete">%s</button>', __('Delete', 'woocommerce-product-sections'));
    echo '  </div>';    
    global $post;
    $meta = get_post_meta($post->ID, 'wps_product_sections', true);
    if (!empty($meta)) {
        $sections = json_decode($meta, true);
        foreach ($sections as $id => $section) {
            echo '  <div class="wps-product-section">';
            echo '        <span class="wps-product-section-drag">↕️</span>';
            printf('      <input type="hidden" name="wps-section-id[]" value="%s">', esc_attr($id));
            printf('      <input type="text" name="wps-section-title[]" value="%s">', esc_attr($section['title']));
            printf('      <select name="wps-section-type[]">');
            printf('        <option value="table" %s>%s</option>', 
                                selected($section['type'], 'table', false),
                                __('Table', 'woocommerce-product-sections'));
            printf('        <option value="accordion" %s>%s</option>',
                                selected($section['type'], 'accordion', false),
                                __('Accordion', 'woocommerce-product-sections'));
            printf('        <option value="text" %s>%s</option>',
                                selected($section['type'], 'text', false),
                                __('Text', 'woocommerce-product-sections'));
            echo '      </select>';
            printf('    <button class="button wps-product-section-delete">%s</button>', __('Delete', 'woocommerce-product-sections'));
            echo '  </div>';
        }
    }
    echo '</div>';
    printf('<button class="button wps-product-section-add">%s</button>', __('Add a section', 'woocommerce-product-sections'));
    submit_button();
}

add_action('save_post', 'wps_save_product_section_list');
function wps_save_product_section_list($post_id) {
    
}

add_action('add_meta_boxes', 'wps_add_product_section_metaboxes', 10, 2);
function wps_add_product_section_metaboxes($post_type, $post) {
    $meta = get_post_meta($post->ID, 'wps_product_sections', true);
    $sections = json_decode($meta, true);
    foreach ($sections as $id => $section) {
        add_meta_box(
            sprintf('wps_%s', $id),
            ($section['title'] === null || trim($section['title']) === '') ?
                __('Untitled Section', 'woocommerce-product-sections')
                    : $section['title'],
            'wps_display_product_section_metabox',
            'product',
            'advanced',
            'default',
            [
                'id' => $id,
                'section' => $section
            ]
        );
    }
}

function wps_display_product_section_metabox($post, $args) {
    $id = $args['args']['id'];
    $section = $args['args']['section'];
    switch ($section['type']) {
        case 'table':
            wps_display_product_table_section($post, $id, $section);
            break;
        case 'accordion':
            wps_display_product_accordion_section($post, $id, $section);
            break;
        case 'text':
            wps_display_product_text_section($post, $id, $section);
            break;
        default:
            break;
    }
}

function wps_find_array_by_nested_keys($keys, $array) {
    $current_level = $array;
    foreach ($keys as $key) {
        if (!array_key_exists($key, $current_level)) {
            return NULL;
        }

        $current_level = $current_level[$key];
    }
    return $current_level;
}

function wps_display_product_table_section($post, $id, $section) {
    echo '<table class="wps-table-section">';
    echo '<thead>';
    echo '  <tr>';
    echo '        <td></td>';
    printf('      <td>
                    <label for="%s">%s</label>
                    <input id ="%s" type="text" value="%s" name="wps-table-left-header[%s]">
                  </td>',
                        "wps-table-left-header-{$id}",
                        __('Left header', 'woocommerce-product-sections'),
                        "wps-table-left-header-{$id}",
                        esc_attr(wps_find_array_by_nested_keys(['meta', 'left-header'], $section)),
                        esc_attr($id));
    printf('      <td>
                    <label for="%s">%s</label>
                    <input id="%s" type="text" value="%s" name="wps-table-right-header[%s]">
                  </td>',
                        "wps-table-right-header-{$id}",
                        __('Right header', 'woocommerce-product-sections'),
                        "wps-table-right-header-{$id}",
                        esc_attr(wps_find_array_by_nested_keys(['meta', 'right-header'], $section)),
                        esc_attr($id));
    echo '  </tr>';
    echo '</thead>';
    echo '<tbody class="wps-table-entries">';
    printf('  <tr class="wps-table-entry-template" data-id="%s">', $id);
    echo '      <td>';
    echo '          <span class="wps-table-entry-drag">↕️</span>';
    echo '      </td>';
    echo '      <td>';
    echo '          <input type="text" value="">';
    echo '      </td>';
    echo '      <td>';
    echo '          <input type="text" value="">';
    echo '      </td>';
    echo '      <td>';
    printf('          <button class="button wps-table-entry-delete">%s</button>', esc_html(__('Delete', 'woocommerce-product-sections')));
    echo '      </td>';
    echo '  </tr>';

    $entries = $section['entries'];
    if (!empty($entries)) {
        foreach ($entries as $entry) {
            echo '<tr class="wps-table-entry">';
            echo '  <td>';
            echo '      <span class="wps-table-entry-drag">↕️</span>';
            echo '  </td>';
            echo '  <td>';
            printf('    <input type="text" name="wps-table-entry-name[%s][]" value="%s">', esc_attr($id), esc_attr($entry['name']));
            echo '  </td>';
            echo '  <td>';
            printf('    <input type="text" name="wps-table-entry-value[%s][]" value="%s">', esc_attr($id), esc_attr($entry['value']));
            echo '  </td>';
            echo '  <td>';
            printf('    <button class="button wps-table-entry-delete">%s</button>', esc_html(__('Delete', 'woocommerce-product-sections')));
            echo '  </td>';
            echo '</tr>';
        }
    }
    echo '</tbody>';
    echo '<tfoot>';
    echo '  <td>';
    echo '  </td>';
    echo '  <td>';
    printf('    <button class="button wps-table-entry-add">%s</button>', esc_html(__('Add an entry', 'woocommerce-product-sections')));
    submit_button();
    echo '  </td>';
    echo '  <td>';
    echo '  </td>';
    echo '  <td>';
    echo '  </td>';
    echo '</tfoot>';
    echo '</table>';
}

add_action('admin_footer', 'wps_add_wp_editor_modal');
function wps_add_wp_editor_modal() {
    echo '<div class="wps-wp-editor-modal">';
    echo '  <div class="wps-wp-editor-modal-content">';
    echo '      <div class="wps-wp-editor-modal-header">';
    echo '          <span class="wps-wp-editor-modal-close">';
    echo '              &times;';
    echo '          </span>';
    echo '      </div>';
    echo '      <div class="wps-wp-editor-modal-body">';
    echo '          <textarea id="wps-wp-editor"></textarea>';
    echo '      </div>';
    echo '      <div class="wps-wp-editor-modal-footer">';
    printf('          <button class="wps-wp-editor-modal-button wps-wp-editor-modal-ok">%s</button>', __('OK', 'woocommerce-product-sections'));
    printf('          <button class="wps-wp-editor-modal-button wps-wp-editor-modal-cancel">%s</button>', __('Cancel', 'woocommerce-product-sections'));
    echo '      </div>';
    echo '  </div>';
    echo '</div>';
}

function wps_display_product_accordion_section($post, $id, $section) {
    echo '<div class="wps-accordion-section">';
    echo '  <div class="wps-accordion-entries">';
    printf('    <div class="wps-accordion-entry wps-accordion-entry-template" data-id="%s">', esc_attr($id));
    echo '          <div class="wps-accordion-entry-flex">';
    echo '              <div class="wps-accordion-entry-flex-item-1">';
    echo '                  <span class="wps-accordion-entry-drag">↕️</span>';
    echo '              </div>';
    echo '              <div class="wps-accordion-entry-flex-item-2">';
    echo '                  <button class="wps-accordion-entry-trigger"></button>';
    echo '                  <div class="wps-accordion-entry-body">';
    echo '                      <input type="text" value="">';
    echo '                      <input type="hidden" value="">';
    echo '                      <div class="wps-accordion-entry-content">';
    echo '                      </div>';
    printf('                    <button class="button wps-accordion-entry-content-edit">%s</button>', __('Edit', 'woocommerce-product-sections'));
    printf('                    <button class="button wps-accordion-entry-delete">%s</button>', __('Delete', 'woocommerce-product-sections'));
    echo '                  </div>';
    echo '              </div>';
    echo '          </div>';
    echo '      </div>';
    $entries = $section['entries'];
    foreach ($entries as $index => $entry) {
        $content = base64_decode($entry['content']);
        echo '  <div class="wps-accordion-entry">';
        echo '      <div class="wps-accordion-entry-flex">';
        echo '          <div class="wps-accordion-entry-flex-item-1">';
        echo '              <span class="wps-accordion-entry-drag">↕️</span>';
        echo '          </div>';
        echo '          <div class="wps-accordion-entry-flex-item-2">';
        printf('            <button class="wps-accordion-entry-trigger">%s</button>', esc_html($entry['title']));
        echo '              <div class="wps-accordion-entry-body">';
        printf('                <input type="text" name="wps-accordion-entry-title[%s][]" value="%s">', esc_attr($id), esc_attr($entry['title']));
        printf('                <input type="hidden" name="wps-accordion-entry-content[%s][]" value="%s">', esc_attr($id), esc_attr($content));
        echo '                  <div class="wps-accordion-entry-content">';
        echo                        $content;
        echo '                  </div>';
        printf('                <button class="button-secondary wps-accordion-entry-content-edit">%s</button>', __('Edit', 'woocommerce-product-sections'));
        printf('                <button class="button-secondary delete wps-accordion-entry-delete">%s</button>', __('Delete', 'woocommerce-product-sections'));
        echo '              </div>';
        echo '          </div>';
        echo '      </div>';
        echo '  </div>';
    }
    echo '  </div>';
    echo '  <div class="wps-accordion-buttons">';
    printf('    <button class="button wps-accordion-entry-add">%s</button>', __('Add an entry', 'woocommerce-product-sections'));
    submit_button();
    echo '  </div>';
    echo '</div>';
}

function wps_display_product_text_section($post, $id, $section) {
    $content = base64_decode($section['content']);
    printf('<div class="wps-text-section" data-id="%s">', esc_attr($id));
    printf('    <input type="hidden" name="wps-text-section-content[%s]" value="%s">', esc_attr($id), esc_attr($content));
    echo '      <div class="wps-text-section-content">';
    echo            $content;
    echo '      </div>';
    printf('    <button class="button wps-text-section-edit">%s</button>', __('Edit', 'woocommerce-product-sections'));
    submit_button();
    echo '</div>';
}

add_action('save_post', 'wps_save_product_sections', 10, 3);
function wps_save_product_sections($post_id, $post, $update) {
    if (wp_is_post_autosave($post_id)) {
        return;
    }

    $sections = [];

    if (array_key_exists('wps-section-id', $_POST) &&
        array_key_exists('wps-section-title', $_POST) &&
        array_key_exists('wps-section-type', $_POST)) {
        $ids = $_POST['wps-section-id'];
        $titles = $_POST['wps-section-title'];
        $types = $_POST['wps-section-type'];

        $meta = get_post_meta($post_id, 'wps_product_sections', true);
        $current_sections = json_decode($meta, true);

        if (empty($current_sections)) {
            $current_sections = [];
        }

        foreach ($ids as $index => $id) {
            if (array_key_exists($id, $current_sections)) {
                $sections[$id] = $current_sections[$id];
            } else {
                if (empty($id)) {
                    $id = wp_generate_uuid4();
                }                
                $sections[$id] = [];
            }

            $sections[$id]['title'] = $titles[$index];
            $sections[$id]['type'] = $types[$index];
        }
    }
    
    if (array_key_exists('wps-table-entry-name', $_POST) &&
        array_key_exists('wps-table-entry-value', $_POST)) {
            $ids = array_keys($_POST['wps-table-entry-name']);
            foreach ($ids as $id) {
                if (!array_key_exists($id, $sections)) {
                    continue;
                }

                $names = $_POST['wps-table-entry-name'][$id];
                $values = $_POST['wps-table-entry-value'][$id];

                $entries = [];
                foreach ($names as $index => $name) {
                    $entries[] = [
                        'name' => $name,
                        'value' => $values[$index]
                    ];
                }
                $sections[$id]['entries'] = $entries;
            }
    }

    if (array_key_exists('wps-table-left-header', $_POST) &&
        array_key_exists('wps-table-right-header', $_POST)) {
            $ids = array_keys($_POST['wps-table-left-header']);

            foreach ($ids as $id) {
                $sections[$id]['meta'] = [
                    'left-header' => $_POST['wps-table-left-header'][$id],
                    'right-header' => $_POST['wps-table-right-header'][$id]
                ];
            }
    }

    if (array_key_exists('wps-accordion-entry-title', $_POST) &&
        array_key_exists('wps-accordion-entry-content', $_POST)) {
        $ids = array_keys($_POST['wps-accordion-entry-title']);
        foreach ($ids as $id) {
            if (!array_key_exists($id, $sections)) {
                continue;
            }

            $titles = $_POST['wps-accordion-entry-title'][$id];
            $contents = $_POST['wps-accordion-entry-content'][$id];

            $entries = [];
            foreach ($titles as $index => $title) {
                $entries[] = [
                    'title' => $title,
                    'content' => base64_encode(stripslashes($contents[$index]))
                ];
            }
            $sections[$id]['entries'] = $entries;
        }
    }

    if (array_key_exists('wps-text-section-content', $_POST)) {
        $ids = array_keys($_POST['wps-text-section-content']);
        foreach ($ids as $id) {
            if (!array_key_exists($id, $sections)) {
                continue;
            }

            $content = $_POST['wps-text-section-content'][$id];
            $sections[$id]['content'] = base64_encode(stripslashes($content));
        }
    }

    update_post_meta(
        $post_id,
        'wps_product_sections',
        json_encode($sections, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS));
}

add_filter('woocommerce_product_tabs', 'wps_display_product_section_tabs');
function wps_display_product_section_tabs($tabs) {
    global $post;
    $meta = get_post_meta($post->ID, 'wps_product_sections', true);
    $sections = json_decode($meta, true);

    foreach ($sections as $id => $section) {
        $tabs[$id] = array(
            'title' => $section['title'],
            'priority' => 30,
            'callback' => 'wps_display_product_section',
            'section' => $section
        );
    }

    return $tabs;
}

function wps_display_product_section($id, $tab) {
    $section = $tab['section'];
    switch ($section['type']) {
        case 'table':
            wps_display_product_table_section_on_frontend($section);
            break;
        case 'accordion':
            wps_display_product_accordion_section_on_frontend($section);
            break;
        case 'text':
            wps_display_product_text_section_on_frontend($section);
            break;
        default:
            break;
    }
}

function wps_display_product_table_section_on_frontend($section) {
    echo '<table class="wps-table-section">';
    echo '    <thead>';
    echo '        <tr>';
    echo '            <td>';
    echo '                ' . esc_html(wps_find_array_by_nested_keys(['meta', 'left-header'], $section));
    echo '            </td>';
    echo '            <td>';
    echo '                ' . esc_html(wps_find_array_by_nested_keys(['meta', 'right-header'], $section));
    echo '            </td>';
    echo '        </tr>';
    echo '    </thead>';
    echo '    <tbody>';
    foreach ($section['entries'] as $entry) {
        $name = esc_html($entry['name']);
        $value = esc_html($entry['value']);
        echo '        <tr>';
        printf('          <td>%s</td>', esc_html($name));
        printf('          <td>%s</td>', esc_html($value));
        echo '        </tr>';
    }
    echo '    </tbody>';
    echo '</table>';
}

function wps_display_product_accordion_section_on_frontend($section) {
    $entries = $section['entries'];
    echo '<div class="wps-accordion-section">';
    foreach ($entries as $index => $entry) {
        $content = base64_decode($entry['content']);
        echo '  <div class="wps-accordion-entry">';
        printf('    <button class="wps-accordion-entry-trigger">%s</button>', esc_html($entry['title']));
        echo '      <div class="wps-accordion-entry-content">';
        echo            $content;
        echo '      </div>';
        echo '  </div>';
    }
    echo '</div>';
}

function wps_display_product_text_section_on_frontend($section) {
    $content = base64_decode($section['content']);
    printf('<div class="wps-text-section">');
    echo '      <div class="wps-text-section-content">';
    echo            $content;
    echo '      </div>';
    echo '</div>';
}

add_action('admin_enqueue_scripts', 'wps_add_admin_scripts');
function wps_add_admin_scripts() {
    if (is_admin()) {
        wp_enqueue_script('wps_product_sections_js', plugin_dir_url(__FILE__) . '/admin/js/product-sections.js', array('jquery'), false, true);
        wp_enqueue_style('wps_product_sections_css', plugin_dir_url(__FILE__) . '/admin/css/product-sections.css');
        
        wp_enqueue_script('wps_product_table_section_js', plugin_dir_url(__FILE__) . '/admin/js/product-table-section.js', array('jquery'), false, true);
        wp_enqueue_style('wps_product_table_section_css', plugin_dir_url(__FILE__) . '/admin/css/product-table-section.css');

        wp_enqueue_script('wps_product_accordion_section_js', plugin_dir_url(__FILE__) . '/admin/js/product-accordion-section.js', array('jquery'), false, true);
        wp_enqueue_style('wps_product_accordion_section_css', plugin_dir_url(__FILE__) . '/admin/css/product-accordion-section.css');

        wp_enqueue_script('wps_product_text_section_js', plugin_dir_url(__FILE__) . '/admin/js/product-text-section.js', array('jquery', 'wps_wp_editor_modal_js'), false, true);
        wp_enqueue_style('wps_product_text_section_css', plugin_dir_url(__FILE__) . '/admin/css/product-text-section.css');

        wp_enqueue_script('wps_wp_editor_modal_js', plugin_dir_url(__FILE__) . '/admin/js/wp-editor-modal.js', array('jquery'), false, true);
        wp_enqueue_style('wps_wp_editor_modal_css', plugin_dir_url(__FILE__) . '/admin/css/wp-editor-modal.css');

        wp_enqueue_editor();
    }
}
