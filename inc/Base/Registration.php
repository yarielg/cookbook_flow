<?php


namespace Memd\Inc\Base;

use Memd\Inc\Services\MemdService;

class Registration
{

    protected $memd;

    function __construct()
    {
        $this->memd = new MemdService();
    }

    public function register(){
        //Add Extra Fields to Registration Form
        add_action( 'rcp_after_password_registration_field', array($this,'memd_add_fields') );
        add_action( 'rcp_profile_editor_after', array($this,'memd_add_fields') );

        //Adding the edit customer screen fields
        add_action( 'rcp_edit_member_after', array($this,'memd_rcp_add_member_edit_fields') );

        //Saving member after registration
        add_action( 'rcp_form_processing', array($this,'memd_rcp_save_user_fields_on_register'), 10, 2 );

        //Saving fields after member edit and profile edit
        add_action( 'rcp_user_profile_updated', array($this,'memd_rcp_save_user_fields_on_profile_save'), 10 );
 	    add_action( 'rcp_edit_member', array($this,'memd_rcp_save_user_fields_on_profile_save'), 10 );

 	    //After Successfully registration
        add_action( 'rcp_successful_registration', array($this, 'memd_rcp_after_registration'), 10, 3 );

        //Add/Edit a new field to membership level
        add_action( 'rcp_edit_subscription_form', array($this, 'memd_edit_fields_to_membership_level') );
        add_action( 'rcp_edit_subscription_level', array($this, 'memd_save_in_edit_fields_to_membership_level') );

        add_action( 'rcp_add_subscription_form', array($this, 'memd_add_add_fields_to_membership_level') );
        add_action( 'rcp_add_subscription', array($this, 'memd_add_save_fields_to_membership_level') );
    }

    public function memd_add_fields(){

        $phone  = get_user_meta( get_current_user_id(), 'rcp_phone', true );
        $dob = get_user_meta( get_current_user_id(), 'rcp_dob', true );
        $gender = get_user_meta( get_current_user_id(), 'rcp_gender', true );
        $city = get_user_meta( get_current_user_id(), 'rcp_city', true );
        $address = get_user_meta( get_current_user_id(), 'rcp_address', true );
        $state = get_user_meta( get_current_user_id(), 'rcp_state', true );
        $zipcode = get_user_meta( get_current_user_id(), 'rcp_zipcode', true );

        ?>
        <p xmlns="http://www.w3.org/1999/html">
            <label for="rcp_phone"><?php _e( 'Your Phone', 'rcp' ); ?></label>
            <input name="rcp_phone" id="rcp_phone" type="text" value="<?php echo esc_attr( $phone ); ?>"/>
        </p>
        <p>
            <label for="rcp_dob"><?php _e( 'Your Birthday', 'rcp' ); ?></label>
            <input name="rcp_dob" id="rcp_dob" type="date" min="1940-01-01" value="<?php echo esc_attr( $dob ); ?>"/>
        </p>
        <p>
            <label for="rcp_gender"><?php _e( 'Your Gender', 'rcp' ); ?></label>
            <select name="rcp_gender" id="rcp_gender"  value="<?php echo esc_attr( $gender ); ?>"/>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select>
        </p>

        <p>
            <label for="rcp_address"><?php _e( 'Your Address', 'rcp' ); ?></label>
            <input name="rcp_address" id="rcp_address" type="text" value="<?php echo esc_attr( $address); ?>"/>
        </p>
        <p>
            <label for="rcp_city"><?php _e( 'Your City', 'rcp' ); ?></label>
            <input name="rcp_city" id="rcp_city" type="text" value="<?php echo esc_attr( $city ); ?>"/>
        </p>
        <p>
            <label for="rcp_state"><?php _e( 'Your State', 'rcp' ); ?></label>
            <input name="rcp_state" id="rcp_state" type="text" value="<?php echo esc_attr( $state ); ?>"/>
        </p>
        <p>
            <label for="rcp_zipcode"><?php _e( 'Your Zipcode', 'rcp' ); ?></label>
            <input name="rcp_zipcode" id="rcp_zipcode" type="text" value="<?php echo esc_attr( $zipcode ); ?>"/>
        </p>

        <?php
    }

