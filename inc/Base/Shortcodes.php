<?php


namespace Memd\Inc\Base;

use Memd\Inc\Services\MemdService;

class Shortcodes
{

    protected $memd;

    function __construct()
    {
        $this->memd = new MemdService();
    }

    public function register(){
        add_shortcode( 'memd_dashboard', array($this, 'dashboard') );
    }

    public function dashboard($atts){
        global $current_screen;
        if ( ! is_admin() ) {

            $user = wp_get_current_user();
            $member_external_id = get_user_meta($user->id, 'rcp_external_member_id', true);
            $output = '';


            if($member_external_id == ''){
                return 'The user has an account in wp but no in memd, copy this message and contact us';

            }

            $member = $this->memd->getMember($member_external_id);

            /*$a = shortcode_atts( array(
                'msg' => 'asdasd'
            ), $atts );*/
            $plan = rcp_get_subscription( $user->id );
            $flag = false;

            if($member['externalsubcriberid'] != '' ){
                $userAndMemberId = explode( '__',$member['externalsubcriberid'] );
                $userMain = get_user_by('login', $userAndMemberId[0]);
                if($userMain){
                    $plan = rcp_get_subscription( $userMain->id );
                    $flag = true;
                }
            }

            $member['isDependent'] = $flag;
            $output .= memd_template(MEMD_PLUGIN_PATH . 'templates/dashboard.php' , array('member' => $member, 'user' => $user , 'self' => $this->memd , 'memberExternalId' => $member_external_id, 'plan' => $plan));
            return $output;
        }

    }
}