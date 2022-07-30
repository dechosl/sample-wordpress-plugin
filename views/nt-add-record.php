<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div>
    <div class="nt-head">
        <?php include(NT_ROOT_DIR. 'views/nt-header.php'); ?>
    </div>
    <div>
        <form method="post" action="<?php echo admin_url() . 'admin-post.php' ?>">
        <input type="hidden" name="action" value="nt_new_record_options" />
            <div>
                <a href="<?php echo admin_url('admin.php?page=nt-admin'); ?>" class="button button-primary">Back To Main Page</a>
                <h2>Create New Record</h2>
                <p>You can add new records from form below.</p>
            </div>
            <div class="nt-input-field">
                <label for="title">Title</label><br />
                <input type="text" name="title" size="25" required="required" />
            </div>
            <div class="nt-input-field">
                <label for="email">Email</label><br />
                <input type="email" name="email" size="25" />
            </div>
            <div class="nt-input-field">
                <label for="description">Description</label><br />
                <input type="text" name="description" size="25"/>
            </div>
            <div class="nt-input-field">
                <input type="submit" class="button-primary" name="nt_save_new_record" value="Add new record" />
                <?php wp_nonce_field( 'nt_nonce_new_record', 'nt_add_nonce_new_record' ); ?>
            </div>
        </form>
    </div>
</div>