<?php
/**
 * Plugin Name:     Hexome Fixer
 * Plugin URI:      https://hexome.cloud/wordpress-fixer
 * Description:     Hexome Fixer
 * Author:          Villalba Juan Manuel Pedro
 * Author URI:      https://hexome.cloud
 * Text Domain:     wordpress-fixer
 * Version:         0.0.1
 *
 * @package         Hexome_Fixer
 */

if (!class_exists('Hexome_Fixer_Updater')) {
    class Hexome_Fixer_Updater extends Abstract_Hexome_Red_Update {
        
        protected $current_version;
        protected $github_user;
        protected $github_repo;
        protected $plugin_file;
        
        /**
        * Constructor method to initialize properties.
        *
        * @param string $current_version Current version of the plugin.
        * @param string $github_user GitHub username of the plugin author.
        * @param string $github_repo GitHub repository name of the plugin.
        * @param string $plugin_file File path to the plugin main file.
        */
        public function __construct($current_version, $github_user, $github_repo, $plugin_file) {
            $this->current_version = $current_version;
            $this->github_user = $github_user;
            $this->github_repo = $github_repo;
            $this->plugin_file = $plugin_file;
            
            add_filter('pre_set_site_transient_update_plugins', array($this, 'check_for_update'));
            add_filter('plugins_api', array($this, 'plugin_popup'), 10, 3);
            add_filter('upgrader_post_install', array($this, 'after_install'), 10, 3);
        }
        
        public function check_for_update($transient) {
            if (empty($transient->checked)) {
                return $transient;
            }
            
            $remote_version = $this->get_remote_version();
            
            if (version_compare($this->current_version, $remote_version, '<')) {
                $obj = new stdClass();
                $obj->slug = plugin_basename($this->plugin_file);
                $obj->new_version = $remote_version;
                $obj->url = $this->get_repository_info()->html_url;
                $obj->package = $this->get_remote_zip_url();
                
                $transient->response[$obj->slug] = $obj;
            }
            
            return $transient;
        }
        
        public function plugin_popup($result, $action, $args) {
            if (!empty($args->slug) && $args->slug == plugin_basename($this->plugin_file)) {
                $repo = $this->get_repository_info();
                
                $result = new stdClass();
                $result->name = $repo->name;
                $result->slug = plugin_basename($this->plugin_file);
                $result->version = $this->get_remote_version();
                $result->author = $repo->owner->login;
                $result->homepage = $repo->html_url;
                $result->download_link = $this->get_remote_zip_url();
                $result->sections = array(
                    'description' => $repo->description
                );
            }
            
            return $result;
        }
        
        public function after_install($response, $hook_extra, $result) {
            global $wp_filesystem;
            $install_directory = plugin_dir_path($this->plugin_file);
            $wp_filesystem->move($result['destination'], $install_directory);
            $result['destination'] = $install_directory;
            return $result;
        }
        
        private function get_remote_version() {
            $version = '0.0.0';
            $remote_version_url = 'https://raw.githubusercontent.com/' . $this->github_user . '/' . $this->github_repo . '/main/version.txt';
            $response = wp_remote_get($remote_version_url);
            
            if (!is_wp_error($response) && isset($response['body'])) {
                $version = trim($response['body']);
            }
            
            return $version;
        }
        
        private function get_repository_info() {
            $repo_info_url = 'https://api.github.com/repos/' . $this->github_user . '/' . $this->github_repo;
            $response = wp_remote_get($repo_info_url);
            
            if (is_wp_error($response)) {
                return false;
            }
            
            return json_decode($response['body']);
        }
        
        private function get_remote_zip_url() {
            return 'https://github.com/' . $this->github_user . '/' . $this->github_repo . '/zipball/main/';
        }
    }
}




