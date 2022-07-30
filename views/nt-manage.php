<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<div>
    <div><p>You can use this plugin to test simple add/edit/delete interactions with Wordpress database.</p></div>
    <div>
        <a href="<?php echo admin_url('admin.php?page=nt_record_create'); ?>" class="button button-primary add-record">Add new record</a>
    </div>
    <div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'nt_records';
        $rows = $wpdb->get_results("SELECT id, title, details FROM $table_name");
        ?>
    </div>
    <?php if (count($rows) > 0) { ?>
        <table class="wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Description</th>
                </tr>
            </thead>
            <?php
            $counter = 1;
            foreach ($rows as $row) {
                $details = unserialize($row->details);
                ?>
                <tbody>
                    <tr class="<?php if ($counter % 2 != 0) { ?>alternate<?php } ?>">
                        <td>
                            <b><?php echo esc_attr($row->title); ?></b>
                        </td>
                        <td>
                            <?php echo esc_attr($details['email']); ?>
                        </td>
                        <td>
                            <?php echo esc_attr($details['description']); ?>
                        </td>
                        <td>
	                        <?php $nt_delete_nonce = wp_create_nonce('nt-remove-record-settings-nonce'); ?>
                            <a href="<?php echo admin_url('admin.php?page=nt_record_edit&id=' . $row->id); ?>">Edit | </a>
                            <a href="<?php echo admin_url() . 'admin-post.php?action=nt_record_delete_options&_wpnonce=' . $nt_delete_nonce . '&id=' . $row->id ?>" onclick="return confirm('Delete current record?')">Delete</a>
                        </td>
                    </tr>
                </tbody>
                <?php
                $counter++;
            }
        } else {
            ?>
            <div><p><strong>No records found</strong></p></div>
<?php } ?>
    </table>
</div>