<!doctype html>
<html amp>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel='stylesheet' id='googleFonts-css'  href='https://fonts.googleapis.com/css?family=Roboto%3A400%2C700%2C300italic%2C400italic%2C700italic%7CLibre+Baskerville%3A400%2C700%2C400italic' type='text/css' media='all' />
	<?php do_action( 'amp_post_template_head', $this ); ?>

	<style amp-custom>
	<?php $this->load_parts( array( 'style' ) ); ?>
	<?php do_action( 'amp_post_template_css', $this ); ?>
	</style>
</head>
<body>
<nav class="amp-wp-title-bar">
	<div>
		<a href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
			<?php $site_icon_url = $this->get( 'site_icon_url' ); ?>
			<?php if ( $site_icon_url ) : ?>
				<amp-img src="<?php echo esc_url( $site_icon_url ); ?>" width="200" height="25" class="amp-wp-site-icon"></amp-img>
			<?php endif; ?>
			<?php echo esc_html( $this->get( 'blog_name' ) ); ?>
		</a>
	</div>
</nav>
<div class="amp-wp-header">
	<div class="amp-header-overlay">
		<div class="amp-article-header">
			<h1 class="amp-wp-title"><?php echo esc_html( $this->get( 'post_title' ) ); ?></h1>
			<p class="amp-wp-meta">
				<?php $this->load_parts( apply_filters( 'amp_post_template_meta_parts', array( 'meta-aut', 'meta-time', 'meta-taxonomy' ) ) ); ?>
			</p>
		</div>
	</div>
	<?php lib_amp_featured_img() ?>
</div>
<div class="amp-wp-content">
	<?php echo $this->get( 'post_amp_content' ); // amphtml content; no kses ?>
</div>
<?php do_action( 'amp_post_template_footer', $this ); ?>
</body>
</html>
