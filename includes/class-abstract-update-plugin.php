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


if (!class_exists('Abstract_Hexome_Red_Update')) {
    abstract class Abstract_Hexome_Red_Update {
        /**
        * Current version of the plugin.
        *
        * @var string
        */
        protected $current_version;

        /**
        * GitHub username of the plugin author.
        *
        * @var string
        */
        protected $github_user;

        /**
        * GitHub repository name of the plugin.
        *
        * @var string
        */
        protected $github_repo;

        /**
        * File path to the plugin main file.
        *
        * @var string
        */
        protected $plugin_file;

        /**
        * Abstract method to register and enqueue a JavaScript file.
        */
        abstract public  function after_install($response, $hook_extra, $result);
        abstract public  function plugin_popup($result, $action, $args);
        abstract public  function check_for_update($transient);
    }
}
