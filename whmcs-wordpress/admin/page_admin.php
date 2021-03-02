<?php
add_action('admin_menu', 'wporg_options_page');

function wporg_options_page() {
    $hookname = add_submenu_page(
        'options-general.php',
        'WHMCS login',
        'WHMCS login',
        'manage_options',
        'Login_whmcs',
        'pageConfig'
    );
    add_action( 'load-' . $hookname, 'configs_submit' );
}

function pageConfig() {
    if ( !current_user_can( 'manage_options' ) ) {
        return;
    } ?>
      <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="<?php menu_page_url( 'wporg' ) ?>" method="post">
        <table class="form-table" role="presentation">
            <?php wp_nonce_field('save_config_whmcs_login','nonce'); ?>
            <tbody>
            <h2 class="title">Informações da API do whmcs</h2>
            <p>As informações da API podem ser conseguidas seguindo o passo a passo <a href="https://docs.whmcs.com/API_Authentication_Credentials#Creating_Admin_API_Authentication_Credentials">aqui</a> </p>
                <tr>
                    <th scope="row">
                        <label for="whmcs_login_identifier">identificador da API WHMCS</label>
                    </th>
                    <td>
                        <input name="whmcs_login_identifier" type="password" id="whmcs_login_identifier" value="<?php echo get_option('whmcs_login_identifier') ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="whmcs_login_secret">Segredo da API WHMCS</label>
                    </th>
                    <td>
                        <input name="whmcs_login_secret" type="password" id="whmcs_login_secret" value="<?php echo get_option('whmcs_login_secret') ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="whmcs_login_url">url do WHMCS</label>
                    </th>
                    <td>
                        <input name="whmcs_login_url" type="text" id="whmcs_login_url" value="<?php echo get_option('whmcs_login_url') ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="whmcs_login_password_reset">link para recuperar a senha WHMCS</label>
                    </th>
                    <td>
                        <input name="whmcs_login_password_reset" type="text" id="whmcs_login_password_reset" value="<?php echo get_option('whmcs_login_password_reset') ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="whmcs_login_register_user">link para registrar um novo usuario WHMCS</label>
                    </th>
                    <td>
                        <input name="whmcs_login_register_user" type="text" id="whmcs_login_register_user" value="<?php echo get_option('whmcs_login_register_user') ?>" class="regular-text">
                    </td>
                </tr>
            </tbody>
        </table>
            <?php
    settings_fields( 'whmcs_login' );
    do_settings_sections( 'whmcs_login_session' );
    submit_button('Salvar alterações', 'textdomain' ); ?>
        </form>
    </div>
    <?php
}

function configs_submit() {
    if ($_POST['nonce'] && wp_verify_nonce($_POST['nonce'],'save_config_whmcs_login')) {
        if (substr($_POST['whmcs_login_url'], -1) != '/') {
            $_POST['whmcs_login_url'] = $_POST['whmcs_login_url'] . '/';
        }
        update_option('whmcs_login_url',$_POST['whmcs_login_url']);
        update_option('whmcs_login_identifier',$_POST['whmcs_login_identifier']);
        update_option('whmcs_login_secret',$_POST['whmcs_login_secret']);
        update_option('whmcs_login_register_user',$_POST['whmcs_login_register_user']);
        update_option('whmcs_login_password_reset',$_POST['whmcs_login_password_reset']);
    }
}
