<?php
/**
 * Registration Form
 *
 * This template is used to display the registration form with [register_form] If the `id` attribute
 * is passed into the shortcode then register-single.php is used instead.
 * @link http://docs.restrictcontentpro.com/article/1597-registerform
 *
 * For modifying this template, please see: http://docs.restrictcontentpro.com/article/1738-template-files
 *
 * @package     Restrict Content Pro
 * @subpackage  Templates/Register
 * @copyright   Copyright (c) 2017, Restrict Content Pro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

global $rcp_options, $post, $rcp_levels_db, $rcp_register_form_atts;
$discount = ! empty( $_REQUEST['discount'] ) ? sanitize_text_field( $_REQUEST['discount'] ) : '';

$upgrading = isset($_GET['registration_type']) && ($_GET['registration_type'] == 'upgrade' || $_GET['registration_type'] == 'renewal');

// show any error messages after form submission
rcp_show_error_messages( 'register' ); ?>

<form id="rcp_registration_form" class="register-hubspot" method="POST" action="<?php echo esc_url( rcp_get_current_url() ); ?>">
    <!-- Hidden fields -->
    <input type="hidden" name="rcp_is_premium" id="rcp_is_premium" value="0">
    <div class="container box-panel">
        <div class="row  panel-wrapper">
            <div class="col-md-12">
                <?php if( ! is_user_logged_in() ) { ?>
                    <h3 class="rcp_header text-center">
                        Welcome Headline
                    </h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur doloribus incidunt rem. Labore numquam praesentium quia quibusdam veritatis. Culpa doloremque ducimus enim impedit iusto officiis perspiciatis sint, tempore ut. Inventore?</p>
                    <br><br>
                <?php } else { ?>
                    <h3 class="rcp_header text-center">
                        <?php echo apply_filters( 'rcp_registration_header_logged_in', $rcp_register_form_atts['logged_in_header'] ); ?>
                    </h3>
                    <br>
                <?php } ?>
            </div>

            <div class="col-md-6 col-xl-5">

                    <?php if( ! is_user_logged_in() ) { ?>

                        <?php do_action( 'rcp_before_register_form_fields' ); ?>
                        <h5 class="text-center left_heading_text">Create a FREE account</h5>
                        <div class="form-row">
                            <p id="rcp_user_first_wrap" class="form-group col-md-6">
                                <label for="rcp_user_first"><?php echo apply_filters ( 'rcp_registration_firstname_label', __( 'First Name', 'rcp' ) ); ?></label>
                                <input class="form-control required" name="rcp_user_first" id="rcp_user_first" type="text" <?php if( isset( $_POST['rcp_user_first'] ) ) { echo 'value="' . esc_attr( $_POST['rcp_user_first'] ) . '"'; } ?>/>
                            </p>
                            <p id="rcp_user_last_wrap" class="form-group col-md-6">
                                <label for="rcp_user_last"><?php echo apply_filters ( 'rcp_registration_lastname_label', __( 'Last Name', 'rcp' ) ); ?></label>
                                <input class="form-control required" name="rcp_user_last" id="rcp_user_last" type="text" <?php if( isset( $_POST['rcp_user_last'] ) ) { echo 'value="' . esc_attr( $_POST['rcp_user_last'] ) . '"'; } ?>/>
                            </p>
                            <p id="rcp_user_email_wrap" class="form-group col-md-12" >
                                <label for="rcp_user_email"><?php echo apply_filters ( 'rcp_registration_email_label', __( 'Email', 'rcp' ) ); ?></label>
                                <input name="rcp_user_email" id="rcp_user_email" class="required form-control" type="email" <?php if( isset( $_POST['rcp_user_email'] ) ) { echo 'value="' . esc_attr( $_POST['rcp_user_email'] ) . '"'; } ?>/>
                            </p>

                            <p id="rcp_password_wrap" class="form-group col-md-6">
                                <label for="rcp_password"><?php echo apply_filters ( 'rcp_registration_password_label', __( 'Password', 'rcp' ) ); ?></label>
                                <input name="rcp_user_pass" id="rcp_password" class="required form-control" type="password"/>
                            </p>

                            <p id="rcp_password_again_wrap" class="form-group col-md-6">
                                <label for="rcp_password_again"><?php echo apply_filters ( 'rcp_registration_password_again_label', __( 'Password Again', 'rcp' ) ); ?></label>
                                <input name="rcp_user_pass_confirm" id="rcp_password_again" class="required form-control" type="password"/>
                            </p>

                            <div class="form-group col-12">
                                <label for="">Account Type</label>
                            </div>
                            <div id="rcp_type_wrap" class="form-group col-md-12" >
                                <div class="type_option text-center">
                                    <input value="Personal" name="rcp_type" type="radio" <?= isset($_POST['rcp_type']) &&  $_POST['rcp_type'] == 'Personal' || !$upgrading ? 'checked' : '' ?>/>
                                <label for="rcp_type"><?php _e( 'Personal', 'rcp' ); ?></label>
                                </div>
                                <div class="type_option text-center">
                                    <input value="Business" name="rcp_type" type="radio" <?= isset($_POST['rcp_type']) && $_POST['rcp_type']  == 'Business' ? 'checked' : '' ?>/>
                                <label for="rcp_type"><?php _e( 'Business', 'rcp' ); ?></label>
                                </div>
                                <div class="type_option text-center">
                                    <input value="Fundraiser" name="rcp_type" type="radio" <?= isset($_POST['rcp_type']) && $_POST['rcp_type']  == 'Fundraiser' ? 'checked' : '' ?>/>
                                <label for="rcp_type"><?php _e( 'Fundraiser', 'rcp' ); ?></label>
                                </div>
                            </div>

                            <p id="rcp_submit_wrap" class="form-group col-md-12 text-center">
                                <input type="hidden" name="rcp_register_nonce" value="<?php echo wp_create_nonce('rcp-register-nonce' ); ?>"/>
                                <input type="submit" name="rcp_submit_registration" id="rcp_submit" class="btn-normal create_account_free" value="<?php esc_attr_e( apply_filters ( 'rcp_registration_register_button', __( 'Create a free account', 'rcp' ) ) ); ?>"/>
                            </p>
                            <button type="button" class="btn-normal" style="display: none" id="go_free">Go Back</button>

                        </div>

                        <?php do_action( 'rcp_after_password_registration_field' ); ?>

                    <?php } else{
                        $customer = rcp_get_customer_by_user_id( get_current_user_id() );
                        if($customer){
                            $memberships = $customer->get_memberships();
                            $premium = $memberships[0]->get_gateway() == 'free' || $memberships[0]->get_status() == 'cancelled' ? false : true;
                            if($premium){
                                echo "<h5>Customer Details</h5><br>";

                                $user = wp_get_current_user();
                                ?>
                                <strong>Name: </strong> <?php echo $user->display_name ?><br><br>
                                <strong>Email: </strong> <?php echo $user->user_email ?><br><br>
                                <strong>Current Plan: </strong> Premium<br><br>
                                <?php
                            }else{
                                echo "<h5>Customer Details</h5><br>";

                                $user = wp_get_current_user();
                                ?>
                                <strong>Name: </strong> <?php echo $user->display_name ?><br><br>
                                <strong>Email: </strong> <?php echo $user->user_email ?><br><br>
                                <strong>Current Plan: </strong> Free<br><br>
                                <?php
                            }

                            ?>
                            <h5>Payments</h5>
                            <table class="rcp-table" id="rcp-payment-history">
                                <thead>
                                <tr>
                                    <!--<th><?php /*_e( 'Invoice #', 'rcp' ); */?></th>-->
                                    <th><?php _e( 'Plan', 'rcp' ); ?></th>
                                    <th><?php _e( 'Amount', 'rcp' ); ?></th>
                                    <th><?php _e( 'Status', 'rcp' ); ?></th>
                                    <th><?php _e( 'Date', 'rcp' ); ?></th>
                                    <th><?php _e( '', 'rcp' ); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $payments = is_object( $customer ) ? $customer->get_payments() : false;
                                if ( $payments ) : ?>
                                    <?php foreach ( $payments as $payment ) : ?>
                                        <tr>
                                            <!--<td data-th="<?php /*esc_attr_e( 'Invoice #', 'rcp' ); */?>"><?php /*echo $payment->id; */?></td>-->
                                            <td data-th="<?php esc_attr_e( 'Membership', 'rcp' ); ?>"><?php echo esc_html( $payment->subscription ); ?></td>
                                            <td data-th="<?php esc_attr_e( 'Amount', 'rcp' ); ?>"><?php echo rcp_currency_filter( $payment->amount ); ?></td>
                                            <td data-th="<?php esc_attr_e( 'Status', 'rcp' ); ?>"><?php echo rcp_get_payment_status_label( $payment ); ?></td>
                                            <td data-th="<?php esc_attr_e( 'Date', 'rcp' ); ?>"><?php echo date('m-d-Y',strtotime( $payment->date, current_time( 'timestamp' ) )) ?></td>
                                            <td data-th="<?php esc_attr_e( 'Actions', 'rcp' ); ?>">
                                                <?php if ( in_array( $payment->status, array( 'pending', 'abandoned', 'failed' ) ) && empty( $payment->transaction_id ) ) : ?>
                                                    <a href="<?php echo esc_url( rcp_get_payment_recovery_url( $payment->id ) ); ?>">
                                                        <?php echo 'failed' === $payment->status ? __( 'Retry Payment', 'rcp' ) : __( 'Complete Payment', 'rcp' ); ?>
                                                    </a> <br/>
                                                <?php endif; ?>
                                                <a href="<?php echo esc_url( rcp_get_invoice_url( $payment->id ) ); ?>"><?php _e( 'Receipt', 'rcp' ); ?></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td data-th="<?php _e( 'Membership', 'rcp' ); ?>" colspan="6"><?php _e( 'You have not made any payments.', 'rcp' ); ?></td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <?php

                        }
                    }

                    do_action( 'rcp_before_subscription_form_fields' ); ?>


                    <?php /*do_action( 'rcp_after_register_form_fields', $levels ); */?>


            </div>
            <div class="col-xl-2 d-none d-xl-block">
                <br>
                <?php if(!$upgrading){ ?>
                    <div class="line_or f">
                        <span>OR</span>
                    </div>
                <?php } ?>

            </div>
            <div class="col-md-6 col-xl-5">
                <?php if( ! is_user_logged_in() ){ ?>
                    <div class="premium_boundaries" style="display: <?= $upgrading ? 'none' : 'block' ?>">
                        <h5 class="text-center">Create a PREMIUM account</h5>
                        <br><br>
                        <ul>
                            <li> <span class="icon_32"></span> <span class="feature">Add your recipes to a cookbook</span></li>
                            <li><span class="icon_32"></span> <span class="feature">Gain access to premium cookbook templates</span></li>
                            <li><span class="icon_32"></span> <span class="feature">Selling point</span></li>
                        </ul>
                    </div>
                <?php } ?>


                    <fieldset class="rcp_subscription_fieldset" style="display: <?= $upgrading ? 'block' : 'none'  ?>">
                        <?php
                        $levels = rcp_get_membership_levels( array( 'status' => 'active', 'number' => 100 ) );
                        $i      = 0;
                        if( $levels ) : ?>
                            <?php if ( count( $levels ) > 1 ) : ?>
                                <h5 class="rcp_subscription_message "><?php echo apply_filters ( 'rcp_registration_choose_subscription', __( 'Choose your plan', 'rcp' ) ); ?></h5>
                                <br>
                            <?php endif; ?>
                            <ul id="rcp_subscription_levels">
                                <?php foreach( $levels as $key => $level ) : ?>
                                    <?php if( rcp_show_subscription_level( $level->get_id() ) ) : ?>
                                        <li style="display: <?= $i == 0 && !$upgrading ? 'none' : 'block' ?>" class="rcp_subscription_level rcp_subscription_level_<?php echo esc_attr( $level->get_id() ); ?>">
                                            <input type="radio" id="rcp_subscription_level_<?php echo esc_attr( $level->get_id() ); ?>" class="required rcp_level" <?php if ( $i == 0 || ( isset( $_GET['level'] ) && $_GET['level'] == $level->get_id() ) ) { echo 'checked="checked"'; } ?> name="rcp_level" rel="<?php echo esc_attr( $level->get_price() ); ?>" value="<?php echo esc_attr( $level->get_id() ); ?>" <?php if( $level->is_lifetime() ) { echo 'data-duration="forever"'; } if ( $level->has_trial() ) { echo 'data-has-trial="true"'; } ?>/>
                                            <label for="rcp_subscription_level_<?php echo esc_attr( $level->get_id() ); ?>">
                                                <span class="rcp_subscription_level_name"><?php echo esc_html( $level->get_name() ); ?></span><span class="rcp_separator">&nbsp;-&nbsp;</span><span class="rcp_price" rel="<?php echo esc_attr( $level->get_price() ); ?>"><?php echo ! $level->is_free() ? rcp_currency_filter( $level->get_price() ) : __( 'free', 'rcp' ); ?></span><span class="rcp_separator">&nbsp;-&nbsp;</span>
                                                <span class="rcp_level_duration"><?php echo ! $level->is_lifetime() ? $level->get_duration() . '&nbsp;' . rcp_filter_duration_unit( $level->get_duration_unit(), $level->get_duration() ) : __( 'unlimited', 'rcp' ); ?></span>
                                                <?php if ( $level->get_maximum_renewals() > 0 ) : ?>
                                                    <span class="rcp_separator">&nbsp;-&nbsp;</span>
                                                    <span class="rcp_level_bill_times"><?php printf( __( '%d total payments', 'rcp' ), $level->get_maximum_renewals() + 1 ); ?></span>
                                                <?php endif; ?>
                                                <div class="rcp_level_description"> <?php echo $level->get_description(); ?></div>
                                            </label>

                                        </li>
                                        <?php $i++; endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p><strong><?php _e( 'You have not created any membership levels yet', 'rcp' ); ?></strong></p>
                        <?php endif; ?>
                    </fieldset>

                    <div class="rcp_gateway_fields">
                        <?php
                        $gateways = rcp_get_enabled_payment_gateways();
                        if( count( $gateways ) > 1 ) :
                            $display = rcp_has_paid_levels() ? '' : ' style="display: none;"';
                            $i = 1;
                            ?>
                            <fieldset class="rcp_gateways_fieldset">
                                <legend><?php _e( 'Choose Your Payment Method', 'rcp' ); ?></legend>
                                <p id="rcp_payment_gateways"<?php echo $display; ?>>
                                    <?php foreach( $gateways as $key => $gateway ) :
                                        $recurring = rcp_gateway_supports( $key, 'recurring' ) ? 'yes' : 'no';
                                        $trial    = rcp_gateway_supports( $key, 'trial' ) ? 'yes' : 'no'; ?>
                                        <label for="rcp_gateway_<?php echo esc_attr( $key ); ?>" class="rcp_gateway_option_label">
                                            <input id="rcp_gateway_<?php echo esc_attr( $key );?>" name="rcp_gateway" type="radio" class="rcp_gateway_option_input" value="<?php echo esc_attr( $key ); ?>" data-supports-recurring="<?php echo esc_attr( $recurring ); ?>" data-supports-trial="<?php echo esc_attr( $trial ); ?>" <?php checked( $i, 1 ); ?>>
                                            <?php echo esc_html( $gateway ); ?>
                                        </label>
                                        <?php
                                        $i++;
                                    endforeach; ?>
                                </p>
                            </fieldset>
                        <?php else: ?>
                            <?php foreach( $gateways as $key => $gateway ) :
                                $recurring = rcp_gateway_supports( $key, 'recurring' ) ? 'yes' : 'no';
                                $trial = rcp_gateway_supports( $key, 'trial' ) ? 'yes' : 'no';
                                ?>
                                <input type="hidden" name="rcp_gateway" value="<?php echo esc_attr( $key ); ?>" data-supports-recurring="<?php echo esc_attr( $recurring ); ?>" data-supports-trial="<?php echo esc_attr( $trial ); ?>"/>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php do_action( 'rcp_before_registration_submit_field', $levels ); ?>

                <?php if(!$upgrading){ ?><br><p id="rcp_submit_wrap"><button class="btn-normal" type="button" id="go_premium">Create a premium account</button></p><?php }  ?>
                    <p id="rcp_submit_wrap" style="display: <?= $upgrading ? 'block' : 'none' ?>" class="create_account_premium">
                        <input type="hidden" name="rcp_register_nonce" value="<?php echo wp_create_nonce('rcp-register-nonce' ); ?>"/>
                        <input type="submit" name="rcp_submit_registration" id="rcp_submit" class="btn-normal mt-3" value="<?= $upgrading ? 'Upgrade Account' : 'Create Account' ?>"/>
                    </p>
            </div>
            <br><br>
            <div class="col-12 text-center mt-3">
                <?php if(!$upgrading){ ?>
                    <p class="text-center">Already have an account <a href="/login">Log in</a></p>
                <?php } ?>
            </div>
        </div>
        <br>

    </div>

