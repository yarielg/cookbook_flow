<div class="container">
    <div class="row">
        <div class="col-12 box-panel text-center">
            <div class="panel-wrapper">
                <h3 class="text-center">Invitation to collaborate</h3>
                <p>You were invited to collaborate on this site, you will have the privileges to create, edit and delete content, however you will not be able to perform any action related to payment or account security.</p>
                <p>Please choose a password to start.</p>
                <br>
                <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                    <input type="hidden" name="action" value="cbf_change_collaborator_password">
                    <input type="hidden" name="cbf_token" value="<?php echo $token ?>">
                    <input type="hidden" name="cbf_collaborator_id" value="<?php echo $collaborator_id ?>">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input readonly required type="text" value="<?php echo $first ?>" name="cbf_first" class="form-control" id="cbf_first" placeholder="First Name">
                        </div>
                        <div class="form-group col-md-6">
                            <input readonly required type="text" value="<?php echo $last ?>" name="cbf_last" class="form-control" id="cbf_last" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <input readonly required type="email" value="<?php echo $email ?>" class="form-control" name="cbf_email" id="cbf_email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input required name="cbf_password" type="password" class="form-control" id="cbf_pass" placeholder="Password">
                    </div>

                    <button type="submit" name="name" class="btn btn-primary">Sign up</button>
                </form>
            </div>
        </div>
    </div>
</div>