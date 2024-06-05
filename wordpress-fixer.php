<?php
/**
 * Plugin Name:     Hexome Fixer
 * Plugin URI:      https://hexome.cloud/wordpress-fixer
 * Description:     Hexome Fixer
 * Author:          Villalba Juan Manuel Pedro
 * Author URI:      https://hexome.cloud
 * Text Domain:     wordpress-fixer
 * Domain Path:     /languages
 * Version:         0.0.17
 *
 * @package         Hexome_Fixer
 */

!define('WPFIXER_CURRENT_VERSION', '0.0.16');
!define('WPFIXER_GITHUB_USER', 'Hexome');
!define('WPFIXER_GITHUB_REPO', 'wordpress-fixer');
!define('WPFIXER_PLUGIN_FILE', plugin_dir_path( __FILE__ ));


function include_abstract_update_plugin_class() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-abstract-update-plugin.php';
}
add_action( 'plugins_loaded', 'include_abstract_update_plugin_class' );

function include_update_plugin_class() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-update-plugin.php';
}
add_action( 'plugins_loaded', 'include_update_plugin_class' );


function include_structure_data_plugin_class() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-structure-data-plugin.php';
    new Structure_Data_Plugin();
}
add_action( 'plugins_loaded', 'include_structure_data_plugin_class' );



function check_for_update_and_execute_action() {
    if (isset($_GET['check_for_update'])) {
        $update_available = true; // Placeholder for demonstration, replace with actual logic to check for update

        if ($update_available) {
            add_action( 'admin_notices', 'display_update_notice' );

            new Hexome_Fixer_Updater(WPFIXER_CURRENT_VERSION, WPFIXER_GITHUB_USER, WPFIXER_GITHUB_REPO, WPFIXER_PLUGIN_FILE);
        }

    }
}


add_action('init', 'check_for_update_and_execute_action');
function display_update_notice() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e( 'An update is available for your plugin. Please update now!', 'text-domain' ); ?></p>
    </div>
    <?php
}


function load_custom_script_on_front_end() {
    if (!is_admin()) {
        wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) .  '/assets/js/script.js', array('jquery'), '0.0.6', true);
    }
}

add_action('init', 'load_custom_script_on_front_end');


function print_style_inline() {
    $css_path = dirname(__FILE__) .  '/assets/css/style.css';
    if (file_exists($css_path)) {
            $css_content = file_get_contents($css_path);
            echo '<style>' . $css_content . '</style>';
    }
}
add_action('wp_head', 'print_style_inline');
