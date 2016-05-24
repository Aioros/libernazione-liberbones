<?php

get_header(); 

$images = glob(WP_CONTENT_DIR . '/404/*.*');
$image = array_rand($images);
$image = $images[$image];
$image = substr($image, strpos($image, "/wp-content"));

?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

					<main id="main" class="m-all t-all d-all cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						<article id="post-not-found" class="entry cf">

							<header class="article-header">

								<h1>Pagina non trovata</h1>

							</header>

							<section class="entry-content">

								<img class="capezzone404" src="<?php echo $image; ?>" alt="<?php echo $ti_option['error_text']; ?>" />
								<p>L'articolo che cercavi non è stato trovato.</p>

							</section>

							<!--section class="search">

									<p><?php get_search_form(); ?></p>

							</section-->

							<footer class="article-footer">

									<!--p><?php _e( 'This is the 404.php template.', 'bonestheme' ); ?></p-->

							</footer>

						</article>

					</main>

				</div>

			</div>

<?php get_footer(); ?>
