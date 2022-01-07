<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$cookbook_id = get_post_meta( $order->get_id(), 'cbf_cookbook_id', true );
$cookbook = get_post($cookbook_id);
?>

<style type="text/css">
    body {
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
    }

    table {
        border-spacing: 0;
    }

    table td {
        border-collapse: collapse;
    }

    .ExternalClass {
        width: 100%;
    }

    .ExternalClass,
    .ExternalClass p,
    .ExternalClass span,
    .ExternalClass font,
    .ExternalClass td,
    .ExternalClass div {
        line-height: 100%;
    }

    .ReadMsgBody {
        width: 100%;
        background-color: #ebebeb;
    }

    table {
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
    }

    img {
        -ms-interpolation-mode: bicubic;
    }

    .yshortcuts a {
        border-bottom: none !important;
    }

    @media screen and (max-width: 599px) {
        .force-row,
        .container {
            width: 100% !important;
            max-width: 100% !important;
        }
    }
    @media screen and (max-width: 400px) {
        .container-padding {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
    }
    .ios-footer a {
        color: #aaaaaa !important;
        text-decoration: underline;
    }
    a[href^="x-apple-data-detectors:"],
    a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }
</style>

<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0">
    <tr>
        <td align="center" valign="top" bgcolor="#F0F0F0" style="background-color: #F0F0F0;">

            <br>

            <!-- 600px container (white background) -->
            <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px">
                <tr>
                    <td class="container-padding header" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px">
                        <div style="width:660px;"><img src="https://cookbook.nextsitehosting.com/wp-content/uploads/2021/12/Logo.png" style="max-width:240px;"/></div>
                    </td>
                </tr>
                <tr>
                    <td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
                        <br>

                        <div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">﻿Thanks for publishing your cookbook <?= $cookbook->post_title ?></div>
                        <br>

                        <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
                            <p>﻿Our team is reviewing your request and will get in touch with you in the next few days.</p>

                            <?php do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email ); ?>

                    </td>
                </tr>
                <tr>
                    <td class="container-padding footer-text" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px">
                        <br><br>
                        <p class="copyright">
                            Copyright © <?php echo date('Y') ?> The Cookbook Creative       </p>
                        <br><br>

                    </td>
                </tr>
            </table>
            <!--/600px container -->


        </td>
    </tr>
</table>
