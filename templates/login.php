<?php
if(!isset($_POST['cbf_create_user'])){
    $memberships = rcp_get_memberships( array(
        'status' => 'active'
    ) );
    var_dump($memberships);
    ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="cbf_fullname_registration">Full Name</label>
            <input name="cbf_fullname_registration" type="text" class="form-control" id="cbf_fullname_registration" placeholder="Full Name" required>
        </div>
        <div class="form-group">
            <label for="cbf_email_registration">Email address</label>
            <input name="cbf_email_registration" type="email" class="form-control" id="cbf_email_registration" placeholder="Enter email" required>
        </div>
        <div class="form-group">
            <label for="cbf_type_registration">Account Type</label>
            <select id="inputState" class="form-control">
                <option selected>Personal</option>
                <option>Business</option>
                <option>Fundraiser</option>

            </select>
        </div>
        <div class="form-group">
            <label for="cbf_pasword_registration">Password</label>
            <input name="cbf_pasword_registration" type="password" class="form-control" id="cbf_pasword_registration" placeholder="Password" required>
        </div>
        <!--<div class="form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div>-->
        <button type="submit" class="btn btn-primary" name="cbf_create_user">Submit</button>
    </form>
    <?php
}else{

    ?>
    <div class="alert alert-success" role="alert">
        Thanks for registering <?= $_POST['cbf_fullname_registration'] ?>
    </div>
    <?php
}
?>