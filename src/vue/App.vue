<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <form id="rcp_registration_form" class="rcp_form" method="POST" action="https://cookbook.nextsitehosting.com/register/" style="position: static; zoom: 1;">


                    <!--<div class="rcp_login_link">
                        <p></p>
                    </div>-->


                    <div class="form-row">
                        <p id="rcp_user_first_wrap" class="form-group col-md-6">
                            <label for="rcp_user_first">First Name</label>
                            <input class="form-control required" name="rcp_user_first" id="rcp_user_first" type="text">
                        </p>
                        <p id="rcp_user_last_wrap" class="form-group col-md-6">
                            <label for="rcp_user_last">Last Name</label>
                            <input class="form-control required" name="rcp_user_last" id="rcp_user_last" type="text">
                        </p>
                        <p id="rcp_user_email_wrap" class="form-group col-md-12">
                            <label for="rcp_user_email">Email</label>
                            <input name="rcp_user_email" id="rcp_user_email" class="required form-control" type="email">
                        </p>
                        <p id="rcp_type_wrap" class="form-group col-md-12">
                            <label for="rcp_type">Type</label>
                            <select name="rcp_type" id="rcp_type" class="required form-control">
                                <option value="Personal" selected="">Personal</option>
                                <option value="Business">Business</option>
                                <option value="Fundraiser">Fundraiser</option>
                            </select>
                        </p>

                        <p id="rcp_password_wrap" class="form-group col-md-12">
                            <label for="rcp_password">Password</label>
                            <input name="rcp_user_pass" id="rcp_password" class="required form-control" type="password">
                        </p>
                        <p id="rcp_password_again_wrap" class="form-group col-md-12">
                            <label for="rcp_password_again">Password Again</label>
                            <input name="rcp_user_pass_confirm" id="rcp_password_again" class="required form-control" type="password">
                        </p>

                    </div>





                    <fieldset class="rcp_subscription_fieldset">
                        <p class="rcp_subscription_message">Choose your membership level</p>
                        <ul id="rcp_subscription_levels">
                            <li class="rcp_subscription_level rcp_subscription_level_3">
                                <input type="radio" id="rcp_subscription_level_3" class="required rcp_level" checked="checked" name="rcp_level" rel="0" value="3" data-duration="forever">
                                <label for="rcp_subscription_level_3">
                                    <span class="rcp_subscription_level_name">Free Plan</span><span class="rcp_separator">&nbsp;-&nbsp;</span><span class="rcp_price" rel="0">free</span><span class="rcp_separator">&nbsp;-&nbsp;</span>
                                    <span class="rcp_level_duration">unlimited</span>
                                    <div class="rcp_level_description"> </div>
                                </label>

                            </li>
                            <li class="rcp_subscription_level rcp_subscription_level_1">
                                <input type="radio" id="rcp_subscription_level_1" class="required rcp_level" name="rcp_level" rel="10" value="1">
                                <label for="rcp_subscription_level_1">
                                    <span class="rcp_subscription_level_name">Monthly Plan</span><span class="rcp_separator">&nbsp;-&nbsp;</span><span class="rcp_price" rel="10">$10.00</span><span class="rcp_separator">&nbsp;-&nbsp;</span>
                                    <span class="rcp_level_duration">1&nbsp;Month</span>
                                    <div class="rcp_level_description"> </div>
                                </label>

                            </li>
                            <li class="rcp_subscription_level rcp_subscription_level_2">
                                <input type="radio" id="rcp_subscription_level_2" class="required rcp_level" name="rcp_level" rel="100" value="2">
                                <label for="rcp_subscription_level_2">
                                    <span class="rcp_subscription_level_name">Yearly Plan</span><span class="rcp_separator">&nbsp;-&nbsp;</span><span class="rcp_price" rel="100">$100.00</span><span class="rcp_separator">&nbsp;-&nbsp;</span>
                                    <span class="rcp_level_duration">1&nbsp;Year</span>
                                    <div class="rcp_level_description"> </div>
                                </label>

                            </li>
                        </ul>
                    </fieldset>


                    <div class="rcp_registration_total">
                        <table class="rcp_registration_total_details rcp-table">

                            <thead>

                            <tr>
                                <th scope="col">Membership</th>
                                <th scope="col">Amount</th>
                            </tr>

                            </thead>

                            <tbody style="vertical-align: top;">

                            <tr>
                                <td data-th="Membership">Monthly Plan</td>
                                <td data-th="Amount">$10.00</td>
                            </tr>


                            </tbody>

                            <tfoot>

                            <tr class="rcp-total">
                                <th scope="row">Total Today</th>
                                <td data-th="Total Today">$10.00</td>
                            </tr>

                            <tr class="rcp-recurring-total">
                                <th scope="row">Total Recurring Per Month</th>
                                <td data-th="Total Recurring Per Month">$10.00/Month</td>
                            </tr>

                            <tr class="rcp-renewal-date">
                                <th scope="row">Next Renewal Due</th>
                                <td data-th="Next Renewal Due">December 18, 2021</td>
                            </tr>

                            </tfoot>
                        </table>
                    </div>

                    <div class="rcp_gateway_fields" style="">
                        <input type="hidden" name="rcp_gateway" value="stripe" data-supports-recurring="yes" data-supports-trial="yes">
                    </div><div class="rcp_gateway_stripe_fields" id="rcp_gateway_extra_fields">			<div id="rcp-sandbox-gateway-test-cards">

                </div>

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



                    <input type="hidden" id="rcp-registration-type" name="registration_type" value="">
                    <input type="hidden" id="rcp-membership-id" name="membership_id" value="0">
                    <input type="hidden" id="rcp-payment-id" name="rcp_registration_payment_id" value="0">

                    <p id="rcp_submit_wrap">
                        <input type="hidden" name="rcp_register_nonce" value="3e6760d618">
                        <input type="submit" name="rcp_submit_registration" id="rcp_submit" class="rcp-button" value="Register">
                    </p>
                </form>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                headers: [
                    {
                        text: 'Dessert (100g serving)',
                        align: 'start',
                        sortable: false,
                        value: 'name',
                    },
                    { text: 'Calories', value: 'calories' },
                    { text: 'Fat (g)', value: 'fat' },
                    { text: 'Carbs (g)', value: 'carbs' },
                    { text: 'Protein (g)', value: 'protein' },
                    { text: 'Iron (%)', value: 'iron' },
                ],
                desserts: [
                    {
                        name: 'Frozen Yogurt',
                        calories: 159,
                        fat: 6.0,
                        carbs: 24,
                        protein: 4.0,
                        iron: '1%',
                    },
                    {
                        name: 'Ice cream sandwich',
                        calories: 237,
                        fat: 9.0,
                        carbs: 37,
                        protein: 4.3,
                        iron: '1%',
                    },
                    {
                        name: 'Eclair',
                        calories: 262,
                        fat: 16.0,
                        carbs: 23,
                        protein: 6.0,
                        iron: '7%',
                    },
                    {
                        name: 'Cupcake',
                        calories: 305,
                        fat: 3.7,
                        carbs: 67,
                        protein: 4.3,
                        iron: '8%',
                    },
                    {
                        name: 'Gingerbread',
                        calories: 356,
                        fat: 16.0,
                        carbs: 49,
                        protein: 3.9,
                        iron: '16%',
                    },
                    {
                        name: 'Jelly bean',
                        calories: 375,
                        fat: 0.0,
                        carbs: 94,
                        protein: 0.0,
                        iron: '0%',
                    },
                    {
                        name: 'Lollipop',
                        calories: 392,
                        fat: 0.2,
                        carbs: 98,
                        protein: 0,
                        iron: '2%',
                    },
                    {
                        name: 'Honeycomb',
                        calories: 408,
                        fat: 3.2,
                        carbs: 87,
                        protein: 6.5,
                        iron: '45%',
                    },
                    {
                        name: 'Donut',
                        calories: 452,
                        fat: 25.0,
                        carbs: 51,
                        protein: 4.9,
                        iron: '22%',
                    },
                    {
                        name: 'KitKat',
                        calories: 518,
                        fat: 26.0,
                        carbs: 65,
                        protein: 7,
                        iron: '6%',
                    },
                ],
            }
        },
    }
</script>

<!--<script>
  const axios = require('axios');
  import $ from 'jquery';


  export default {
    components: {
    },
    data () {
      return {
        items: [
          ['mdi-shield-plus', 'New Template','new-template'],
          ['mdi-shield', 'Badges','badges'],
          ['mdi-cogs', 'Templates','templates'],

        ],
      }
    },
    mounted() {

    },
    methods:{
      loadDefaults(){
        $('#vwp-plugin-loading').css('display','none');
      },

      goToWordpress(){
        window.location.replace("/wp-admin");
      },
    },
    created() {
       this.loadDefaults();
    },

  }
</script>-->

<style>

</style>
