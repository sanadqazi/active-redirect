<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (isset($_POST['submit'])) {
    // Handle form submission for adding redirect
    $old_url = esc_url_raw($_POST['old_url']);
    $new_url = esc_url_raw($_POST['new_url']);

    if (!empty($old_url) && !empty($new_url)) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'active_redirection';

        $wpdb->insert(
            $table_name,
            array(
                'old_url'        => $old_url,
                'new_url'        => $new_url,
                'active'         => 1,
                'redirect_count' => 0,
            )
        );
        echo '<div class="updated"><p>Redirect added successfully!</p></div>';
    } else {
        echo '<div class="error"><p>Please enter both Old URL and New URL.</p></div>';
    }
}
?>

<div class="wrap">
    <h2>Add Redirect</h2>
    <form method="post" action="">
        <label for="old_url">Old URL:</label>
        <input type="text" name="old_url" id="old_url" required>

        <label for="new_url">New URL:</label>
        <input type="text" name="new_url" id="new_url" required>

        <input type="submit" name="submit" class="button button-primary" value="Add Redirect">
    </form>
</div>