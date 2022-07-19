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

class Whmcs_Wordpress_Whmcs_Services {
    private $api_identifier;
    private $api_secret;
    private $whmcs_url;

    public function __construct() {
        $this->api_identifier = get_option('whmcs_wordpress_setting_whmcs_api_identifier');
        $this->api_secret = get_option('whmcs_wordpress_setting_whmcs_api_secret');
        $this->whmcs_url = get_option('whmcs_wordpress_setting_whmcs_url');
    }

    /**
     * Auth into the WHMCS API and executes the request.
     *
     * @param  string $resource - WHMCS API resource without starting slash.
     * @param  array $body
     * @param  string $httpMethod
     *
     * @see https://developers.whmcs.com/api/authentication/
     *
     * @return void
     */
    private function connect($resource, $body, $httpMethod = 'POST') {
        $request = array_merge($body, [
            'body' => [
                'username' => $this->api_identifier,
                'password' => $this->api_secret,
                'responsetype' => 'json',
            ],
            'method' => $httpMethod
        ]);

        return wp_remote_post($this->whmcs_url . $resource, $request);
    }
}
