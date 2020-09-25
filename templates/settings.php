<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h4>Memd Integration Setting</h4>
           <?php echo strlen($this->memd->resolveToken(null)) > 400 ? '<span class="badge badge-pill badge-success">Connection Success</span>' : '<span class="badge badge-pill badge-danger">Connection failure</span>'; ?>
            <hr>
            <form action="" method="post" >
                <div class="form-group">
                    <label for="memd_base_uri">Base URI</label>
                    <input value="<?php echo (get_option('memd_uri') !='' && get_option('memd_uri') != null) ? get_option('memd_uri') :'' ?>" name="memd_base_uri" required type="text" class="form-control" id="memd_base_uri" placeholder="">
                </div>
                <div class="form-group">
                    <label for="memd_client_id">Client ID</label>
                    <input value="<?php echo (get_option('memd_client_id') !='' && get_option('memd_client_id') != null) ? get_option('memd_client_id') :'' ?>" name="memd_client_id" required  type="text" class="form-control" id="memd_client_id" placeholder="">
                </div>
                <div class="form-group">
                    <label for="memd_client_secret">Client Secret</label>
                    <input value="<?php echo (get_option('memd_client_secret') !='' && get_option('memd_client_secret') != null) ? get_option('memd_client_secret') :'' ?>" name="memd_client_secret" required  type="text" class="form-control" id="memd_client_secret" placeholder="">
                </div>

                <div class="form-group">
                    <label for="memd_user">User</label>
                    <input value="<?php echo (get_option('memd_user') !='' && get_option('memd_user') != null) ? get_option('memd_user') :'' ?>" name="memd_user" required  type="text" class="form-control" id="memd_user" placeholder="">
                </div>

                <div class="form-group">
                    <label for="memd_password">Password</label>
                    <input value="<?php echo (get_option('memd_password') !='' && get_option('memd_password') != null) ? get_option('memd_password') :'' ?>" name="memd_password" required  type="text" class="form-control" id="memd_password" placeholder="">
                </div>

                <div class="modal-footer">
                    <button name="memd_update_settings" type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php

    if(isset($_POST['memd_update_settings'])){
        update_option('memd_uri',$_POST['memd_base_uri']);
        update_option('memd_client_id',$_POST['memd_client_id']);
        update_option('memd_client_secret',$_POST['memd_client_secret']);
        update_option('memd_user',$_POST['memd_user']);
        update_option('memd_password',$_POST['memd_password']);

        echo("<script>location.href = '".$_SERVER['HTTP_REFERER']."'</script>");

    }


?>
