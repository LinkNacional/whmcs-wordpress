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
            <h2 class="title"><?php _e('Whmcs API information','whmcs-wordpress')?></h2>
            <p><?php _e('API information can be obtained by following the step by step ','whmcs-wordpress')?> <a href="https://docs.whmcs.com/API_Authentication_Credentials#Creating_Admin_API_Authentication_Credentials"><?php _e('here','whmcs-wordpress') ?></a> </p>
                <tr>
                    <th scope="row">
                        <label for="whmcs_login_identifier"><?php _e('WHMCS API identifier','whmcs-wordpress')?></label>
                    </th>
                    <td>
                        <input name="whmcs_login_identifier" type="password" id="whmcs_login_identifier" onfocus="this.value='';" value="<?php echo get_option('whmcs_login_identifier') ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="whmcs_login_secret"><?php _e('WHMCS API secret','whmcs-wordpress')?></label>
                    </th>
                    <td>
                        <input name="whmcs_login_secret" type="password" id="whmcs_login_secret" onfocus="this.value='';" value="<?php echo get_option('whmcs_login_secret') ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="whmcs_login_url"><?php _e('WHMCS url','whmcs-wordpress')?></label>
                    </th>
                    <td>
                        <input name="whmcs_login_url" type="text" id="whmcs_login_url" value="<?php echo get_option('whmcs_login_url') ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="whmcs_login_password_reset"><?php _e('link to retrieve the WHMCS password','whmcs-wordpress')?></label>
                    </th>
                    <td>
                        <input name="whmcs_login_password_reset" type="text" id="whmcs_login_password_reset" value="<?php echo get_option('whmcs_login_password_reset') ?>" class="regular-text">
                        <!-- <p class="description" for="whmcs_login_password_reset">
                            deixar em branco para não aparecer o botão
                        </p> -->
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="whmcs_login_register_user"><?php _e('link to register a new WHMCS user','whmcs-wordpress')?></label>
                    </th>
                    <td>
                        <input name="whmcs_login_register_user" type="text" id="whmcs_login_register_user" value="<?php echo get_option('whmcs_login_register_user') ?>" class="regular-text">
                        <!-- <p class="description" for="whmcs_login_register_user">
                            deixar em branco para não aparecer o botão
                        </p> -->
                    </td>
                </tr>
            </tbody>
        </table>
            <?php
    settings_fields( 'whmcs_login' );
    do_settings_sections( 'whmcs_login_session' );
    submit_button( __('Save'), 'textdomain' ); ?>
        </form>
    </div>
    <?php
}

function configs_submit() {
    if ($_POST['nonce'] && wp_verify_nonce($_POST['nonce'],'save_config_whmcs_login')) {
        if (substr($_POST['whmcs_login_url'], -1) != '/') {
            $_POST['whmcs_login_url'] = $_POST['whmcs_login_url'] . '/';
        }
        if ($_POST['whmcs_login_secret'] != '') {
            update_option('whmcs_login_secret',$_POST['whmcs_login_secret']);
        }
        if ($_POST['whmcs_login_identifier'] != '') {
            update_option('whmcs_login_identifier',$_POST['whmcs_login_identifier']);
        }
        update_option('whmcs_login_url',$_POST['whmcs_login_url']);
        update_option('whmcs_login_register_user',$_POST['whmcs_login_register_user']);
        update_option('whmcs_login_password_reset',$_POST['whmcs_login_password_reset']);
    }
}
