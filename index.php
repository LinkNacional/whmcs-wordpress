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
if (!class_exists('login_whmcs_shortcode')) {
    /**
     * @author João Victor
     */
    class Wp_login_screen_whmcs {
        public function __construct() {
            add_action('wp_enqueue_scripts', [$this, 'func_load_vuescripts']);
            add_shortcode('whmcslogin', [$this, 'login_whmcs_shortcode']);
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

            return file_get_html(plugin_dir_url(__FILE__) . 'dist/spa/index.html');
            // return var_dump($this->list_files_js());
        }

        public function func_load_vuescripts() {
            wp_register_script('wpvue_vuejs1', plugin_dir_url(__FILE__) . 'dist/spa/js/2.dcb47d05.js', true);
            wp_register_script('wpvue_vuejs2', plugin_dir_url(__FILE__) . 'dist/spa/js/3.757d20cb.js', true);
            wp_register_script('wpvue_vuejs3', plugin_dir_url(__FILE__) . 'dist/spa/js/4.50b461e6.js', true);
            wp_register_script('wpvue_vuejs4', plugin_dir_url(__FILE__) . 'dist/spa/js/app.5f8bb9fd.js', true);
            wp_register_script('wpvue_vuejs5', plugin_dir_url(__FILE__) . 'dist/spa/js/vendor.2733207c.js', true);

            // $this->list_files_js();
            $this->list_files_css();
            // wp_enqueue_style('wpvue_vuecss1', plugin_dir_url(__FILE__) . 'dist/spa/css/app.0e433876.css', true);
            // wp_enqueue_style('wpvue_vuecss2', plugin_dir_url(__FILE__) . 'dist/spa/css/vendor.e810699e.css', true);
        }

        public function list_files_css() {
            $path = './wp-content/plugins/loginWHMCS/dist/spa/css';
            $diretorio = dir($path);
            $names = [];
            $cont = 1;
            while ($arquivo = $diretorio->read()) {
                $names[] = $arquivo;
                wp_enqueue_style('wpvue_vuecss' . $cont, plugin_dir_url(__FILE__) . 'dist/spa/css/' . $arquivo, true);
                $cont++;
            }
            $diretorio->close();
            return $names;
        }

        public function list_files_js() {
            $path = './wp-content/plugins/loginWHMCS/dist/spa/js';
            $diretorio = dir($path);
            $names = [];
            $cont = 1;
            while ($arquivo = $diretorio->read()) {
                $names[] = $arquivo;
                wp_register_script('wpvue_vuejs' . $cont, plugin_dir_url(__FILE__) . 'dist/spa/js/' . $arquivo, true);
                $cont++;
            }
            $diretorio->close();
            return $names;
        }
    }
    $matinalInit = new Wp_login_screen_whmcs();
}
