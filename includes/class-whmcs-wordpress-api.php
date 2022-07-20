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
            'login',
            'POST',
            [$this, 'login']
        );

        $this->loader->add_endpoint(
            $this->namespace,
            '/email/is-registered',
            'POST',
            [$this, 'is_email_registered']
        );

        $this->loader->add_endpoint(
            $this->namespace,
            '/password/reset',
            'POST',
            [$this, 'reset_password']
        );
    }

    /**
     * @return void
     */
    public function login(WP_REST_Request $request) {
        $email = $request->get_param('email');
        $password = $request->get_param('password');

        $validatedLogin = $this->whmcsService->validate_login($email, $password);

        if (is_wp_error($validatedLogin)) {
            return new WP_REST_Response(['success' => false]);
        } else {
            $clientId = $this->whmcsService->get_client_id_of_email($email);

            if (is_wp_error($clientId)) {
                return new WP_REST_Response(['success' => false]);
            } else {
                $ssoToken = $this->whmcsService->create_sso_tokenn($validatedLogin['userId'], $clientId);

                if (is_wp_error($ssoToken)) {
                    return new WP_REST_Response(['success' => false]);
                } else {
                    return new WP_REST_Response([
                        'success' => true,
                        'redirectUrl' => $ssoToken['redirectUrl']
                    ]);
                }
            }
        }
    }

    /**
     * @return void
     */
    public function is_email_registered(WP_REST_Request $request) {
        $email = $request->get_param('email');

        if ($email === '') {
            return new WP_REST_Response(['success' => false]);
        }

        $emailIsRegistered = $this->whmcsService->is_email_registered($email);

        return new WP_REST_Response(['isRegistered' => $emailIsRegistered]);
    }

    public function reset_password(WP_REST_Request $request) {
        $email = $request->get_param('email');

        $success = $this->whmcsService->reset_password($email);

        return new WP_REST_Response(['success' => $success]);
    }
}
