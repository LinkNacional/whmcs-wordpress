<?php
function get_texts() {
    $email_label = __('Enter the registered email address','whmcswordpress');
    $email_error_null = __('Enter email','whmcswordpress');
    $email_error_not_domain = __('Enter a registered email','whmcswordpress');
    $email_error_not_user = __('There is no user registered with this email','whmcswordpress');
    $email_error_not_valid = __('Enter a valid email address','whmcswordpress');

    $password_label = __('Type the password','whmcswordpress');
    $password_error = __('Enter your password','whmcswordpress');

    $btn_next = __('Next','whmcswordpress');
    $btn_enter = __('Get in','whmcswordpress');
    $btn_register = __('Register','whmcswordpress');
    $btn_password = __('Forgot password?','whmcswordpress');

    // $email_label = __('Digite o endereço de e-mail cadastrado');
    // $email_error_null = __('Digite um e-mail');
    // $email_error_not_domain = __('Digite um e-mail cadastrado');
    // $email_error_not_user = __('Não existe nenhum usuário cadastrado com este e-mail');
    // $email_error_not_valid = __('Digite um e-mail válido');

    // $password_label = __('Digite a senha');
    // $password_error = __('Digite sua senha de acesso');

    // $btn_next = __('Próximo');
    // $btn_enter = __('Entrar');
    // $btn_register = __('Registrar');
    // $btn_password = __('Esqueceu a senha?');

    $list_translate = [
        'email_label' => $email_label,
        'email_error_null' => $email_error_null,
        'email_error_not_domain' => $email_error_not_domain,
        'email_error_not_user' => $email_error_not_user,
        'email_error_not_valid' => $email_error_not_valid,
        'password_label' => $password_label,
        'password_error' => $password_error,
        'btn_next' => $btn_next,
        'btn_enter' => $btn_enter,
        'btn_register' => $btn_register,
        'btn_password' => $btn_password
    ];
    return $list_translate;
}