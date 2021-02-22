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

        static public function createJson() {
            try {
                $link = get_option('plugin_whmcs_link');
                $fp = fopen('../url.json', 'w+');
                fwrite($fp, json_encode(['link' => $link]));
                fclose($fp);
            } catch (Exception $e) {
                return $e->getMessage();
            }
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
// function wporg_settings_init() {
//     // register a new setting for "reading" page
//     register_setting('general', 'plugin_whmcs_link');

//     // register a new section in the "reading" page
//     add_settings_section(
//         'whmcs_settings_section',
//         'Login WHMCS', 'wporg_settings_section_callback',
//         'general'
//     );

//     // register a new field in the "wporg_settings_section" section, inside the "reading" page
//     add_settings_field(
//         'whmcs_settings_field',
//         'Link para o login', 'wporg_settings_field_callback',
//         'general',
//         'whmcs_settings_section'
//     );
// }

// /**
//  * register wporg_settings_init to the admin_init action hook
//  */
// add_action('admin_init', 'wporg_settings_init');

// /**
//  * callback functions
//  */

// // section content cb
// function wporg_settings_section_callback() {
//     echo 'Configurações do plugin WHMCS login';
// }

//  function createJson($link) {
//      try {
//          //  $link = get_option('plugin_whmcs_link');
//          $fp = fopen('../../url.json', 'w+');
//          fwrite($fp, json_encode(['link' => $link]));
//          fclose($fp);
//      } catch (Exception $e) {
//          return $e->getMessage();
//      }
//  }

// // field content cb
// function wporg_settings_field_callback() {
//     // get the value of the setting we've registered with register_setting()
//     $setting = get_option('plugin_whmcs_link');
//     createJson($setting);
//     // output the field

function pageConfig() {
    // check user capabilities
    if ( !current_user_can( 'manage_options' ) ) {
        return;
    } ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
        <th scope="row"><label for="default_category">Categoria padrão de post</label></th>
        <input type="text" name="url_whmcs" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">



            <?php
            // output security fields for the registered setting "wporg_options"
            settings_fields( 'whmcs_login_options' );
    // output setting sections and their fields
    // (sections are registered for "wporg", each field is registered to a specific section)
    do_settings_sections( 'Login_whmcs' );
    // output save settings button
    submit_button( __( 'Salvar alterações', 'textdomain' ) ); ?>
        </form>
    </div>
    <?php
}

function wporg_options_page() {
    add_submenu_page(
        'options-general.php',
        'WHMCS login',
        'WHMCS login',
        'manage_options',
        'Login_whmcs',
        'pageConfig'
    );
}
add_action('admin_menu', 'wporg_options_page');