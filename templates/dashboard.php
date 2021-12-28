<?php
/**
 * Header for the Vue App
 *
 */

?><!DOCTYPE html>
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
<nav class="navbar navbar-expand-lg navbar-light bg-light px-5 dashboard-header">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarTogglerDemo01">
        <a class="navbar-brand " href="#"><img src="https://cookbook.nextsitehosting.com/wp-content/uploads/2021/12/Logo.png"></a>
        <ul class="navbar-nav mt-2 mt-lg-0 ">
            <a class="nav-link dropdown-toggle account-menu" href="#" id="create_action_dropmenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Create +
            </a>
            <div class="dropdown-menu" aria-labelledby="create_action_dropmenu">
                <a class="dropdown-item" href="<?= site_url('welcome/?screen=add-recipe') ?>">Create recipe</a>
                <a class="dropdown-item" href="<?= site_url('welcome/?screen=add-cookbook') ?>">Create cookbook</a>
            </div>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle account-menu" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="<?php echo CBF_PLUGIN_URL . 'assets/images/user.png' ?>" alt=""> <?php echo $current_user->user_firstname ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?= site_url('register/your-membership/') ?>">Your Account</a>
                    <a class="dropdown-item" href="#"></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Sign out</a>
                </div>

            </li>
        </ul>

    </div>
</nav>

    <?php
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
    ?>

<?php
get_footer();
