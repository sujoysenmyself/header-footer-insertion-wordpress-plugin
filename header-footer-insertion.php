<?php
/*
Plugin Name: Header Footer Insertion
Plugin URI: https://wordpress.org/plugins/header-footer-insertion/
Description: A plugin to add custom styles and scripts to the header or footer.
Version: 1.0.1
Requires at least: 5.8
Requires PHP: 7.4
Author: Sujoy Sen
Author URI: https://sujoysenmyself.github.io/myresume/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: hfinsert
*/

// Add menu page
add_action('admin_menu', 'hfinsert_add_admin_menu');
function hfinsert_add_admin_menu() {
    add_menu_page(
        'Header and Footer', 
        'Header and Footer', 
        'manage_options', 
        'hfinsert', 
        'hfinsert_create_admin_page', 
        'dashicons-editor-code', 
        26
    );
}

// Create admin page
function hfinsert_create_admin_page() {
    ?>
    <div class="wrap">
        <h1>Insert Header and Footer</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('hfinsert_settings_group');
            do_settings_sections('hfinsert');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'hfinsert_register_settings');
function hfinsert_register_settings() {
    register_setting('hfinsert_settings_group', 'hfinsert_header_css');
    register_setting('hfinsert_settings_group', 'hfinsert_header_js');
    register_setting('hfinsert_settings_group', 'hfinsert_footer_js');

    add_settings_section('hfinsert_settings_section', null, null, 'hfinsert');

    add_settings_field(
        'hfinsert_header_css', 
        'CSS in Header', 
        'hfinsert_header_css_callback', 
        'hfinsert', 
        'hfinsert_settings_section'
    );
    add_settings_field(
        'hfinsert_header_js', 
        'JS in Header', 
        'hfinsert_header_js_callback', 
        'hfinsert', 
        'hfinsert_settings_section'
    );
    add_settings_field(
        'hfinsert_footer_js', 
        'JS in Footer', 
        'hfinsert_footer_js_callback', 
        'hfinsert', 
        'hfinsert_settings_section'
    );
}

// Callbacks for input fields
function hfinsert_header_css_callback() {
    $header_css = esc_textarea(get_option('hfinsert_header_css'));
    echo "<textarea name='hfinsert_header_css' rows='5' cols='50' style='width: 100%;'>$header_css</textarea>";
}

function hfinsert_header_js_callback() {
    $header_js = esc_textarea(get_option('hfinsert_header_js'));
    echo "<textarea name='hfinsert_header_js' rows='5' cols='50' style='width: 100%;'>$header_js</textarea>";
}

function hfinsert_footer_js_callback() {
    $footer_js = esc_textarea(get_option('hfinsert_footer_js'));
    echo "<textarea name='hfinsert_footer_js' rows='5' cols='50' style='width: 100%;'>$footer_js</textarea>";
}

// Enqueue custom styles and scripts in the header
function hfinsert_add_custom_css_js_header() {
    $custom_css = get_option('hfinsert_header_css');
    $custom_js = get_option('hfinsert_header_js');
    
    // Add inline CSS
    if (!empty($custom_css)) {
        wp_register_style('hfinsert-custom-style', false);
        wp_enqueue_style('hfinsert-custom-style');
        wp_add_inline_style('hfinsert-custom-style', wp_strip_all_tags($custom_css));
    }
    
    // Add inline JavaScript
    if (!empty($custom_js)) {
        wp_register_script('hfinsert-custom-js-header', false);
        wp_enqueue_script('hfinsert-custom-js-header');
        wp_add_inline_script('hfinsert-custom-js-header', wp_kses_post($custom_js));
    }
}

add_action('wp_head', 'hfinsert_add_custom_css_js_header');

// Enqueue custom scripts in the footer
function hfinsert_add_custom_js_footer() {
    $custom_js_footer = get_option('hfinsert_footer_js');

    if (!empty($custom_js_footer)) {
        wp_register_script('hfinsert-custom-js-footer', false);
        wp_enqueue_script('hfinsert-custom-js-footer');
        wp_add_inline_script('hfinsert-custom-js-footer', wp_kses_post($custom_js_footer));
    }
}

add_action('wp_footer', 'hfinsert_add_custom_js_footer');
