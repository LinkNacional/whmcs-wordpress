<?php
/*
* Plugin Name:WHMCS login
* Author URI: https://www.linknacional.com.br/
* Author: Link Nacional
* Description: Insert the WHMCS login in WordPress by shortcode
* Version: 1.1.0
* License: Apache License 2.0
* License URI: https://www.apache.org/licenses/LICENSE-2.0
*/

//Check for direct access
defined('ABSPATH') or exit('Please Keep Silence');
include_once 'admin/page_admin.php';
include_once 'api/whmcs_api.php';
include_once 'languages/translate.php';

if (!class_exists('login_whmcs_shortcode')) {
    /**
     * @author João Victor
     */
    class Wp_login_screen_whmcs {
        public $namesCss = [];
        public $namesJs = [];

        public function __construct() {
            add_action('wp_enqueue_scripts', [$this, 'func_load_vuescripts']);
            add_shortcode('whmcslogin', [$this, 'login_whmcs_shortcode']);
            add_filter( 'plugin_action_links_whmcs-wordpress/whmcs-wordpress.php', [$this, 'plugin_links'] );
            add_action('init', [$this, 'status_language_init']);
        }

        /// CRIAR O SHORTCODE
        public function login_whmcs_shortcode($atts = [], $content = null, $tag = '') {
            wp_enqueue_script('wpvue_vuejs1');
            wp_enqueue_script('wpvue_vuejs2');
            wp_enqueue_script('wpvue_vuejs3');
            wp_enqueue_script('wpvue_vuejs4');
            wp_enqueue_script('wpvue_vuejs5');

            wp_enqueue_style('wpvue_vuecss1');
            wp_enqueue_style('wpvue_vuecss2');
            $list_translate = get_texts();

            $return = '<!--variaveis para o plugin WHMCS login-->' .
            "<script type='text/javascript'>" .
            "var login_whmcs_url = '" . get_site_url() . 
            "'; var login_whmcs_content = '" . $content . 
            "'; var login_whmcs_size = '" . $atts['size'] . "';";

            foreach ($list_translate as $key => $value) {
                $return .= ' var ' . $key . "='" . $value . "';";
            }
            $return .= '</script>' . "<div id='q-app'></div>";
            return $return;
        }

        public function func_load_vuescripts() {
            $this->list_files_js();
            $this->list_files_css();
        }

        public function list_files_css() {
            $path = plugin_dir_path( __FILE__ ) . 'dist/spa/css';
            $diretorio = dir($path);
            $cont = 1;
            while ($arquivo = $diretorio->read()) {
                if ($arquivo != '..' && $arquivo != '.') {
                    if (!in_array($arquivo, $this->namesCss)) {
                        wp_enqueue_style('wpvue_vuecss' . $cont, plugin_dir_url(__FILE__) . 'dist/spa/css/' . $arquivo,true);
                        $this->namesCss[] = $arquivo;
                        $cont++;
                    }
                }
            }
            $diretorio->close();
            return true;
        }

        public function list_files_js() {
            $path = plugin_dir_path( __FILE__ ) . 'dist/spa/js';
            $diretorio = dir($path);
            $cont = 1;
            while ($arquivo = $diretorio->read()) {
                if ($arquivo != '..' && $arquivo != '.') {
                    wp_register_script('wpvue_vuejs' . $cont, plugin_dir_url(__FILE__) . 'dist/spa/js/' . $arquivo,true);
                    $this->namesJs[] = $arquivo;
                    $cont++;
                }
            }
            $diretorio->close();
            return true;
        }

        public static function plugin_links( $links ) {
            $links[] = '<a href="' . admin_url( 'options-general.php?page=Login_whmcs' ) . '">' . __('Settings', 'whmcs-wordpress' ) . '</a>';
            return $links;
        }

        public function status_language_init() {
            $path = basename( dirname( __FILE__ ) ) . '/languages';
            load_plugin_textdomain( 'whmcs-wordpress', false, $path);
        }
    }
}
$matinalInit = new Wp_login_screen_whmcs();