    function memd_rcp_add_member_edit_fields($user_id = 0){
        $phone  = get_user_meta( get_current_user_id(), 'rcp_phone', true );
        $dob = get_user_meta( get_current_user_id(), 'rcp_dob', true );
        $gender = get_user_meta( get_current_user_id(), 'rcp_gender', true );
        $city = get_user_meta( get_current_user_id(), 'rcp_city', true );
        $address = get_user_meta( get_current_user_id(), 'rcp_address', true );
        $state = get_user_meta( get_current_user_id(), 'rcp_state', true );
        $zipcode = get_user_meta( get_current_user_id(), 'rcp_zipcode', true );

        ?>
        <tr valign="top">
            <th scope="row" valign="top">
                <label for="rcp_phone"><?php _e( 'Phone', 'rcp' ); ?></label>
            </th>
            <td>
                <input name="rcp_phone" id="rcp_phone" type="text" value="<?php echo esc_attr( $phone ); ?>"/>
                <p class="description"><?php _e( 'The member\'s phone', 'rcp' ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" valign="top">
                <label for="rcp_dob"><?php _e( 'Birthday', 'rcp' ); ?></label>
            </th>
            <td>
                <input name="rcp_dob" id="rcp_dob" type="text" value="<?php echo esc_attr( $dob ); ?>"/>
                <p class="description"><?php _e( 'The member\'s birthday', 'rcp' ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" valign="top">
                <label for="rcp_gender"><?php _e( 'Gender', 'rcp' ); ?></label>
            </th>
            <td>
                <input name="rcp_gender" id="rcp_gender" type="text" value="<?php echo esc_attr( $gender ); ?>"/>
                <p class="description"><?php _e( 'The member\'s gender', 'rcp' ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" valign="top">
                <label for="rcp_city"><?php _e( 'City', 'rcp' ); ?></label>
            </th>
            <td>
                <input name="rcp_city" id="rcp_city" type="text" value="<?php echo esc_attr( $city ); ?>"/>
                <p class="description"><?php _e( 'The member\'s city', 'rcp' ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" valign="top">
                <label for="rcp_address"><?php _e( 'Address', 'rcp' ); ?></label>
            </th>
            <td>
                <input name="rcp_address" id="rcp_address" type="text" value="<?php echo esc_attr( $address ); ?>"/>
                <p class="description"><?php _e( 'The member\'s address', 'rcp' ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" valign="top">
                <label for="rcp_state"><?php _e( 'State', 'rcp' ); ?></label>
            </th>
            <td>
                <input name="rcp_state" id="rcp_state" type="text" value="<?php echo esc_attr( $state ); ?>"/>
                <p class="description"><?php _e( 'The member\'s state', 'rcp' ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" valign="top">
                <label for="rcp_zipcode"><?php _e( 'Zipcode', 'rcp' ); ?></label>
            </th>
            <td>
                <input name="rcp_zipcode" id="rcp_zipcode" type="text" value="<?php echo esc_attr( $zipcode ); ?>"/>
                <p class="description"><?php _e( 'The member\'s zipcode', 'rcp' ); ?></p>
            </td>
        </tr>

        <?php
    }

    function memd_rcp_save_user_fields_on_register($posted, $user_id){
        if( ! empty( $posted['rcp_phone'] ) ) {
            update_user_meta( $user_id, 'rcp_phone', sanitize_text_field( $posted['rcp_phone'] ) );
        }

        if( ! empty( $posted['rcp_dob'] ) ) {
            update_user_meta( $user_id, 'rcp_dob', sanitize_text_field( $posted['rcp_dob'] ) );
        }

        if( ! empty( $posted['rcp_gender'] ) ) {
            update_user_meta( $user_id, 'rcp_gender', sanitize_text_field( $posted['rcp_gender'] ) );
        }

        if( ! empty( $posted['rcp_city'] ) ) {
            update_user_meta( $user_id, 'rcp_city', sanitize_text_field( $posted['rcp_city'] ) );
        }

        if( ! empty( $posted['rcp_address'] ) ) {
            update_user_meta( $user_id, 'rcp_address', sanitize_text_field( $posted['rcp_address'] ) );
        }

        if( ! empty( $posted['rcp_state'] ) ) {
            update_user_meta( $user_id, 'rcp_state', sanitize_text_field( $posted['rcp_state'] ) );
        }

        if( ! empty( $posted['rcp_zipcode'] ) ) {
            update_user_meta( $user_id, 'rcp_zipcode', sanitize_text_field( $posted['rcp_zipcode'] ) );
        }

        $user = get_user_by( 'id', $user_id );
        $member_id = memd_generate_rand_id();
        $member_external_id = $user->user_login  . '__' .  $member_id ;

        update_user_meta( $user_id, 'rcp_external_member_id', sanitize_text_field( $member_external_id ) );

        $member = array();
        $member['externalID'] = $member_external_id;
        $member['memberID'] = $member_id;
        $member['First'] = $user->user_firstname;
        $member['Middle'] = '';
        $member['Last'] = $user->user_lastname;
        $member['email'] = $user->user_email;
        $member['phone'] = $posted['rcp_phone'];
        $member['dob'] = memd_format_date($posted['rcp_dob']);
        $member['gender'] = $posted['rcp_gender'];
        $member['address1'] = $posted['rcp_address'];
        $member['city'] = $posted['rcp_city'];
        $member['state'] = $posted['rcp_state'];
        $member['zipCode'] = $posted['rcp_zipcode'];
        $member['benefitstart'] = memd_format_date('+1 second');
        $member['benefitend'] = memd_format_date('+5 years');

        $this->memd->createMember( $member );

    }

    function memd_rcp_save_user_fields_on_profile_save( $user_id ) {

        if( ! empty( $_POST['rcp_phone'] ) ) {
            update_user_meta( $user_id, 'rcp_phone', sanitize_text_field( $_POST['rcp_phone'] ) );
        }

        if( ! empty( $_POST['rcp_dob'] ) ) {
            update_user_meta( $user_id, 'rcp_dob', sanitize_text_field( $_POST['rcp_dob'] ) );
        }

        if( ! empty( $_POST['rcp_gender'] ) ) {
            update_user_meta( $user_id, 'rcp_gender', sanitize_text_field( $_POST['rcp_gender'] ) );
        }

        if( ! empty( $_POST['rcp_city'] ) ) {
            update_user_meta( $user_id, 'rcp_city', sanitize_text_field( $_POST['rcp_city'] ) );
        }

        if( ! empty( $_POST['rcp_address'] ) ) {
            update_user_meta( $user_id, 'rcp_address', sanitize_text_field( $_POST['rcp_address'] ) );
        }

        if( ! empty( $_POST['rcp_state'] ) ) {
            update_user_meta( $user_id, 'rcp_state', sanitize_text_field( $_POST['rcp_state'] ) );
        }

        if( ! empty( $_POST['rcp_zipcode'] ) ) {
            update_user_meta( $user_id, 'rcp_zipcode', sanitize_text_field( $_POST['rcp_zipcode'] ) );
        }

        $user = get_user_by( 'id', $user_id );

        $member_external_id = get_user_meta($user_id, 'rcp_external_member_id', true);


        if(!memd_is_dependent($member_external_id, $this->memd)){
            $member = array();
            $member['First'] = $user->user_firstname;
            $member['Last'] = $user->user_lastname;
            $member['email'] = $user->user_email;
            $member['phone'] = $_POST['rcp_phone'];
            $member['dob'] = memd_format_date($_POST['rcp_dob']);
            $member['gender'] = $_POST['rcp_gender'];
            $member['address1'] = $_POST['rcp_address'];
            $member['city'] = $_POST['rcp_city'];
            $member['state'] = $_POST['rcp_state'];
            $member['zipCode'] = $_POST['rcp_zipcode'];

            $this->memd->updatePrimaryMember( $member, $member_external_id );
        }

    }

    public function memd_rcp_after_registration($member, $customer, $membership){
        // Array of level IDs we want to execute code for.
        $membership_levels_to_check = array( 1, 2, 3 );  // Change these ID numbers.

        // Bail if they don't have one of these level IDs.
        if ( ! in_array( $membership->get_object_id(), $membership_levels_to_check ) ) {
            return;
        }

        // Send an extra follow up email.
        $subject = __( 'Requesting extra information', 'rcp' );
        $message = __( 'Include your email message here.', 'rcp' );

    }

    /**
     *  Used to inject content into the "Edit Membership Level" table. This can be used to add a new field to the "Edit Membership Level Level" form.
     */
    function memd_edit_fields_to_membership_level($level) {
        $plan_code = ( empty( $level->id ) ) ? '' : rcp_get_membership_level_meta( $level->id, 'memd_plan_code',true ); ?>

        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="rcpga-group-plancode"><?php _e( 'Memd Plan Code', 'rcp-group-accounts' ); ?></label>
            </th>
            <td>
                <input id="rcpga-group-plancode" type="text" name="rcpga-group-plancode" value="<?php echo $plan_code;?>"  style="width: 120px;"/>
                <p class="description"><?php _e( 'Add the memd plan code associated with this membership.', 'rcp-group-accounts' ); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * @param $level
     * Used to inject content into the "Add New Level" table. This can be used to add a new field to the "Add New Level" form.
     */
    function memd_add_add_fields_to_membership_level($level) {
        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="rcpga-group-plancode"><?php _e( 'Memd Plan Code', 'rcp-group-accounts' ); ?></label>
            </th>
            <td>
                <input id="rcpga-group-plancode" type="text" name="rcpga-group-plancode" style="width: 120px;"/>
                <p class="description"><?php _e( 'Add the memd plan code associated with this membership.', 'rcp-group-accounts' ); ?></p>
            </td>
        </tr>
        <?php
    }

    function memd_save_in_edit_fields_to_membership_level($level_id){
        global $rcp_levels_db;
        $plan_code= isset($_POST['rcpga-group-plancode']) ? $_POST['rcpga-group-plancode'] : '';
        $rcp_levels_db->update_meta( $level_id, 'memd_plan_code', $plan_code, '');
    //    rcp_update_membership_level_meta( 1, '', serialize($args) );

    }

    function memd_add_save_fields_to_membership_level($level_id){
        global $rcp_levels_db;
        $plan_code= isset($_POST['rcpga-group-plancode']) ? $_POST['rcpga-group-plancode'] : '';
        rcp_update_membership_level_meta( $level_id, 'memd_plan_code', $plan_code);
    }

}