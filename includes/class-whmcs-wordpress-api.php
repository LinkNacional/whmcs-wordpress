<?php

/**
 * @link       https://www.linknacional.com.br/
 * @since      1.0.0
 *
 * @package    Whmcs_Wordpress
 * @subpackage Whmcs_Wordpress/includes
 */

/**
 * @since      1.0.0
 * @package    Whmcs_Wordpress
 * @subpackage Whmcs_Wordpress/includes
 * @author     Bruno Ferreira <ferreira.bruno@linknacional.com>
 */
class Whmcs_Wordpress_Api {
    /**
     * @since    1.0.0
     * @access   protected
     * @var      string    $namespace
     */
    private $namespace;

    /**
     * @since    1.0.0
     * @access   protected
     * @var      Whmcs_Wordpress_Loader    $loader
     */
    private $loader;

    /**
     * @since    1.0.0
     * @access   protected
     * @var      Whmcs_Wordpress_Whmcs_Services    $whmcsService
     */
    private $whmcsService;

    /**
     * @param  string                         $plugin_name
     * @param  Whmcs_Wordpress_Loader         $loader
     * @param  Whmcs_Wordpress_Whmcs_Services $whmcsService
     */
    public function __construct($plugin_name, Whmcs_Wordpress_Loader $loader, Whmcs_Wordpress_Whmcs_Services $whmcsService) {
        $this->loader = $loader;
        $this->namespace = $plugin_name . '/v1';
        $this->whmcsService = $whmcsService;

        $this->register_routes();
    }

    /**
     * @return void
     */
    public function register_routes() {
        $this->loader->add_endpoint(
            $this->namespace,
            '',
            'GET',
            [$this, 'login']
        );

        $this->loader->add_endpoint(
            $this->namespace,
            '/email/is-registered',
            'GET',
            [$this, 'is_email_registered']
        );

        $this->loader->add_endpoint(
            $this->namespace,
            '/password/reset',
            'GET',
            [$this, 'reset_password']
        );
    }

    /**
     * @return void
     */
    public function login() {
        //
    }

    /**
     * @return void
     */
    public function is_email_registered() {
        //
    }

    /**
     * @return void
     */
    public function reset_password() {
        //
    }
}
