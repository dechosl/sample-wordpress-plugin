<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once(NT_ROOT_DIR . 'models/nt-record.php');

class NT_APP {

    public function __construct() {
        add_action('init', array($this, 'nt_start_session_init'));
        register_activation_hook(NT_FCPATH, array($this, 'nt_plugin_activation'));
        add_action('admin_menu', array($this, 'nt_add_plugin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'nt_register_admin_assets'));

        add_action('admin_post_nt_save_options', array($this, 'nt_save_options'));
        add_action('admin_post_nt_new_record_options', array($this, 'nt_new_record_options'));
        add_action('admin_post_nt_current_record_options', array($this, 'nt_current_record_options'));
        add_action('admin_post_nt_record_delete_options', array($this, 'nt_record_delete_options'));

        add_action('template_redirect', array($this, 'nt_submit_form_save'));
    }

    /**
     * Stars session if not already started
     */
    public function nt_start_session_init() {
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * On Plugin activation
     */
    public function nt_plugin_activation() {
        // Check if its multisite
        if (is_multisite()) {
            global $wpdb;

            // Get all blogs in the network and activate plugin on each one
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_ids as $blog_id) {
                switch_to_blog($blog_id);
                $charset_collate = $wpdb->get_charset_collate();
                $table_name = $wpdb->prefix . 'nt_records';
                $sql = "CREATE TABLE $table_name (
	                        id int NOT NULL AUTO_INCREMENT,
	                        title varchar(50),
	                        details varchar(1000),
	                        PRIMARY KEY id (id)
                      ) $charset_collate;";
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                dbDelta($sql);
            }
        } else {
            global $wpdb;
            $options = get_option(NT_SETTINGS);
            $charset_collate = $wpdb->get_charset_collate();
            $table_name = $wpdb->prefix . 'nt_records';
            $sql = "CREATE TABLE $table_name (
                        id int NOT NULL AUTO_INCREMENT,
                        title varchar(50),
                        details varchar(1000),
                        PRIMARY KEY id (id)
                  ) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);
        }

