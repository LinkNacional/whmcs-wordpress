<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.linknacional.com.br/
 * @since      1.0.0
 *
 * @package    Whmcs_Wordpress
 * @subpackage Whmcs_Wordpress/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Whmcs_Wordpress
 * @subpackage Whmcs_Wordpress/public
 * @author     Bruno Ferreira <ferreira.bruno@linknacional.com>
 */
class Whmcs_Wordpress_Public {
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
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Whmcs_Wordpress_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Whmcs_Wordpress_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/whmcs-wordpress-public.css', [], $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Whmcs_Wordpress_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Whmcs_Wordpress_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/whmcs-wordpress-public.js', ['jquery'], $this->version, false);
    }

    public function shortcode_whmcs_wordpress($atts = [], $content = null) {
        $buildDir = __DIR__ . '/form';

        $cssFiles = scandir("$buildDir/css");
        $jsFiles = scandir("$buildDir/js");
        $buildFiles = array_merge($cssFiles, $jsFiles);

        $files = array_filter($buildFiles, function ($file) {
            return $file !== '.' && $file !== '..';
        });

        $pathsJs = '';
        $pathsCss = '';

        foreach ($files as $file) {
            $posDotBeforeExt = strripos($file, '.');
            $fileExt = substr($file, $posDotBeforeExt + 1);

            switch ($fileExt) {
                case 'js':
                    $path = plugin_dir_url(__FILE__) . "form/js/$file";
                    $pathsJs .= "<script src='$path'></script>";

                    break;
                case 'css':
                    $path = plugin_dir_url(__FILE__) . "form/css/$file";
                    $pathsCss .= "<link rel='stylesheet' href='$path'>";

                    break;
            }
        }

        $apiUrl = get_site_url(null, 'wp-json/' . $this->plugin_name);
        $whmcsRegistrationUrl = get_option('whmcs_wordpress_setting_register_user_url');

        $iframeFilePath = plugin_dir_path(__FILE__) . 'form/iframe.html';
        $iframeFilePathUrl = plugin_dir_url(__FILE__) . 'form/iframe.html';

        if (!file_exists($iframeFilePath)) {
            $iframeCode = <<<EOT
        <!DOCTYPE html>
        <html>
            <head>
                $pathsCss
            </head>
            <body>
                <script type="text/javascript">
                    const whmcs_wordpress_api_url = '$apiUrl'
                    const whmcs_wordpress_registration_url = '$whmcsRegistrationUrl'
                </script>
                <div id=q-app></div>
                $pathsJs
            </body>
        </html>
        EOT;

            file_put_contents($iframeFilePath, $iframeCode);
        }

        return <<<EOT
        <iframe 
        src="$iframeFilePathUrl"
        frameborder="0"
        loading="eager"
        allowfullscreen="false"
        style="width: 500px; min-height: 184px;"
        ></iframe>
        EOT;
    }
}
