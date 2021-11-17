<?php


namespace Cbf\Inc\Base;


class Shortcodes
{
    public function register(){
        add_shortcode( 'cbf_login', array($this, 'login') );
        add_shortcode( 'acf_example', array($this, 'acf_example') );
    }

    public function login($atts){
        $output = memd_template(CBF_PLUGIN_PATH . 'templates/login.php' , array());
        return $output;
    }

    public function acf_example(){
        $row = [
            [
                'name' => 'UU',
                'quantity'   => '2',
                'unit'  => 'oz'
            ],[
                'name' => 'YYUUUY',
                'quantity'   => '2',
                'unit'  => 'oz'
            ]
        ];

        $existing = get_field( 'cbf_ingredients',50 );
        if ( ! is_array($existing) ) $existing = [];
        $updated = array_merge($row, $existing);

        update_field( 'cbf_ingredients', $updated,50);
        return 'Yeah';
    }

    public function dashboard($atts){

        global $current_screen;
        global $rcp_levels_db;
        if ( ! is_admin()) {

            $user = wp_get_current_user();
            $member_external_id = get_user_meta($user->id, 'rcp_external_member_id', true);
            $output = '';

            if($member_external_id == ''){
                return 'The user has an account in wp but no in memd, copy this message and contact us';
            }
            $member = $this->memd->getMember($member_external_id);

            $plan = rcp_get_subscription( $user->id );
            $flag = false;

            $subscription_id = rcp_get_subscription_id($user->id);

            $membership_code = $rcp_levels_db->get_meta( $subscription_id, 'memd_plan_code')[0];

            if(!empty($member['externalsubcriberid']) && $member['externalsubcriberid'] != '' ){
                $userAndMemberId = explode( '__',$member['externalsubcriberid'] );
                $userMain = get_user_by('login', $userAndMemberId[0]);

                if($userMain){
                    $plan = rcp_get_subscription( $userMain->id );
                    $flag = true;
                    $member['policies'] = $this->memd->getMemberPolicy($member['externalsubcriberid'] );
                }else{
                    $member['link'] = $this->memd->memd_visit_link($member['id']);
                }
            }
            $member['link'] = $this->memd->memd_visit_link($member['id']);
            foreach ($member['dependents'] as $dependent){

                $date1 = strtotime(memd_format_date($dependent['dob'] . ' +1 second'));
                // Declare and define two dates
                $date2 = strtotime(memd_format_date('+1 second'));


                $diff = abs($date2 - $date1);

                $years = floor($diff / (365*60*60*24));

                $userDependentWp = explode( '__',$dependent['externalID'] );
                $userDependentWp = get_user_by('login', $userDependentWp[0]);
                if($years > 18 && empty(get_user_meta($userDependentWp->id,'memd_check_18_years', true ))){
                    if($userDependentWp){
                        memd_send_password_reset_mail($userDependentWp->id);
                        update_user_meta( $userDependentWp->id, 'memd_check_18_years',1) ;
                        $userDependentWp->remove_role('dependent_child');
                        $userDependentWp->add_role('dependent_child');
                    }
                }

            }
            $member['isDependent'] = $flag;
            $output .= memd_template(MEMD_PLUGIN_PATH . 'templates/dashboard.php' , array('member' => $member, 'user' => $user , 'self' => $this->memd , 'memberExternalId' => $member_external_id, 'plan' => $plan, 'membership_code' => $membership_code));
            return $output;
        }

    }
}