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
            add_shortcode('whmcslogin', [$this, 'login_whmcs_shortcode']);
            add_action('wp_enqueue_scripts', [$this, 'func_load_vuescripts']);
        }

        /// CRIAR O SHORTCODE
        public function login_whmcs_shortcode($atts = [], $content = null, $tag = '')
        {
            define('ROOT_PATH', dirname(__FILE__));

            $a = shortcode_atts([
                'title' => '',
                'hidefds' => '',
                'class' => '',
            ], $atts);

            wp_enqueue_script('wpvue_vuejs');
            wp_enqueue_script('wpvue_vuejs1');

            return file_get_html(plugin_dir_url(__FILE__).'dist/spa/index.html');
        }

        public function func_load_vuescripts()
        {
            wp_register_script('wpvue_vuejs', plugin_dir_url(__FILE__).'dist/spa/js/app.c54610b5.js', true);
            wp_register_script('wpvue_vuejs1', plugin_dir_url(__FILE__).'dist/spa/js/vendor.e947b7ef.js', true);
        }
    }
    $matinalInit = new Wp_login_screen_whmcs();
}
