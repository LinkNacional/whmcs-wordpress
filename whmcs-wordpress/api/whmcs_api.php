<?php

function login($identifier,$secret,$request) {
    $email = $request['email'];
    $senha = $request['senha'];
    $array = [
        'action' => 'ValidateLogin',
        'username' => $identifier,
        'password' => $secret,
        'email' => $email,
        'password2' => $senha,
        'responsetype' => 'json',
    ];
    return connect($array);
}

function createToken($identifier,$secret,$request) {
    $array = [
        'action' => 'CreateSsoToken',
        'username' => $identifier,
        'password' => $secret,
        'responsetype' => 'json',
    ];
    if (isset($request['clid'])) {
        $arrayClient = ['client_id' => $request['clid']];
        $array = array_merge($array,$arrayClient);
    }
    if (isset($request['uid'])) {
        $arrayUser = ['user_id' => $request['uid']];
        $array = array_merge($array,$arrayUser);
    }
    if (isset($request['destination'])) {
        $arrayArea = ['destination' => 'clientarea:' . $request['destination']];
        $array = array_merge($array, $arrayArea);
    }
    return connect($array);
}

function searchByEmail($identifier,$secret, $email) {
    $array = [
        'action' => 'GetClients',
        // See https://developers.whmcs.com/api/authentication
        'username' => $identifier,
        'password' => $secret,
        'limitnum' => 5,
        'search' => $email,
        'responsetype' => 'json',
    ];
    return connect($array);
}

function connect($array) {
    $url = get_option('whmcs_login_url');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . 'includes/api.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function post_actions( $request ) {
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json; charset=utf-8');
    $request = (json_decode(file_get_contents('php://input'), true));
    $identifier = get_option('whmcs_login_identifier');
    $secret = get_option('whmcs_login_secret');
    //action = ValidateLogin
    if ($request['action'] == 'ValidateLogin') {
        $resLogin = login($identifier,$secret,$request);
        if (json_decode($resLogin)->result === 'success') {
            echo $resLogin;
        } else {
            if (json_decode($resLogin)->result === 'error') {
                /// pesquisar se o email esta cadastrado com usuário.
                if (json_decode(searchByEmail($identifier,$secret,$request['email']))->totalresults >= 1) {
                    /// SENHA ERRADA
                    echo '{"result":"password"}';
                /// NOVO CLIENTE
                } else {
                    echo '{"result":"notin"}';
                }
            }
        }
    }
    //action = CreateSsoToken
    if ($request['action'] == 'CreateSsoToken') {
        $resToken = createToken($identifier,$secret,$request);
        if ($resToken != null) {
            echo $resToken; /// Sessão iniciada
        } else {
            echo '{"result":"notin"}';  /// Sessão falhou
        }
    } 
    //action = checkEmail
    if ($request['action'] == 'checkEmail') {
        if (json_decode(searchByEmail($identifier, $secret, $request['email']))->totalresults >= 1) {
            echo '{"result":"success"}';
        /// NOVO CLIENTE
        } else {
            echo '{"result":"notin"}';
        }
    }
    //action = url_redirect

    if ($request['action'] == 'url_redirect') {
        $url_password = get_option('whmcs_login_password_reset');
        $url_register = get_option('whmcs_login_register_user');
        echo json_encode(['password' => $url_password, 'register' => $url_register]);
    }
}

function prefix_register_product_routes() {
    register_rest_route( 'login-whmcs/v1', '/login', [
        [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => 'post_actions',
        ]
    ] );
}
add_action( 'rest_api_init', 'prefix_register_product_routes' );