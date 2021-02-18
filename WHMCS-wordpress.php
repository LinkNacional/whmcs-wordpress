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
            add_action('admin_menu', 'wporg_options_page');
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
            // return var_dump($this->namesJs);
        }

        public function func_load_vuescripts() {
            $this->list_files_js();

            $this->list_files_css();
        }

        public function list_files_css() {
            $path = WP_PLUGIN_DIR . '/whmcs-wordpress/dist/spa/css';
            $diretorio = dir($path);
            $cont = 1;
            while ($arquivo = $diretorio->read()) {
                if ($arquivo != '..' && $arquivo != '.') {
                    wp_enqueue_style('wpvue_vuecss' . $cont, plugin_dir_url(__FILE__) . 'dist/spa/css/' . $arquivo, true);
                    $this->namesCss[] = $arquivo;
                    $cont++;
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
                    wp_register_script('wpvue_vuejs' . $cont, plugin_dir_url(__FILE__) . 'dist/spa/js/' . $arquivo, true);
                    $this->namesJs[] = 'wpvue_vuejs' . $cont;
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
add_action('admin_menu', 'wporg_options_page');

function wporg_options_page_html() {
    // check user capabilities
    if ( !current_user_can( 'manage_options' ) ) {
        return;
    } ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'login_whmcs' );
    do_settings_sections( 'log_whmcs' );
    submit_button( __( 'Salvar configurações', 'textdomain' ) ); ?>
        </form>
    </div>
    <?php
}
function wporg_options_page() {
    add_submenu_page(
        'edit.php',
        'Login WHMCS',
        'Login WHMCS',
        'manage_options',
        'WHMCS',
        'wporg_options_page_html'
    );
}

