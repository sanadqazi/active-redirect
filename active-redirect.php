<?php

/**
 * Plugin Name: Active Redirect
 * Description: A plugin to manage URL redirections.
 * Version: 1.0
 * Author: Sanad Qazi
 * Author URI: https://sanadqazi.com
 */

// Include required files
require_once plugin_dir_path(__FILE__) . 'includes/install.php';
require_once plugin_dir_path(__FILE__) . 'includes/redirect.php';
require_once plugin_dir_path(__FILE__) . 'includes/menu.php';

// Create table on activation
register_activation_hook(__FILE__, 'prefix_create_active_redirection_table');
