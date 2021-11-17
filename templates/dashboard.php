<?php
if(isset($message)){
    echo $message;
}
//if()
?>


<div class="container-fluid" style="width: 100%; margin:  auto;">
    <div class="row">
        <div class="col-md-3 ">
            <?php if(!$member['IsDependent'] && $membership_code != 'individual'){ ?>
            <div class="list-group ">
                <a data-toggle="modal" href="#" data-target="#memd_dependent_modal" class="list-group-item list-group-item-action">Add a Dependent</a>
            </div>
            <br>

            <?php } ?>
            <?php if(!$member['IsDependent']){ ?>
            <div class="list-group ">
                <a  class="list-group-item list-group-item-action" href="<?= $member['link'] ?>" target="_blank">Visit MeMD</a>
            </div>
            <?php } ?>
        </div>
        <div class="col-md-9">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Member Info </h4><?php echo  $member['isDependent'] ? '<span class="badge badge-pill badge-danger right">Dependent</span>' : '<span class="badge badge-pill badge-success right">No Dependent</span>' ?>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <form>
                                    <div class="form-group row">
                                        <label for="username" class="col-4 col-form-label">User Name</label>
                                        <div class="col-8">
                                            <?php echo $user->user_login ?>
                                            <!--<input id="username" name="username" placeholder="Username" class="form-control here" required="required" type="text">-->
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-4 col-form-label">First Name</label>
                                        <div class="col-8">
                                            <?php echo $user->first_name ? $user->first_name : $member['name']['first']   ?>
                                            <!--<input id="name" name="name" placeholder="First Name" class="form-control here" type="text">-->
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="lastname" class="col-4 col-form-label">Last Name</label>
                                        <div class="col-8">
                                            <?php echo $user->last_name ?  $user->last_name : $member['name']['last'] ?>
                                            <!--<input id="lastname" name="lastname" placeholder="Last Name" class="form-control here" type="text">-->
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-4 col-form-label">Email</label>
                                        <div class="col-8">
                                            <?php echo $member['email'] ? $member['email'] : $user->user_email?>
                                            <!--<input id="email" name="email" placeholder="Email" class="form-control here" required="required" type="text">-->
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="publicinfo" class="col-4 col-form-label">Address</label>
                                        <div class="col-8">
                                            <?php echo $member['address']['address1'] . ', ' . $member['address']['city'] . ', ' . $member['address']['state'] . ' ' . $member['address']['zipCode'] ?>
                                            <!--<textarea id="publicinfo" name="publicinfo" cols="40" rows="4" class="form-control"></textarea>-->
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="website" class="col-4 col-form-label">Current Membership</label>
                                        <div class="col-8">
                                            <?php echo $plan  ?>
                                            <!-- <input id="website" name="website" placeholder="website" class="form-control here" type="text">-->
                                        </div>
                                    </div>

                                    <!--<div class="form-group row">
                                        <div class="offset-4 col-8">
                                            <button name="submit" type="submit" class="btn btn-primary">Update My Profile</button>
                                        </div>
                                    </div>-->
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Policy</h4>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <form>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="list-group">
                                                <?php if(!empty($member['policies']) && count($member['policies'])>0){
                                                    foreach ($member['policies'] as $policy){
                                                        if(!$policy['isactive']) continue;
                                                        /* echo $dependent['name']['first'];*/?>

                                                        <div href="#" class="list-group-item list-group-item-action">
                                                            <div class="d-flex w-100 justify-content-between">

                                                                <h5 class="mb-1">Policy ID: <?php echo $policy['policyId'] ?></h5>
                                                                <?php echo $policy['isactive'] ? '<small><span class="badge badge-success right">Active</span></small>' : '<small><span class="badge badge-danger right">Inactive</span></small>'  ?>

                                                            </div>
                                                            <p><small>Copay: </small><?php echo $policy['copay'] ?></p>
                                                            <p><small>Benefit Start: </small><?php echo date("M d, Y", strtotime($policy['benefitstart']))  ?></p>
                                                            <p><small>Benefit End: </small><?php echo date("M d, Y", strtotime($policy['benefitend']))  ?></p>
                                                        </div>
                                                        <?php
                                                    }
                                                }else{
                                                    echo '<p>No Policies</p>';
                                                }
                                                ?>
                                            </div>
                                            <!--<input id="username" name="username" placeholder="Username" class="form-control here" required="required" type="text">-->
                                        </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <?php if(!$member['IsDependent']){ ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Dependents</h4>
                                <hr>
                            </div>
                        </div>
                            <div class="col-md-12">
                                <form>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="list-group">
                                            <?php if( !empty($member['dependents']) && count($member['dependents'])>0){
                                                foreach ($member['dependents'] as $dependent){

                                                   /* echo $dependent['name']['first'];*/?>

                                                        <div href="#" class="list-group-item list-group-item-action">
                                                            <div class="d-flex w-100 justify-content-between">
                                                                <h5 class="mb-1"><?php echo $dependent['name']['first'] . ' ' . $dependent['name']['last'] ?></h5>
                                                                <?php
                                                                switch ($dependent['relationship']){
                                                                    case '19':
                                                                         echo "<small>Child/Dependent </small>";
                                                                         break;
                                                                    case '01':
                                                                        echo "<small>Spouse</small>";
                                                                        break;
                                                                    default:
                                                                        echo "<small>Life Partner </small>";
                                                                }
                                                                ?>
                                                            </div>
                                                            <p class="mb-1"><?php  echo $dependent['address']['address1'] . ', ' . $dependent['address']['city'] . ', ' . $dependent['address']['state'] . ' ' . $dependent['address']['zipCode'] ?> </p>
                                                        </div>
                                                <?php
                                                }
                                            }else{
                                                if($membership_code == 'individual')
                                                    echo '<p>Your Current Plan do not allow to add dependent. If you want to add someone else, please upgrade your Plan</p>';
                                                else
                                                    echo '<p>No Dependents</p>';
                                            }
                                            ?>
                                            </div>
                                            <!--<input id="username" name="username" placeholder="Username" class="form-control here" required="required" type="text">-->
                                        </div>
                                </form>
                            </div>

                    </div>
                </div>
            </div>
    <?php } ?>

        </div>
    </div>
