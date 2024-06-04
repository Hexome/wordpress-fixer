<?php
/**
 * Plugin Name:     Hexome Fixer
 * Plugin URI:      https://hexome.cloud/wordpress-fixer
 * Description:     Hexome Fixer
 * Author:          Villalba Juan Manuel Pedro
 * Author URI:      https://hexome.cloud
 * Text Domain:     wordpress-fixer
 * Version:         0.0.7
 *
 * @package         Hexome_Fixer
 */

if (!defined('ABSPATH')) exit;


class Structure_Data_Plugin {

     public function __construct() {
        if (class_exists('WPSEO_Frontend')) {
            add_filter('wpseo_json_ld_output', [$this, 'alr_structured_data_yoast']);
        }

        if (defined('RANK_MATH_VERSION')) {
            add_filter('rank_math/json_ld', [$this, 'alr_structured_data_rankmath'], 10, 2);
        }
    }

    public function alr_structured_data_yoast($data) {
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

    public function alr_structured_data_rankmath($data, $jsonld) {
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
