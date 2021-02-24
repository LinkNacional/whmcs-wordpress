<?php
/**
 * This is our callback function to return our products.
 *
 * @param WP_REST_Request $request This function accepts a rest request to process data.
 */
function prefix_get_products( $request ) {
    // In practice this function would fetch the desired data. Here we are just making stuff up.
    $products = [
        '1' => 'I am product 1',
        '2' => 'I am product 2',
        '3' => 'I am product 3',
    ];

    return rest_ensure_response( $products );
}

/**
 * This is our callback function to return a single product.
 *
 * @param WP_REST_Request $request This function accepts a rest request to process data.
 */
function prefix_create_product( $request ) {
    $identifier = get_option('whmcs_login_identifier');
    $secret = get_option('whmcs_login_secret');
    $url = get_option('whmcs_login_url');
    if ($_POST['action'] == 'ValidateLogin') {
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $array = [
            'action' => 'ValidateLogin',
            'username' => $identifier,
            'password' => $secret,
            'email' => $email,
            'password2' => $senha,
            'responsetype' => 'json',
        ];
    } elseif ($_POST['action'] == 'CreateSsoToken') {
        $array = [
            'action' => 'CreateSsoToken',
            'username' => $identifier,
            'password' => $secret,
            'responsetype' => 'json',
        ];

        if (isset($_POST['clid'])) {
            $arrayClient = ['client_id' => $_POST['clid']];
            $array = array_merge($array,$arrayClient);
        }

        if (isset($_POST['uid'])) {
            $arrayUser = ['user_id' => $_POST['uid']];
            $array = array_merge($array,$arrayUser);
        }

        if (isset($_POST['destination'])) {
            $arrayArea = ['destination' => 'clientarea:' . $_POST['destination']];
            $array = array_merge($array, $arrayArea);
        }
    }
    return connect($url, $array);
}

function connect($url, $array) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '/includes/api.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
/**
 * This function is where we register our routes for our example endpoint.
 */
function prefix_register_product_routes() {
    // Here we are registering our route for a collection of products and creation of products.
    register_rest_route( 'login-whmcs/v1', '/login', [
        [
            // By using this constant we ensure that when the WP_REST_Server changes, our readable endpoints will work as intended.
            'methods' => WP_REST_Server::READABLE,
            // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
            'callback' => 'prefix_get_products',
        ],
        [
            // By using this constant we ensure that when the WP_REST_Server changes, our create endpoints will work as intended.
            'methods' => WP_REST_Server::CREATABLE,
            // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
            'callback' => 'prefix_create_product',
        ],
    ] );
}
add_action( 'rest_api_init', 'prefix_register_product_routes' );