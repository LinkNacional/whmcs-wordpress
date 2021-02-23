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
            <tbody>
                <tr>
                <input type="hidden" id="nonce" name="nonce" value="<?php $GLOBALS['nonce'] ?>">
                    <th scope="row">
                        <label for="whmcs_login_url">url do WHMCS</label>
                    </th>
                    <td>
                    <?php wp_nonce_field('save_config_whmcs_login','nonce'); ?>
                        <input name="whmcs_login_url" type="text" id="whmcs_login_url" value="<?php echo get_option('whmcs_login_url') ?>" class="regular-text">
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
        update_option('whmcs_login_url',$_POST['whmcs_login_url']);
    }
}