<?php
/*
Plugin Name: Header and Footer Insertion
Plugin URI: https://wordpress.org/plugins/header-and-footer-insertion/
Description: A plugin to add custom styles and scripts to the header or footer.
Version: 1.0.0
Requires at least: 5.8
Requires PHP: 7.4
Author: Sujoy Sen
Author URI: https://sujoysenmyself.github.io/myresume/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: HFI
*/

// Add menu page
add_action('admin_menu', 'HFI_add_admin_menu');
function HFI_add_admin_menu() {
    add_menu_page('Header and Footer', 'Header and Footer', 'manage_options', 'HFI', 'HFI_create_admin_page', 'dashicons-editor-code', 26);
}

// Create admin page
function HFI_create_admin_page() {
    ?>
    <div class="wrap">
        <h1>Insert Header and Footer</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('HFI_settings_group');
            do_settings_sections('HFI');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'HFI_register_settings');
function HFI_register_settings() {
    register_setting('HFI_settings_group', 'HFI_header_css');
    register_setting('HFI_settings_group', 'HFI_header_js');
    register_setting('HFI_settings_group', 'HFI_footer_js');

    add_settings_section('HFI_settings_section', null, null, 'HFI');

    add_settings_field('HFI_header_css', 'CSS in Header', 'HFI_header_css_callback', 'HFI', 'HFI_settings_section');
    add_settings_field('HFI_header_js', 'JS in Header', 'HFI_header_js_callback', 'HFI', 'HFI_settings_section');
    add_settings_field('HFI_footer_js', 'JS in Footer', 'HFI_footer_js_callback', 'HFI', 'HFI_settings_section');
}

// Callbacks for input fields
function HFI_header_css_callback() {
    $header_css = esc_textarea(get_option('HFI_header_css'));
    echo "<textarea name='HFI_header_css' rows='5' cols='50' style='width: 100%;'>$header_css</textarea>";
}

function HFI_header_js_callback() {
    $header_js = esc_textarea(get_option('HFI_header_js'));
    echo "<textarea name='HFI_header_js' rows='5' cols='50' style='width: 100%;'>$header_js</textarea>";
}

function HFI_footer_js_callback() {
    $footer_js = esc_textarea(get_option('HFI_footer_js'));
    echo "<textarea name='HFI_footer_js' rows='5' cols='50' style='width: 100%;'>$footer_js</textarea>";
}

// Enqueue custom styles and scripts
add_action('wp_head', 'HFI_add_custom_css_js_header');
add_action('wp_footer', 'HFI_add_custom_js_footer');

function HFI_add_custom_css_js_header() {
    if ($custom_css = get_option('HFI_header_css')) {
        echo "<style>$custom_css</style>";
    }
    if ($custom_js = get_option('HFI_header_js')) {
        echo "<script>$custom_js</script>";
    }
}

function HFI_add_custom_js_footer() {
    if ($custom_js = get_option('HFI_footer_js')) {
        echo "<script>$custom_js</script>";
    }
}
