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
                        <label for="whmcs_login_identifier">identificador da API WHMCS</label>
                    </th>
                    <td>
                        <input name="whmcs_login_identifier" type="text" id="whmcs_login_identifier" value="<?php echo get_option('whmcs_login_identifier') ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="whmcs_login_secret">Segredo da API WHMCS</label>
                    </th>
                    <td>
                        <input name="whmcs_login_secret" type="text" id="whmcs_login_secret" value="<?php echo get_option('whmcs_login_secret') ?>" class="regular-text">
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
        update_option('whmcs_login_identifier',$_POST['whmcs_login_identifier']);
        update_option('whmcs_login_secret',$_POST['whmcs_login_secret']);
    }
    createJson();
}

 function createJson() {
     try {
         $fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/url.json', 'w+');
         fwrite($fp, json_encode(['link' => get_site_url()]));
         fclose($fp);
     } catch (Exception $e) {
         return $e->getMessage();
     }
 }
