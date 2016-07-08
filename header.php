<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title><?php wp_title('-', true, "right"); ?></title>

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<?php  // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_template_directory_uri(); ?>/favicons/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_template_directory_uri(); ?>/favicons/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/favicons/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/favicons/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/favicons//apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/favicons//apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/favicons//apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/favicons//apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/favicons//apple-touch-icon-180x180.png">
		<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicons//favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicons//favicon-194x194.png" sizes="194x194">
		<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicons//favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicons//android-chrome-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicons//favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/favicons//manifest.json">
		<link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/favicons//safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/favicons//mstile-144x144.png">
		<meta name="theme-color" content="#ffffff">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>

		<?php // drop Google Analytics Here ?>
		<?php // end analytics ?>

	</head>

	<body <?php body_class(); ?>>

		<?php print_adv("skin"); ?>

		<div id="container">

			<header id="header" class="header" role="banner">
				<div id="site-header">
					<div id="inner-header" class="wrap cf">

						<span id="menu-open"><i class="fa fa-bars"></i></span>
						<?php // to use a image just replace the bloginfo('name') with your img src and remove the surrounding <p> ?>
						<h2 class="mobile-title"><a href="<?php echo home_url(); ?>" title="LIBERNAZIONE" class="logo dark-logo" rel="nofollow">
							<img src="http://libernazione.it/wp-content/themes/lib-bones/library/images/liblogowhite.png" />
						</a></h2>
						<a href="<?php echo home_url(); ?>" title="LIBERNAZIONE" class="logo light-logo" rel="nofollow">
							<img src="http://libernazione.it/wp-content/uploads/2014/12/logolibern.png" />
						</a>
						<span class="header-tagline"><?php bloginfo('description'); ?></span>

					</div>
				</div>

				<?php print_adv("strip"); ?>

				<nav role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
					<?php wp_nav_menu(array(
				         'container' => false,                           // remove nav container
				         'container_class' => 'menu cf',                 // class of container (should you choose to use it)
				         'menu' => __( 'Menu Principale', 'bonestheme' ),  // nav name
				         'menu_class' => 'nav top-nav cf',               // adding custom nav class
				         'theme_location' => 'main-nav',                 // where it's located in the theme
				         'before' => '',                                 // before the menu
			               'after' => '',                                  // after the menu
			               'link_before' => '',                            // before each link
			               'link_after' => '',                             // after each link
			               'depth' => 0,                                   // limit the depth of the nav
				         'fallback_cb' => ''                             // fallback function (if there is one)
					)); ?>

				</nav>

			</header>

			<?php print_adv("top-box"); ?>
