<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>
<?php
    global $wpdb;
    $options = get_option(NT_SETTINGS);
    $table_name = $wpdb->prefix . 'nt_records';
    $record_id  = (int)$_GET['id'];
    $record_detail = $wpdb->get_results("SELECT id, title, details FROM $table_name WHERE id=$record_id");
    foreach ($record_detail as $row) {
        $details = unserialize($row->details);
    }
?>
<div>
    <div class="nt-head">
        <?php include(NT_ROOT_DIR. 'views/nt-header.php');?>
    </div>
    <div>
      <div>
		  <?php
		  if( isset( $_SESSION['nt_message'] ) ) { ?>
              <div class="nt-message">
                  <p><?php echo $_SESSION['nt_message']; unset( $_SESSION['nt_message'] ); ?></p>
              </div>
		  <?php } ?>
          <div>
              <form method="post" action="<?php echo admin_url() . 'admin-post.php' ?>">
                  <input type="hidden" name="action" value="nt_current_record_options" />
                  <input type="hidden" name="current_record_id" value="<?php echo $record_id;?>" />
                  <div>
                      <a href="<?php echo admin_url('admin.php?page=nt-admin'); ?>" class="button button-primary">Back To Main Page</a>
                      <h2>Edit Record</h2>
                  </div>
                  <div class="nt-input-field">
                      <label for="title">Title</label><br />
                      <input type="text" name="title" size="25" required="required" value="<?php echo esc_attr($row->title);?>" />
                  </div>
                  <div class="nt-input-field">
                      <label for="email">Email</label><br />
                      <input type="email" name="email" size="25" value="<?php echo esc_attr($details['email']);?>" />
                  </div>
                  <div class="nt-input-field">
                      <label for="description">Description</label><br />
                      <input type="text" name="description" size="25" value="<?php echo esc_attr($details['description']); ?>"/>
                  </div>

                  <div>
                      <input type="submit" class="button-primary" name="nt_save_current_record" value="Save Your Change" />
					  <?php wp_nonce_field( 'nt_nonce_current_record', 'nt_add_nonce_current_record' ); ?>
                  </div>
              </form>
          </div>
      </div>
    </div>
</div>