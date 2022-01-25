<?php


namespace Cbf\Inc\Base;

use Cbf\Inc\Services\HubspotService;

class Registration
{

    function __construct()
    {

    }

    public function register(){
        //Add Extra Fields to Registration Form
      //  add_action( 'rcp_after_password_registration_field', array($this,'cbf_add_fields') );
        add_action( 'rcp_profile_editor_after', array($this,'cbf_add_fields') );

        //Remove the username mandatory
        add_filter( 'rcp_user_registration_data', array($this, 'cbf_remove_username') );

        //Adding the edit customer screen fields
        add_action( 'rcp_edit_member_after', array($this,'cbf_rcp_add_member_edit_fields') );

        //Saving member after registration
        add_action( 'rcp_form_processing', array($this,'cbf_rcp_save_user_fields_on_register'), 10, 2 );

        //Saving fields after member edit and profile edit
        add_action( 'rcp_user_profile_updated', array($this,'cbf_rcp_save_user_fields_on_profile_save'), 10 );
 	    add_action( 'rcp_edit_member', array($this,'cbf_rcp_save_user_fields_on_profile_save'), 10 );

 	    //After Successfully registration
        //add_action( 'rcp_successful_registration', array($this, 'memd_rcp_after_registration'), 10, 3 );

        //Add/Edit a new field to membership level
       // add_action( 'rcp_edit_subscription_form', array($this, 'memd_edit_fields_to_membership_level') );
       // add_action( 'rcp_edit_subscription_level', array($this, 'memd_save_in_edit_fields_to_membership_level') );

       // add_action( 'rcp_add_subscription_form', array($this, 'memd_add_add_fields_to_membership_level') );
        //add_action( 'rcp_add_subscription', array($this, 'memd_add_save_fields_to_membership_level') );

        //Canceling a memebership
        //add_filter( 'rcp_transition_membership_status_cancelled', array($this, 'memd_terminate_policy'), 10, 5 );
        //Activating the account
        //add_action( 'rcp_transition_membership_status_active', array($this, 'memd_activating_policy') );
    }

    /**
     * Removing username as a mandatory field
     */
    function cbf_remove_username( $user ) {
        rcp_errors()->remove( 'username_empty' );
        $user['login'] = $user['email'];
        return $user;
    }

    /**
     * Add new fields to registration form and update profile
     */
    public function cbf_add_fields($user_id = 0){
        $type = get_user_meta($user_id, 'rcp_type', true)
        ?>

        <p>
            <input name="rcp_type" type="radio" <?php echo count($type) > 0 && $type == 'Personal' ? 'checked' : '' ?>/>
            <label for="rcp_type"><?php _e( 'Personal', 'rcp' ); ?></label>
            <input name="rcp_type" type="radio" <?php echo count($type) > 0 && $type == 'Business' ? 'checked' : '' ?>/>
            <label for="rcp_type"><?php _e( 'Business', 'rcp' ); ?></label>
            <input name="rcp_type" type="radio" <?php echo count($type) > 0 && $type == 'Fundraiser' ? 'checked' : '' ?>/>
            <label for="rcp_type"><?php _e( 'Fundraiser', 'rcp' ); ?></label>
        </p>
        <?php
    }

    /**
     * @param int $user_id
     * @description Adding the edit customer screen fields
     */
    function cbf_rcp_add_member_edit_fields($user_id = 0){
        $type = get_user_meta( $user_id, 'rcp_type', true );

        ?>
        <tr valign="top">
            <th scope="row" valign="top">
                <label for="rcp_type"><?php _e( 'Type', 'rcp' ); ?></label>
            </th>
            <td>
                <input name="rcp_type" id="rcp_type" type="text" value="<?php echo esc_attr( $type ); ?>"/>
                <p class="description"><?php _e( 'The member\'s type', 'rcp' ); ?></p>
            </td>
        </tr>


        <?php
    }

    /**
     * @param $posted
     * @param $user_id
     * @description Save fields on register form
     */
    function cbf_rcp_save_user_fields_on_register($posted, $user_id){
        if( ! empty( $posted['rcp_type'] ) ) {
            update_user_meta( $user_id, 'rcp_type', sanitize_text_field( $posted['rcp_type'] ) );
        }

	    /**
	     * Save user into hubspot
	     */

	    /**
	     * @todo check if the hubspot plugin installed first,
         * also we need to create an ui to choose the list and store the api key
	     */
	    $hubspot = new HubspotService();
	    $email = $_POST['rcp_user_email'];
	    $hubspot->createContact($email,$_POST['rcp_user_first'],$_POST['rcp_user_last']);

	    if(isset($_POST['rcp_is_premium']) && $_POST['rcp_is_premium'] == 0){
            $hubspot->addContactToList($email,1);
	    }else{
		    $hubspot->addContactToList($email,2);
	    }
    }

	/**
     * Save info when the use is registered
	 * @param $user_id
	 */
    function cbf_rcp_save_user_fields_on_profile_save( $user_id ) {

        if( ! empty( $_POST['rcp_type'] ) ) {
            update_user_meta( $user_id, 'rcp_type', sanitize_text_field( $_POST['rcp_type'] ) );
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
            <td>s
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

    function memd_terminate_policy(){
            $user = wp_get_current_user();
            $member_external_id = get_user_meta($user->id, 'rcp_external_member_id', true);
            $this->memd->terminatePolicy($member_external_id, 'YUFC5AW6', memd_format_date(rcp_get_expiration_date($user->id)));
    }

    function memd_activating_policy(){
        $user = wp_get_current_user();
        $member_external_id = get_user_meta($user->id, 'rcp_external_member_id', true);
        if(strlen($member_external_id )> 0){


            $this->memd->createPolicy($member_external_id, 'YUFC5AW6', memd_format_date('+1 second'), memd_format_date('+5 years'));
        }

    }

}