<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

delete_option('whmcs_login_url');
delete_option('whmcs_login_identifier');
delete_option('whmcs_login_secret');
delete_option('whmcs_login_register_user');
delete_option('whmcs_login_password_reset');

remove_menu_page('Login_whmcs');
remove_shortcode('whmcslogin');
?>