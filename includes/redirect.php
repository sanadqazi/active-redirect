<?php
// Redirect if old URL exists
add_action('template_redirect', 'prefix_check_redirect');

function prefix_check_redirect()
{
    global $wpdb;
    $table_name   = $wpdb->prefix . 'active_redirection';

    $current_url  = home_url($_SERVER['REQUEST_URI']);
    $redirect_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE old_url = %s", $current_url));

    if ($redirect_row) {
        // Increment redirect count
        $wpdb->update(
            $table_name,
            array('redirect_count' => $redirect_row->redirect_count + 1),
            array('id' => $redirect_row->id)
        );

        // Redirect to new URL
        wp_redirect($redirect_row->new_url, 301);
        exit;
    }
}
