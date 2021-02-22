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
        public $namesCss = [];
        public $namesJs = [];

        public function __construct() {
            add_action('wp_enqueue_scripts', [$this, 'func_load_vuescripts']);
            add_shortcode('whmcslogin', [$this, 'login_whmcs_shortcode']);
        }

        /// CRIAR O SHORTCODE
        public function login_whmcs_shortcode($atts = [], $content = null, $tag = '') {
            // wp_enqueue_script('wpvue_vuejs2');
            // wp_enqueue_script('wpvue_vuejs3');
            // wp_enqueue_script('wpvue_vuejs4');
            // wp_enqueue_script('wpvue_vuejs5');

            // wp_enqueue_style('wpvue_vuecss1');
            // wp_enqueue_style('wpvue_vuecss2');

            $link = get_option('plugin_whmcs_link');

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, plugin_dir_url(__FILE__) . '/dist/spa/index.html');
            // curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-type: application/json', 'User-Agent: whmcs_nfeio']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        }

        public function func_load_vuescripts() {
            // wp_register_script('wpvue_vuejs1', plugin_dir_url(__FILE__) . 'dist/spa/js/1.90255e0e.js',true);
            // wp_register_script('wpvue_vuejs2',plugin_dir_url(__FILE__) . 'dist/spa/js/3.8d1a5784.js',true);
            // wp_register_script('wpvue_vuejs3',plugin_dir_url(__FILE__) . 'dist/spa/js/4.74771008.js',true);
            // wp_register_script('wpvue_vuejs4',plugin_dir_url(__FILE__) . 'dist/spa/js/app.41357a0f.js',true);
            // wp_register_script('wpvue_vuejs5',plugin_dir_url(__FILE__) . 'dist/spa/js/vendor.5f15a21f.js',true);

            // wp_enqueue_style('wpvue_vuecss1',plugin_dir_url(__FILE__) . 'dist/spa/css/app.2b1073c3.css',true);
            // wp_enqueue_style('wpvue_vuecss2', plugin_dir_url(__FILE__) . 'dist/spa/css/vendor.1f0243cb.css',true);

            $this->list_files_js();
            $this->list_files_css();
        }

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

//menu
function wporg_settings_init() {
    // register a new setting for "reading" page
    register_setting('general', 'plugin_whmcs_link');

    // register a new section in the "reading" page
    add_settings_section(
        'whmcs_settings_section',
        'Login WHMCS', 'wporg_settings_section_callback',
        'general'
    );

    // register a new field in the "wporg_settings_section" section, inside the "reading" page
    add_settings_field(
        'whmcs_settings_field',
        'Link para o login', 'wporg_settings_field_callback',
        'general',
        'whmcs_settings_section'
    );
}

/**
 * register wporg_settings_init to the admin_init action hook
 */
add_action('admin_init', 'wporg_settings_init');

/**
 * callback functions
 */

// section content cb
function wporg_settings_section_callback() {
    echo 'Configurações do plugin WHMCS login';
}

// field content cb
function wporg_settings_field_callback() {
    // get the value of the setting we've registered with register_setting()
    $setting = get_option('plugin_whmcs_link');
    // output the field ?>
    <input name="plugin_whmcs_link" type="text" class="regular-text code" value="<?php echo  $setting; ?>">
    <?php
}
