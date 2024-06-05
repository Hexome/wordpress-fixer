<?php
/**
 * Plugin Name:     Hexome Fixer
 * Plugin URI:      https://hexome.cloud/wordpress-fixer
 * Description:     Hexome Fixer
 * Author:          Villalba Juan Manuel Pedro
 * Author URI:      https://hexome.cloud
 * Text Domain:     wordpress-fixer
 * Version:         0.0.11
 *
 * @package         Hexome_Fixer
 */

if (!defined('ABSPATH')) exit;


class Structure_Data_Plugin {

    public function __construct() {
        if (class_exists('WPSEO_Frontend')) {
            add_filter('wpseo_json_ld_output', [$this, 'alr_structured_data']);
        }

        if (defined('RANK_MATH_VERSION')) {
            add_filter('rank_math/json_ld', [$this, 'alr_structured_data'], 10, 2);
        }

        if (defined('JOB_MANAGER_VERSION')) {
            add_action('wp_footer', [$this, 'alr_wp_job_manager_json_ld'], 100);
            add_filter('wpjm_get_job_listing_structured_data', [$this, 'alr_wpjm_structured_data'], 10, 2); 
        }
    }

    public function alr_structured_data($data) {
        if (isset($data['@type']) && $data['@type'] === 'JobPosting') {
            $data = $this->add_missing_fields($data);
        }
        return $data;
    }

    public function alr_structured_data_rankmath($data, $jsonld) {
        if (isset($data['@type']) && $data['@type'] === 'JobPosting') {
            $data = $this->add_missing_fields($data);
        }
        return $data;
    }

    public function alr_wp_job_manager_json_ld() {
        ob_start([$this, 'alr_json_ld_output']);
    }

    public function alr_json_ld_output($buffer) {
        $pattern = '/<script type="application\/ld\+json">(.*?)<\/script>/s';
        $buffer = preg_replace_callback($pattern, function($matches) {
            $json = json_decode($matches[1], true);
            if (isset($json['@type']) && $json['@type'] === 'JobPosting') {
                $json = $this->add_missing_fields($json);
            }
            return '<script type="application/ld+json">' . json_encode($json) . '</script>';
        }, $buffer);
        return $buffer;
    }

    public function alr_wpjm_structured_data($data, $post) {
        if (isset($data['@type']) && $data['@type'] === 'JobPosting') {
            $data = $this->add_missing_fields($data);
        }
        return $data;
    }

    private function add_missing_fields($data) {
        if (!isset($data['applicantLocationRequirements'])) {
            $data['applicantLocationRequirements'] = [
                '@type' => 'Country',
                'name' => 'US' 
            ];
        }

        if (!isset($data['hiringOrganization']['name']) || empty($data['hiringOrganization']['name'])) {
            $data['hiringOrganization']['name'] = 'Private Business'; 
        }

        return $data;
    }
}
