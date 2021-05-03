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

function resetpassword($identifier,$secret,$request) {
    $email = $request['email'];
    $array = [
        'action' => 'ResetPassword',
        'username' => $identifier,
        'password' => $secret,
        'email' => $email,
        'responsetype' => 'json',
    ];
    return connect($array);
}

function createToken($identifier,$secret,$user_id,$client_id) {
    $array = [
        'action' => 'CreateSsoToken',
        'username' => $identifier,
        'password' => $secret,
        'responsetype' => 'json',
        'destination' => 'sso:custom_redirect',
        'sso_redirect_path' => 'index.php?rp=/user/accounts',
        'user_id' => $user_id,
        'client_id' => $client_id
    ];

    return connect($array);
}

function searchByEmail($identifier,$secret, $email) {
    $array = [
        'action' => 'GetClients',
        // See https://developers.whmcs.com/api/authentication
        'username' => $identifier,
        'password' => $secret,
        'search' => $email,
        'responsetype' => 'json'
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
            $userid = json_decode($resLogin)->userid;
            $resToken = createToken($identifier,$secret,$userid,$request['idCliente']);
            if ($resToken != null) {
                echo ($resToken); /// Sessão iniciada
            } else {
                echo '{"result":"notinSession"}';  /// Sessão falhou
            }
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
    //action = checkEmail
    if ($request['action'] == 'checkEmail') {
        $email = $request['email'];
        $return = json_decode(searchByEmail($identifier, $secret,$email ));
        if ($return->totalresults >= 1) {
            foreach ($return->clients->client as  $elementKey => $element) {
                if ($element->email != $email) {
                    unset($return->clients->client[$elementKey]);
                } else {
                    unset($return->clients->client[$elementKey]);
                    $return->clients->client[0] = $element;
                }
            }
            echo json_encode($return);
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

    if ($request['action'] == 'resetpassword') {
        $request = (json_decode(file_get_contents('php://input'), true));
        $identifier = get_option('whmcs_login_identifier');
        $secret = get_option('whmcs_login_secret');
        echo resetpassword($identifier,$secret,$request);
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