</div>

<!-- Add dependent modal -->
<?php if($membership_code == 'individual'){ ?>
    <div class="modal fade" id="memd_dependent_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        Your are in an individual plan, to add another person to your Plan, upgrade to Family plan or Spouse Plan
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php }else if($membership_code == 'spouse' && count($member['dependents']) > 0){ ?>
    <div class="modal fade" id="memd_dependent_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    This is a Spouse Plan, if you want to add something else, please upgrade to Family Plan
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php }else{?>
    <div class="modal fade" id="memd_dependent_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Dependent</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="?action=add_dependent" method="post" >
                        <div class="form-group">
                            <label for="memd_username">Username</label>
                            <input name="memd_username" required type="text" class="form-control" id="memd_username" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="memd_name">First Name</label>
                            <input name="memd_name" required  type="text" class="form-control" id="memd_last" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="memd_last">Last Name</label>
                            <input name="memd_last" required  type="text" class="form-control" id="memd_last" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="memd_email">Email</label>
                            <input name="memd_email" required type="email" class="form-control" id="memd_email" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="memd_phone">Phone</label>
                            <input name="memd_phone" required type="number" class="form-control" id="memd_phone" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="memd_dob">Birthday</label>
                            <input name="memd_dob" required type="date" class="form-control" id="memd_dob" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="memd_gender" name="memd_gender">Gender</label>
                            <select name="memd_gender" required class="form-control" id="memd_gender">
                                <option value="M">M</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="memd_address">Address</label>
                            <input name="memd_address" required type="text" class="form-control" id="memd_address" placeholder="">
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <input required name="memd_city" type="text" class="form-control" placeholder="City">
                            </div>
                            <div class="col">
                                <input required name="memd_state" type="text" class="form-control" placeholder="State">
                            </div>
                            <div class="col">
                                <input required name="memd_zipcode" type="number" class="form-control" placeholder="Zip">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="memd_relation" >Relationship</label>
                            <select name="memd_relation" required class="form-control" id="memd_relation">
                                <option value="19">Child/Dependent</option>
                                <option value="01">Spouse</option>
                                <option value="53">Life Partner</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="memd_pass">Password</label>
                            <input name="memd_pass" required type="password" class="form-control" id="memd_pass" placeholder="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button name="memd_add_dependent" type="submit" class="btn btn-primary">Add Dependent</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
<?php }?>
<?php
 if(isset($_POST['memd_add_dependent'])){

     $member_id = memd_generate_rand_id();
     $member_external_id = $_POST['memd_username']  . '__' .  $member_id ;
     $memberToAdd = array();
     $memberToAdd['externalID'] = $member_external_id;
     $memberToAdd['memberID'] = $member_id;
     $memberToAdd['First'] = $_POST['memd_name'];
     $memberToAdd['Middle'] = '';
     $memberToAdd['Last'] = $_POST['memd_last'];
     $memberToAdd['email'] = $_POST['memd_email'];
     $memberToAdd['phone'] = $_POST['memd_phone'];
     $memberToAdd['dob'] = memd_format_date($_POST['memd_dob']);
     $memberToAdd['gender'] = $_POST['memd_gender'];
     $memberToAdd['address1'] = $_POST['memd_address'];
     $memberToAdd['city'] = $_POST['memd_city'];
     $memberToAdd['state'] = $_POST['memd_state'];
     $memberToAdd['zipCode'] = $_POST['memd_zipcode'];
     $memberToAdd['relationship'] = $_POST['memd_relation'];

         $new_wp_user_id = wp_create_user( $_POST['memd_username'] , $_POST['memd_pass'] , $_POST['memd_email'] );

        // $self->createDependentMember( $memberToAdd, $memberExternalId );
         $wp_new_user = get_user_by('ID',$new_wp_user_id);

        if($new_wp_user_id > 0){
            $wp_new_user->remove_role('subscriber');
            $self->createDependentMember( $memberToAdd, $memberExternalId );

            update_user_meta( $new_wp_user_id, 'rcp_external_member_id', sanitize_text_field( $member_external_id ) );
            if($_POST['memd_relation'] == 19) { //It's a child, so we wont create wp account until he turned 18.
                    $wp_new_user->add_role('dependent_member_child');
            }else{
                $wp_new_user->add_role('dependent_member');
            }
            //new user meta
            if( ! empty( $_POST['memd_phone'] ) ) {
                update_user_meta( $new_wp_user_id, 'memd_phone', sanitize_text_field( $_POST['memd_phone'] ) );
            }

            if( ! empty( $_POST['rcp_dob'] ) ) {
                update_user_meta( $new_wp_user_id, 'rcp_dob', sanitize_text_field( $_POST['rcp_dob'] ) );
            }

            if( ! empty( $_POST['rcp_gender'] ) ) {
                update_user_meta( $new_wp_user_id, 'rcp_gender', sanitize_text_field( $_POST['rcp_gender'] ) );
            }

            if( ! empty( $_POST['rcp_city'] ) ) {
                update_user_meta( $new_wp_user_id, 'rcp_city', sanitize_text_field( $_POST['rcp_city'] ) );
            }

            if( ! empty( $_POST['rcp_address'] ) ) {
                update_user_meta( $new_wp_user_id, 'rcp_address', sanitize_text_field( $_POST['rcp_address'] ) );
            }

            if( ! empty( $_POST['rcp_state'] ) ) {
                update_user_meta( $new_wp_user_id, 'rcp_state', sanitize_text_field( $_POST['rcp_state'] ) );
            }

            if( ! empty( $_POST['rcp_zipcode'] ) ) {
                update_user_meta( $new_wp_user_id, 'rcp_zipcode', sanitize_text_field( $_POST['rcp_zipcode'] ) );
            }

        }else{
            echo 'No wp added';
        }
     echo("<script>location.href = '".$_SERVER['HTTP_REFERER']."'</script>");
 }
?>