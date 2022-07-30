<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div>
  <div class="nt-head">
        <?php include(NT_ROOT_DIR. 'views/nt-header.php');?>
  </div>
     <?php
    $options = get_option(NT_SETTINGS);
    if( isset( $_SESSION['nt_message'] ) ) { ?>
        <div class="nt-message">
            <p><?php echo $_SESSION['nt_message']; unset( $_SESSION['nt_message'] ); ?></p>
        </div>
    <?php } ?>
    <h2 class="nav-tab-wrapper">
    <a href="#" id="nt-custom-settings" class="nt-tabs-trigger nav-tab nav-tab-active">Records Manager</a>
    <a href="#" id="nt-settings" class="nt-tabs-trigger nav-tab">Settings</a>
    </h2>
  <div>
    <div class="metabox-holder columns-2">
        <div class="postbox">
            <div>
                <div class="nt-form-wraper">
                    <div class="inside">
                        <form action="<?php echo admin_url() . 'admin-post.php' ?>" method="post" class="nt-setting-form" style="display:none;">
                            <input type="hidden" name="action" value="nt_save_options" />
                            <div class='nt-tab-contents' id='tab-nt-settings' style="display:none">
							    <?php include(NT_ROOT_DIR . 'views/nt-settings.php'); ?>
                            </div>
                            <div id="tab-nt-submit">
                                <input type="submit" class="button-primary" name="nt_save_settings" value="Save Settings" />
							    <?php wp_nonce_field( 'nt_nonce_save_settings', 'nt_add_nonce_save_settings' ); ?>
                            </div>
                        </form>

                        <!-- Main page -->
                        <div class="nt-tab-contents nt-active-tab nt-main-page" id='tab-nt-custom-settings'>
						    <?php include(NT_ROOT_DIR . 'views/nt-manage.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
</div>
</div>