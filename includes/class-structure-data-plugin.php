<?php
/**
 * Plugin Name:     Hexome Fixer
 * Plugin URI:      https://hexome.cloud/wordpress-fixer
 * Description:     Hexome Fixer
 * Author:          Villalba Juan Manuel Pedro
 * Author URI:      https://hexome.cloud
 * Text Domain:     wordpress-fixer
 * Version:         0.0.6
 *
 * @package         Hexome_Fixer
 */

if (!defined('ABSPATH')) exit;


class Structure_Data_Plugin {

    public function __construct() {
        add_filter('wpseo_json_ld_output', [$this, 'modify_structured_data']);
    }

    public function alr_structured_data($data) {
        if (isset($data['@type']) && $data['@type'] === 'JobPosting') {
            if (!isset($data['applicantLocationRequirements'])) {
                $data['applicantLocationRequirements'] = [
                    '@type' => 'Country',
                    'name' => 'US' 
                ];
            }
        }
        return $data;
    }
}
