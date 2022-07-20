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
        $whmcsUrl = ltrim(get_option('whmcs_wordpress_setting_whmcs_url'), '/');

        $this->whmcs_url = $whmcsUrl . '/includes/api.php';

        $this->api_identifier = get_option('whmcs_wordpress_setting_whmcs_api_identifier');
        $this->api_secret = get_option('whmcs_wordpress_setting_whmcs_api_secret');
    }

    /**
     * Auth into the WHMCS API and executes the request.
     *
     * @param  string $action
     * @param  array $body
     * @param  string $httpMethod
     *
     * @see https://developers.whmcs.com/api/authentication/
     *
     * @return array|WP_Error
     */
    private function connect($action, $body, $httpMethod = 'POST') {
        $request = [
            'body' => [
                'action' => $action,
                'username' => $this->api_identifier,
                'password' => $this->api_secret,
                'responsetype' => 'json',
            ],
            'method' => $httpMethod
        ];

        $request['body'] = array_merge($request['body'], $body);

        return wp_remote_post($this->whmcs_url, $request);
    }

    /**
     * @param  array $response
     *
     * @return bool
     */
    private function is_success_response($response) {
        return isset($response['result']) && $response['result'] === 'success';
    }

    /**
     * @param  string $email
     * @param  string $password
     *
     * @return void
     */
    public function validate_login($email, $password) {
        $response = $this->connect('ValidateLogin', ['email' => $email, 'password2' => $password]);

        $response = json_decode(wp_remote_retrieve_body($response), true);

        if ($this->is_success_response($response)) {
            return ['userId' => $response['userid']];
        } else {
            return new WP_Error('api_error');
        }
    }

    public function create_sso_token($userId, $clientId) {
        $requestBody = [
            'destination' => 'sso:custom_redirect',
            'sso_redirect_path' => 'index.php?rp=/user/accounts',
        ];

        if ($clientId === null) {
            $requestBody['user_id'] = $userId;
        } else {
            $requestBody['client_id'] = $clientId;
        }

        $response = $this->connect('CreateSsoToken', $requestBody);
        $response = json_decode(wp_remote_retrieve_body($response), true);

        if ($this->is_success_response($response)) {
            return ['redirectUrl' => $response['redirect_url']];
        } else {
            return new WP_Error('api_error');
        }
    }

    public function get_client_id_of_email($email) {
        $getClients = $this->connect('GetClients', ['search' => $email]);

        if (is_wp_error($getClients)) {
            return new WP_Error('api_error');
        }

        $response = json_decode(wp_remote_retrieve_body($getClients), true);

        if (
            isset($response['result']) &&
            $response['result'] === 'success' &&
            $response['totalresults'] >= 1
        ) {
            return $response['clients']['client'][0]['id'];
        } else {
            return null;
        }
    }

    public function is_email_registered($email) {
        $emailExistsForClient = $this->client_email_exists($email);

        if (is_wp_error($emailExistsForClient)) {
            return false;
        } else {
            if ($emailExistsForClient) {
                return true;
            } else {
                $emailExistsForUser = $this->user_email_exists($email);

                return is_wp_error($emailExistsForUser) ? false : $emailExistsForUser;
            }
        }
    }

    /**
     * @param  string $email
     *
     * @return bool|\WP_Error
     */
    private function client_email_exists($email) {
        $getClients = $this->connect('GetClients', ['search' => $email]);

        if (!is_wp_error($getClients)) {
            $response = json_decode(wp_remote_retrieve_body($getClients), true);

            if (
                isset($response['result']) &&
                $response['result'] === 'success' &&
                $response['totalresults'] >= 1
            ) {
                return true;
            } else {
                return false;
            }
        } else {
            return new WP_Error('api_error');
        }
    }

    /**
     * @param  string $email
     *
     * @return bool|\WP_Error
     */
    private function user_email_exists($email) {
        $getUsers = $this->connect('GetUsers', ['search' => $email]);

        if (!is_wp_error($getUsers)) {
            $response = json_decode(wp_remote_retrieve_body($getUsers), true);

            if (
                isset($response['result']) &&
                $response['result'] === 'success' &&
                $response['totalresults'] >= 1
            ) {
                return true;
            } else {
                return false;
            }
        } else {
            return new WP_Error('api_error');
        }
    }

    public function reset_password($email) {
        $response = $this->connect('ResetPassword', ['email' => $email]);

        if (!is_wp_error($response)) {
            $response = json_decode(wp_remote_retrieve_body($response), true);

            return $this->is_success_response($response);
        } else {
            return new WP_Error('api_error');
        }
    }
}
