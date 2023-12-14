<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Delete the redirect URL from the database
function delete_redirection($redirect_id)
{
    if (isset($redirect_id)) {

        // Handle form submission for deleting redirect
        global $wpdb;
        $table_name = $wpdb->prefix . 'active_redirection';

        $wpdb->delete($table_name, array('id' => $redirect_id));
        echo '<div class="updated"><p>Redirect deleted successfully!</p></div>';
    }
}
