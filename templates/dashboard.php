<?php
/**
 * Header for the Vue App
 *
 */

$upgrading = isset($_GET['registration_type']) && $_GET['registration_type'] == 'upgrade';
global $post;
$post_slug = $post->post_name;
if(is_user_logged_in()){
    $user_data = cbf_get_user_info();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php $current_user = wp_get_current_user(); ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-5 " id="cbf_dashboard_nav">
        <a class="navbar-brand " href="<?= site_url('welcome') ?>"><img style="width: 150px" src="https://cookbook.nextsitehosting.com/wp-content/uploads/2021/12/Logo.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

            </ul>
            <form class="form-inline my-2 my-lg-0">
                <ul class="navbar-nav mt-2 mt-lg-0 left-bar">
                    <a class="nav-link dropdown-toggle account-menu" href="#" id="create_action_dropmenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Create</a>
                    <div class="dropdown-menu" aria-labelledby="create_action_dropmenu">
                    <?php if($user_data['premium']){?><a class="dropdown-item" href="<?= site_url('welcome/?screen=add-cookbook') ?>">A Cookbook</a><?php } ?>
                        <a class="dropdown-item" href="<?= site_url('welcome/?screen=add-recipe') ?>">A Recipe</a>			            
                    </div>
                    <a class="nav-link" href="<?= site_url('search-recipe') ?>">Browse Recipe</a>
                    <!--<a class="nav-link" href="<?/*= site_url('welcome') */?>">My Recipes</a>
                    <a class="nav-link" href="<?/*= site_url('welcome') */?>">My Cookbooks</a>-->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle account-menu" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo CBF_PLUGIN_URL . 'assets/images/user.png' ?>" alt=""> <?php echo $current_user->user_firstname ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
				            <?php if($user_data['account_type'] == CBF_OWNER_ACCOUNT){ ?>
                                <a class="dropdown-item" href="<?= site_url('welcome/?screen=collaborators') ?>">Collaborators</a>
                                <a class="dropdown-item" href="<?= site_url('register/your-membership/') ?>">Your Account</a>
				            <?php } ?>
                            <a class="dropdown-item" href="#"></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= site_url('/wp-login.php?action=logout') ?>">Sign out</a>
                        </div>

                    </li>
                </ul>
            </form>
        </div>
    </nav>
<?php }else{

    if($post_slug !== 'register'){
        wp_redirect(site_url('/login'));
        die();
    }else{
        get_header();
    }


} ?>
<div class=" main">
    <?php
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
    ?>
</div>

<?php
get_footer();
