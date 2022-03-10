<?php
/**
 * Login Form
 *
 * Template for displaying the login form. This is used in the [login_form] shortcode.
 * @link http://docs.restrictcontentpro.com/article/1598-loginform
 *
 * For modifying this template, please see: http://docs.restrictcontentpro.com/article/1738-template-files
 *
 * @package     Restrict Content Pro
 * @subpackage  Templates/Login
 * @copyright   Copyright (c) 2017, Restrict Content Pro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

global $rcp_login_form_args; ?>



<?php if ( ! is_user_logged_in() ) : ?>

    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 box-panel">
                <div class="panel-wrapper login-container">
                <?php if ( isset( $_GET['password-reset'] ) && 'true' == $_GET['password-reset'] ) { ?>
    <p class="rcp_success">
        <span><?php _e( 'Your password has been successfully reset.', 'rcp' ); if ( ! is_user_logged_in() ) _e( ' You may now log in.', 'rcp' ); ?></span>
                </p>
                    <?php } ?>

                    <?php rcp_show_error_messages( 'login' ); ?>
                    <a class="navbar-brand" href="<?= site_url() ?>"><img style="width: 200px" src="https://cookbook.nextsitehosting.com/wp-content/uploads/2021/12/Logo.png"></a>
                    <h1>Welcome Back!</h1>
                    <p class="description">While you're adding new recipes to your account, don't forget to visit our Postcards from Kitchen blog! You can even send a digital "postcard" to share you favorite recipes with friends and family!</p>
                    <form id="rcp_login_form"  class="<?php echo esc_attr( $rcp_login_form_args['class'] ); ?>" method="POST" action="<?php echo esc_url( rcp_get_current_url() ); ?>">

                        <?php do_action( 'rcp_before_login_form_fields' ); ?>
                        <div class="form">
                            <p class="form-group">
                                <label for="rcp_user_login"><?php _e( 'Username', 'rcp' ); ?></label>
                                <input name="rcp_user_login" id="rcp_user_login" class="required form-control" type="text"/>
                            </p>
                            <p class="form-group">
                                <label for="rcp_user_pass"><?php _e( 'Password', 'rcp' ); ?></label>
                                <input name="rcp_user_pass" id="rcp_user_pass" class="required form-control" type="password"/>
                            </p>
                            <?php do_action( 'rcp_login_form_fields_before_submit' ); ?>
                            <!-- <p>
                                <input type="checkbox" name="rcp_user_remember" id="rcp_user_remember" value="1"/>
                                <label for="rcp_user_remember"><?php //_e( 'Remember me', 'rcp' ); ?></label>
                            </p>
                            <p class="rcp_lost_password"><a href="<?php //echo esc_url( add_query_arg( 'rcp_action', 'lostpassword') ); ?>"><?php //_e( 'Lost your password?', 'rcp' ); ?></a></p> -->
                            <div class="d-flex align-items-center justify-content-between submit-container">
                                <input type="hidden" name="rcp_action" value="login"/>
                                <input type="hidden" name="rcp_redirect" value="<?php echo esc_url( $rcp_login_form_args['redirect'] ); ?>"/>
                                <input type="hidden" name="rcp_login_nonce" value="<?php echo wp_create_nonce( 'rcp-login-nonce' ); ?>"/>
                                <input id="rcp_login_submit" class="btn-normal" type="submit" value="<?php esc_attr_e( 'Login', 'rcp' ); ?>"/>

                                <div class="signup-section">
                                    Don’t have an account?
                                    <a href="<?php echo get_permalink('5'); ?>">Sign Up</a>
                                    <br>
                                    <a href="/my-account/lost-password/">Lost your password?</a>
                                </div>
                            </div>
                            <?php do_action( 'rcp_login_form_fields_after_submit' ); ?>

                        </div>

                        <?php do_action( 'rcp_after_login_form_fields' ); ?>

                    </form>
                </div>
            </div>
            <div class="col-md-6 stack-login-image">
                
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="rcp_logged_in"><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( 'Log out', 'rcp' ); ?></a></div>
<?php endif; ?>