</form>
<?php
if(!$upgrading){
    ?>
    <script>
        jQuery(function($) {

            $(document).ready(function() {
                setTimeout(function(){ $('.rcp_gateway_stripe_fields').css('display','none'); },1000);
                $('#go_premium').on('click',function(){
                    $('#rcp_subscription_level_1').trigger('click');
                    $('.rcp_subscription_fieldset').css('display', 'block');
                    $('.rcp_gateway_stripe_fields').css('display','block');
                    $('.premium_boundaries').css('display','none');
                    $('#go_free').css('display','block');
                    $(this).css('display','none');
                    $('.create_account_free').css('display','none');
                    $('.create_account_premium').css('display','block');
                    $('#rcp_subscription_level_1').trigger('click');
                    $('.left_heading_text').text('Create a PREMIUM account');
                    $('.line_or').css('display','none');
                    $('#rcp_is_premium').val(1);
                });

                $('#go_free').on('click',function(){
                    $('#rcp_subscription_level_3').trigger('click');
                    $('.premium_boundaries').css('display','block');
                    $('.rcp_subscription_fieldset').css('display', 'none');
                    $('.rcp_gateway_stripe_fields').css('display','none');
                    $(this).css('display','none');
                    $('#go_premium').css('display','block');
                    $('.create_account_free').css('display','block');
                    $('.create_account_premium').css('display','none');
                    $('.left_heading_text').text('Create a FREE account');
                    $('.line_or').css('display','flex');
                    $('#rcp_is_premium').val(0);
                });

            });

        });

    </script>
    <?php
}
?>
<style>
    #rcp-sandbox-gateway-test-cards{
        display: none;
    }
</style>
