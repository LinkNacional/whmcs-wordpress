<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.linknacional.com.br/
 * @since      1.0.0
 *
 * @package    Whmcs_Wordpress
 * @subpackage Whmcs_Wordpress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Whmcs_Wordpress
 * @subpackage Whmcs_Wordpress/admin
 * @author     Bruno Ferreira <ferreira.bruno@linknacional.com>
 */
class Whmcs_Wordpress_Admin {
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The options name to be used in this plugin
     *
     * @since  	1.0.0
     * @access 	private
     * @var  	string 		$option_name 	Option name of this plugin
    */
    private $option_name = 'whmcs_wordpress_setting';

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->register_settings();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/whmcs-wordpress-admin.css', [], $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/whmcs-wordpress-admin.js', ['jquery'], $this->version, false);
    }

    public function register_settings() {
        add_action('admin_menu', [$this, 'whmcs_wordpress_add_admin_menu']);
        add_action('admin_init', [$this, 'whmcs_wordpress_settings_init']);
    }

    public function whmcs_wordpress_add_admin_menu() {
        add_submenu_page(
            'options-general.php',
            'WHMCS Login',
            'WHMCS Login',
            'manage_options',
            'whmcs_wordpress',
            [$this, 'whmcs_wordpress_settings_page']
        );
    }

    public function whmcs_wordpress_settings_init() {
        add_settings_section(
            $this->option_name . '_general',
            __('WHMCS API settings', 'whmcs-wordpress'),
            [$this, 'whmcs_wordpress_api_settings_section_callback'],
            $this->plugin_name,
        );

        add_settings_field(
            $this->option_name . '_whmcs_url',
            __('WHMCS url', 'whmcs-wordpress'),
            [$this, 'whmcs_url_setting_callback'],
            $this->plugin_name,
            $this->option_name . '_general',
        );

        add_settings_field(
            $this->option_name . '_whmcs_api_identifier',
            __('WHMCS API identifier', 'whmcs-wordpress'),
            [$this, 'whmcs_login_identifier_setting_callback'],
            $this->plugin_name,
            $this->option_name . '_general',
        );

        add_settings_field(
            $this->option_name . '_whmcs_api_secret',
            __('WHMCS API secret', 'whmcs-wordpress'),
            [$this, 'whmcs_login_secret_setting_callback'],
            $this->plugin_name,
            $this->option_name . '_general',
        );

        add_settings_field(
            $this->option_name . '_register_user_url',
            __('User registration link', 'whmcs-wordpress'),
            [$this, 'whmcs_register_user_url_setting_callback'],
            $this->plugin_name,
            $this->option_name . '_general',
        );

        register_setting($this->plugin_name, $this->option_name . '_whmcs_api_identifier');
        register_setting($this->plugin_name, $this->option_name . '_whmcs_api_secret');
        register_setting($this->plugin_name, $this->option_name . '_whmcs_url');
        register_setting($this->plugin_name, $this->option_name . '_register_user_url');
    }

    public function whmcs_wordpress_settings_page() {
        ?>
            <form action='options.php' method='post'>
                <h2>WHMCS WordPress</h2>
                <?php
        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
        submit_button(); ?>
            </form>
            <?php
    }

    public function whmcs_wordpress_api_settings_section_callback() {
        // echo __('API Credentials', 'whmcs-wordpress');
    }

    public function whmcs_login_identifier_setting_callback() {
        $name = $this->option_name . '_whmcs_api_identifier';
        $value = get_option($name);

        echo <<<EOT
            <input
            class="whmcs-wordpress-input"
            type="password"
            autocomplete="off"
            name="$name"
            value="$value"
            required
            maxlength="255"
            >
        EOT;
    }

    public function whmcs_login_secret_setting_callback() {
        $name = $this->option_name . '_whmcs_api_secret';
        $value = get_option($name);

        echo <<<EOT
            <input
            class="whmcs-wordpress-input"
            type="password"
            autocomplete="off"
            name="$name"
            value="$value"
            required
            maxlength="255"
            >
        EOT;
    }

    public function whmcs_url_setting_callback() {
        $name = $this->option_name . '_whmcs_url';
        $value = get_option($name);

        echo <<<EOT
            <input
            class="whmcs-wordpress-input"
            type="url"
            autocomplete="off"
            name="$name"
            value="$value"
            required
            maxlength="255"
            >
        EOT;
    }

    public function whmcs_register_user_url_setting_callback() {
        $name = $this->option_name . '_register_user_url';
        $value = get_option($name);

        echo <<<EOT
            <input
            class="whmcs-wordpress-input"
            type="url"
            autocomplete="off"
            name="$name"
            value="$value"
            required
            maxlength="255"
            >
        EOT;
    }
}