        // Set default option value
        $nt_settings = array();
        $nt_settings['nt-setting-1'] = (isset($options['nt-setting-1'])) ? esc_attr($options['nt-setting-1']) : 'Test value 1';
        $nt_settings['nt-setting-2'] = (isset($options['nt-setting-2'])) ? esc_attr($options['nt-setting-2']) : 'Test value 2';
        update_option(NT_SETTINGS, $nt_settings);
    }

    /**
     * On new plugin menu
     */
    public function nt_add_plugin_menu() {
        add_menu_page('Basic DB', 'Basic DB', 'manage_options', 'nt-admin', array($this, 'nt_main_page'), 'dashicons-album');
        add_submenu_page('nt-admin', 'All Records', 'All Records', 'manage_options', 'nt-admin', array($this, 'nt_main_page'));
        add_submenu_page('nt-admin', 'Add Record', 'Add Record', 'manage_options', 'nt_record_create', array($this, 'nt_record_create'));
        add_submenu_page('null', 'Edit Record', 'Edit Record', 'manage_options', 'nt_record_edit', array($this, 'nt_record_edit'));
        add_submenu_page('nt-admin', 'About', 'About', 'manage_options', 'nt_about', array($this, 'nt_about'));
    }

    /**
     * Add main page
     */
    public function nt_main_page() {
        include(NT_ROOT_DIR . 'views/nt-main.php');
    }

    /**
     * Add new record
     */
    public function nt_record_create() {
        include(NT_ROOT_DIR . 'views/nt-add-record.php');
    }

    /**
     * Edit record
     */
    public function nt_record_edit() {
        include(NT_ROOT_DIR . 'views/nt-edit-record.php' );
    }

    /**
     * About page
     */
    public function nt_about() {
        include(NT_ROOT_DIR . 'views/nt-about.php');
    }

    /**
     * Register all plugin assets
     */
    public function nt_register_admin_assets() {
        wp_enqueue_script('nt-admin-js', NT_JS_DIR . 'nt-main.js', array('jquery'), NT_VERSION);
        wp_enqueue_style('nt-main', NT_CSS_DIR . 'nt-main.css', '', NT_VERSION);
    }

    /**
     * Save into options table
     */
    public function nt_save_options() {
        if ((isset($_POST['nt_add_nonce_save_settings']) && isset($_POST['nt_save_settings']) && wp_verify_nonce($_POST['nt_add_nonce_save_settings'], 'nt_nonce_save_settings')) || (isset($_POST['nt_add_record_form_nonce_save_settings']) && isset($_POST['nt_form_save_settings']) && wp_verify_nonce($_POST['nt_add_record_form_nonce_save_settings'], 'nt_record_form_nonce_save_settings'))) {

            $nt_settings = array();

            // Updates settings
            $nt_settings['nt-setting-1'] = sanitize_text_field($_POST['nt-setting-1']);
            $nt_settings['nt-setting-2'] = sanitize_text_field($_POST['nt-setting-2']);

            update_option(NT_SETTINGS, $nt_settings);
            $_SESSION['nt_message'] = 'Settings Saved Successfully.';
            wp_redirect(admin_url() . 'admin.php?page=nt-admin');

        } else {
            die('No script kiddies please!');
        }
    }

    /**
     * Add new record details into database
     */
    public function nt_new_record_options() {
        if (isset($_POST['nt_add_nonce_new_record']) && isset($_POST['nt_save_new_record']) && wp_verify_nonce($_POST['nt_add_nonce_new_record'], 'nt_nonce_new_record')) {

        	// Sanitize input
            $title       = sanitize_text_field($_POST['title']);
            $email       = sanitize_text_field(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
            $description = sanitize_text_field($_POST['description']);

            $details = [];
            $details['email']       = $email;
            $details['description'] = $description;

            // Insert new record
            $insert = Record::add($title, $details);
            if ($insert) {
                $msg_txt = 'Record Added Successfully.';
            } else {
                $msg_txt = 'Record Could Not Be Added. Please Try Again.';
            }

	        $_SESSION['nt_message'] = $msg_txt;
	        wp_redirect( admin_url() . 'admin.php?page=nt-admin' );

        } else {
            die('No script kiddies please!');
        }
    }

    /**
     * Update record
     */
    public function nt_current_record_options() {
        if (isset($_POST['nt_add_nonce_current_record']) && isset($_POST['nt_save_current_record']) && wp_verify_nonce($_POST['nt_add_nonce_current_record'], 'nt_nonce_current_record')) {

        	// Sanitize input
            $current_record_id = (int)($_POST['current_record_id']);
            $title             = sanitize_text_field($_POST['title']);
            $email             = sanitize_text_field($_POST['email']);
            $description       = sanitize_text_field($_POST['description']);

            $details = [];
            $details['email']       = $email;
            $details['description'] = $description;

            // Update record
            $update = Record::update($current_record_id, $title, $details);
            if ($update) {
                $msg_txt = 'Record Updated Successfully!';
            }
            else {
                $msg_txt = 'No changes Made.';
            }

            $_SESSION['nt_message'] = $msg_txt;
            wp_redirect( admin_url() . 'admin.php?page=nt-admin' );

        } else {
            die('No script kiddies please!');
        }
    }

    /**
     * Delete record
     */
    public function nt_record_delete_options() {
        $nt_delete_nonce = $_REQUEST['_wpnonce'];
        if (!empty($_GET['id']) && is_numeric($_GET['id']) && wp_verify_nonce($nt_delete_nonce, 'nt-remove-record-settings-nonce')) {

        	// Delete record
	        $id = (int)$_GET['id'];
            if (Record::delete($id)) {
	            $msg_txt = 'Record Deleted.';
            } else {
	            $msg_txt = 'Cannot delete record.';
            }
	        $_SESSION['nt_message'] = $msg_txt;
	        wp_redirect(admin_url() . 'admin.php?page=nt-admin');
            exit;

        } else {
            die('No script kiddies please!');
        }
    }
}