<template>
    <div class="container">
        <div class="row">
            <form id="rcp_registration_form" class="rcp_form" method="POST" action="https://cookbook.nextsitehosting.com/register/" style="position: static; zoom: 1;">
            <div class="col-md-6">

                    <div class="form-row">
                        <p id="rcp_user_first_wrap" class="form-group col-md-12">
                            <label for="rcp_user_first">First Name</label>
                            <input class="form-control required" name="rcp_user_first" id="rcp_user_first" type="text">
                        </p>
                        <p id="rcp_user_email_wrap" class="form-group col-md-12">
                            <label for="rcp_user_email">Email</label>
                            <input name="rcp_user_email" id="rcp_user_email" class="required form-control" type="email">
                        </p>


                        <p id="rcp_password_wrap" class="form-group col-md-12">
                            <label for="rcp_password">Create a password</label>
                            <input name="rcp_user_pass" id="rcp_password" class="required form-control" type="password">
                        </p>

                        <p v-show="account_premium" id="rcp_type_wrap" class="form-group col-md-12">
                            <label for="rcp_type">Type</label>
                            <select name="rcp_type" id="rcp_type" class="required form-control">
                                <option value="Personal" selected="">Personal</option>
                                <option value="Business">Business</option>
                                <option value="Fundraiser">Fundraiser</option>
                            </select>
                        </p>

                    </div>

                    <fieldset v-show="false" class="rcp_subscription_fieldset">
                        <ul id="rcp_subscription_levels">
                            <li class="rcp_subscription_level rcp_subscription_level_3">
                                <input type="radio" id="rcp_subscription_level_3" class="required rcp_level" :checked="!account_premium" name="rcp_level" rel="0" value="3" data-duration="forever">
                                <label for="rcp_subscription_level_3">
                                    <span class="rcp_subscription_level_name">Free Plan</span><span class="rcp_separator">&nbsp;-&nbsp;</span><span class="rcp_price" rel="0">free</span><span class="rcp_separator">&nbsp;-&nbsp;</span>
                                    <span class="rcp_level_duration">unlimited</span>
                                    <div class="rcp_level_description"> </div>
                                </label>

                            </li>
                            <li class="rcp_subscription_level rcp_subscription_level_1">
                                <input type="radio" id="rcp_subscription_level_1" class="required rcp_level" name="rcp_level" rel="10" value="1" :checked="account_premium">
                                <label for="rcp_subscription_level_1">
                                    <span class="rcp_subscription_level_name">Monthly Plan</span><span class="rcp_separator">&nbsp;-&nbsp;</span><span class="rcp_price" rel="10">$10.00</span><span class="rcp_separator">&nbsp;-&nbsp;</span>
                                    <span class="rcp_level_duration">1&nbsp;Month</span>
                                    <div class="rcp_level_description"> </div>
                                </label>
                            </li>

                        </ul>
                    </fieldset>

            </div>
            <div class="col-md-6">
                <div v-show="!account_premium" class="free_account_boundaries">
                    <h4>Upgrade to our PREMIUM account</h4>
                    <ul>
                        <li>Add your recipes to a cookbook</li>
                        <li>Gain access to premium cookbook</li>
                        <li>Selling point</li>
                    </ul>
                </div>
                <div v-show="account_premium" class="payment_methods">
                    <div class="rcp_gateway_fields" style="">
                        <input type="hidden" name="rcp_gateway" value="stripe" data-supports-recurring="yes" data-supports-trial="yes">
                    </div>
                    <div class="rcp_gateway_stripe_fields" id="rcp_gateway_extra_fields">

                        <div class="rcp-gateway-new-card-fields">
                            <fieldset class="rcp_card_fieldset">
                                <div id="rcp_card_name_wrap">
                                    <label for="rcp-card-name">Name on Card</label>
                                    <input type="text" size="20" name="rcp_card_name" id="rcp-card-name" class="rcp_card_name card-name">
                                </div>

                                <div id="rcp_card_wrap">
                                    <label for="rcp-card-element">Credit Card</label>
                                    <div id="rcp-card-element" class="StripeElement StripeElement--empty"><div class="__PrivateStripeElement" style="margin: 0px !important; padding: 0px !important; border: none !important; display: block !important; background: transparent !important; position: relative !important; opacity: 1 !important;"><iframe name="__privateStripeFrame3975" frameborder="0" allowtransparency="true" scrolling="no" allow="payment *" src="https://js.stripe.com/v3/elements-inner-card-73bcd730feea464595aba3cba8d85a65.html#wait=false&amp;mids[guid]=NA&amp;mids[muid]=NA&amp;mids[sid]=NA&amp;rtl=false&amp;componentName=card&amp;keyMode=test&amp;apiKey=pk_test_51C9cVLAY47vxYGIAA6n963DaQfpanuWa60vwg2Mr9HT6aOGv35EppS7Dn3FekE7dCsfWdaOBXRq0nHs19Obokdpm006yJ553dG&amp;referrer=https%3A%2F%2Fcookbook.nextsitehosting.com%2Fregister%2F&amp;controllerId=__privateStripeController3971" title="Secure card payment input frame" style="border: none !important; margin: 0px !important; padding: 0px !important; width: 1px !important; min-width: 100% !important; overflow: hidden !important; display: block !important; user-select: none !important; transform: translate(0px) !important; height: 24px;"></iframe><input class="__PrivateStripeElement-input" aria-hidden="true" aria-label=" " autocomplete="false" maxlength="1" style="border: none !important; display: block !important; position: absolute !important; height: 1px !important; top: -1px !important; left: 0px !important; padding: 0px !important; margin: 0px !important; width: 100% !important; opacity: 0 !important; background: transparent !important; pointer-events: none !important; font-size: 16px !important;"></div></div>
                                </div>
                            </fieldset>
                        </div>

                        <div id="rcp-card-element-errors"></div>
                    </div>

                </div>
            </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" id="rcp-registration-type" name="registration_type" value="">
                            <input type="hidden" id="rcp-membership-id" name="membership_id" value="0">
                            <input type="hidden" id="rcp-payment-id" name="rcp_registration_payment_id" value="0">

                            <p id="rcp_submit_wrap">
                                <input type="hidden" name="rcp_register_nonce" value="3e6760d618">
                                <input type="submit" name="rcp_submit_registration" id="rcp_submit" class="rcp-button" value="Create Account">
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
               account_premium: false
            }
        },
    }
</script>


<style>

</style>
