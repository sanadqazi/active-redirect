<?php
// Add backend menu
add_action('admin_menu', 'prefix_active_redirect_menu');

function prefix_active_redirect_menu()
{
    add_menu_page(
        'Active Redirect',
        'Active Redirect',
        'manage_options',
        'active_redirect_menu',
        'prefix_active_redirect_page'
    );
}

// Display data on the main menu page with pagination
function prefix_active_redirect_page()
{
    global $wpdb;
    $table_name     = $wpdb->prefix . 'active_redirection';

    // Number of items per page
    $items_per_page = 10;

    // Current page number
    $current_page   = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;

    // Offset calculation for the query
    $offset         = ($current_page - 1) * $items_per_page;

    $redirects      = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name LIMIT %d OFFSET %d", $items_per_page, $offset), ARRAY_A);

    // Store the HTML for the form in a variable
    $edit_form_html = '';

    // Display the table
    ob_start();
?>
    <div class="wrap">
        <?php require_once plugin_dir_path(__FILE__) . 'add-redirect.php'; ?>
        <h2>Active Redirect</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Old URL</th>
                    <th>New URL</th>
                    <th>Active</th>
                    <th>Redirect Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($redirects as $redirect) : ?>
                    <tr>
                        <td><?php echo esc_html($redirect['id']); ?></td>
                        <td><?php echo esc_html($redirect['old_url']); ?></td>
                        <td><?php echo esc_html($redirect['new_url']); ?></td>
                        <td><?php echo esc_html($redirect['active']); ?></td>
                        <td><?php echo esc_html($redirect['redirect_count']); ?></td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=active_redirect_menu&action=edit&id=' . $redirect['id']); ?>" title="Edit"><span class="dashicons dashicons-edit"></span></a>
                            <a href="<?php echo admin_url('admin.php?page=active_redirect_menu&action=delete&id=' . $redirect['id']); ?>" title="Delete" onclick="return alert('Are you sure?');"><span class="dashicons dashicons-trash"></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        // Output pagination
        $total_redirects = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
        $total_pages     = ceil($total_redirects / $items_per_page);

        echo '<div class="tablenav">';
        echo '<div class="tablenav-pages">';
        echo paginate_links(array(
            'base'      => add_query_arg('paged', '%#%'),
            'format'    => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total'     => $total_pages,
            'current'   => $current_page,
        ));
        echo '</div>';
        echo '</div>';
        ?>
    </div>
    <?php
    $table_html = ob_get_clean();

    // Delete the redirection
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        include_once plugin_dir_path(__FILE__) . 'delete-redirect.php';
        delete_redirection(absint($_GET['id']));
    }

    // Display the edit form if the edit action is requested
    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
        ob_start();
        prefix_edit_redirect_form(absint($_GET['id']));
        $edit_form_html = ob_get_clean();
    }

    // Output the HTML
    echo $table_html . $edit_form_html;
}

// Edit redirect form
function prefix_edit_redirect_form($redirect_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'active_redirection';
    $redirect   = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $redirect_id), ARRAY_A);

    if (isset($_POST['submit'])) {
        // Handle form submission for editing redirect
        $old_url = esc_url_raw($_POST['old_url']);
        $new_url = esc_url_raw($_POST['new_url']);

        if (!empty($old_url) && !empty($new_url)) {
            $wpdb->update(
                $table_name,
                array(
                    'old_url' => $old_url,
                    'new_url' => $new_url,
                ),
                array('id' => $redirect_id)
            );
            echo '<div class="updated"><p>Redirect updated successfully!</p></div>';
        } else {
            echo '<div class="error"><p>Please enter both Old URL and New URL.</p></div>';
        }
    }
    ?>
    <h2>Edit Redirect</h2>
    <form method="post" action="">
        <label for="old_url">Old URL:</label>
        <input type="text" name="old_url" id="old_url" value="<?php echo esc_attr($redirect['old_url']); ?>" required>

        <label for="new_url">New URL:</label>
        <input type="text" name="new_url" id="new_url" value="<?php echo esc_attr($redirect['new_url']); ?>" required>

        <input type="submit" name="submit" class="button button-primary" value="Update Redirect">
    </form>
<?php
}
