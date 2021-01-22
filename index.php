<?php
/*
Plugin Name: Divi WP WHMCS login
Plugin URI: https://www.linknacional.com.br/
Author URI: https://www.linknacional.com.br/
Author: Link Nacional
Description: Inserir o login do WHMCS no WordPress por shortcode
Text-Domain: 'link-nacional'
Version: 1.0.0
*/

//Check for direct access
defined('ABSPATH') or exit('Please Keep Silence');

include_once 'file_get_html/simple_html_dom.php';

// Constants
define('LKN_DPLLMP_VERSION', '1.0.0');
define('LKN_DPLLMP_OPTIONS_VERSION', '1');
define('LKN_DPLLMP_SUPPORT_FORUM', 'https://www.linknacional.com.br/suporte');
define('LKN_DPLLMP_WP_VERSION', '4.0');
define('LKN_DPLLMP_WC_VERSION', '3.0');

if (!class_exists('login_whmcs_shortcode')) {
    /**
     * @author João Victor
     */
    class Wp_login_screen_whmcs
    {
        public function __construct()
        {
            add_action('wp_enqueue_scripts', [$this, 'func_load_vuescripts']);
            add_shortcode('whmcslogin', [$this, 'login_whmcs_shortcode']);
        }

        /// CRIAR O SHORTCODE
        public function login_whmcs_shortcode($atts = [], $content = null, $tag = '')
        {
            wp_enqueue_script('wpvue_vuejs');
            wp_enqueue_script('wpvue_vuejs1');
            wp_enqueue_script('wpvue_vuejs2');
            wp_enqueue_script('wpvue_vuejs3');
            wp_enqueue_script('wpvue_vuejs4');

            wp_enqueue_style('wpvue_vuecss');
            wp_enqueue_style('wpvue_vuecss1');

            return file_get_html(plugin_dir_url(__FILE__).'dist/spa/index.html');
        }

        public function func_load_vuescripts()
        {
            wp_register_script('wpvue_vuejs', plugin_dir_url(__FILE__).'dist/spa/js/app.4bfa1afa.js', true);
            wp_register_script('wpvue_vuejs1', plugin_dir_url(__FILE__).'dist/spa/js/vendor.6e0574b4.js', true);
            wp_register_script('wpvue_vuejs2', plugin_dir_url(__FILE__).'dist/spa/js/2.bf7586da.js', true);
            wp_register_script('wpvue_vuejs3', plugin_dir_url(__FILE__).'dist/spa/js/3.757d20cb.js', true);
            wp_register_script('wpvue_vuejs4', plugin_dir_url(__FILE__).'dist/spa/js/4.306491c8.js', true);

            wp_enqueue_style('wpvue_vuecss', plugin_dir_url(__FILE__).'dist/spa/css/app.0e433876.css', true);
            wp_enqueue_style('wpvue_vuecss1', plugin_dir_url(__FILE__).'dist/spa/css/vendor.e810699e.css', true);
        }
    }
    $matinalInit = new Wp_login_screen_whmcs();
}
