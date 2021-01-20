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

// Constants
define('LKN_DPLLMP_VERSION', '1.0.0');
define('LKN_DPLLMP_OPTIONS_VERSION', '1');
define('LKN_DPLLMP_SUPPORT_FORUM', 'https://www.linknacional.com.br/suporte');
define('LKN_DPLLMP_WP_VERSION', '4.0');
define('LKN_DPLLMP_WC_VERSION', '3.0');

if (!class_exists('LKN_Divi_Wp_Loop_Matinal_Player')) {
    /**
     * @author João Victor
     */
    class Wp_login_screen_whmcs
    {
        public function __construct()
        {
            add_shortcode('whmcslogin', [$this, 'login_whmcs_shortcode']);
        }

        /// CRIAR O SHORTCODE DO
        public function login_whmcs_shortcode($atts = [], $content = null, $tag = '')
        {
            $a = shortcode_atts([
                'title' => '',
                'hidefds' => '',
                'class' => '',
            ], $atts);

            return '<div> teste123 </div>';
        }
    }
    $matinalInit = new Wp_login_screen_whmcs();
}
