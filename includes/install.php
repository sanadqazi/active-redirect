<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Create table on plugin activation
function prefix_create_active_redirection_table()
{
    global $wpdb;
    $table_name      = $wpdb->prefix . 'active_redirection';
    $charset_collate = $wpdb->get_charset_collate();

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
        $sql = "CREATE TABLE `$table_name` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `old_url` VARCHAR(255) NOT NULL,
            `new_url` VARCHAR(255) NOT NULL,
            `active` INT NOT NULL,
            `redirect_count` INT NOT NULL,
            PRIMARY KEY (id)
            ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
