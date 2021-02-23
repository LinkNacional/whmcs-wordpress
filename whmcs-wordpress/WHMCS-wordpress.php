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
include_once 'plugins/file_get_html/simple_html_dom.php';
include_once 'admin/page-admin.php';

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

            $this->createJson();
            return file_get_html(plugin_dir_url(__FILE__) . 'dist/spa/index.html');
            // return file_get_html(plugin_dir_url(__FILE__) . 'dist/spa/index.html');
        }

        public function func_load_vuescripts() {
            $this->list_files_js();
            $this->list_files_css();
        }

        static public function getConfigs() {
        }

        // static public function createJson() {
        //     try {
        //         $link = get_option('plugin_whmcs_link');
        //         $fp = fopen('../url.json', 'w+');
        //         fwrite($fp, json_encode(['link' => $link]));
        //         fclose($fp);
        //     } catch (Exception $e) {
        //         return $e->getMessage();
        //     }
        // }

        public function list_files_css() {
            $path = WP_PLUGIN_DIR . '/whmcs-wordpress/dist/spa/css';
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
            $path = WP_PLUGIN_DIR . '/whmcs-wordpress/dist/spa/js';
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
    }
    $matinalInit = new Wp_login_screen_whmcs();
}

/**
 * This is our callback function that embeds our phrase in a WP_REST_Response
 */
function prefix_get_endpoint_phrase() {
    // rest_ensure_response() wraps the data we want to return into a WP_REST_Response, and ensures it will be properly returned.
    return rest_ensure_response( 'Hello World, this is the WordPress REST API' );
}

/**
 * This function is where we register our routes for our example endpoint.
 */
function prefix_register_example_routes() {
    // register_rest_route() handles more arguments but we are going to stick to the basics for now.
    register_rest_route( 'hello-world/v1', '/phrase', [
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods' => WP_REST_Server::READABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => 'prefix_get_endpoint_phrase',
    ] );
}

add_action( 'rest_api_init', 'prefix_register_example_routes' );