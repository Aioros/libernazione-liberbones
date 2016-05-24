<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<main id="main" class="m-all t-all d-5of7 cf" role="main">
							
							<?php
							if ( is_category() ) {?>
								<h1 class="page-title category-title"><?php echo ucfirst(single_cat_title( '', false )); ?></h1>
							<?php } elseif ( is_tag() ) { ?>
							    <h1 class="page-title tag-title">Tag: <?php echo single_tag_title( '', false ); ?></h1>
							<?php } elseif ( is_author() ) {
							    echo lib_author_box();
							}
							?>

							<div id="posts-container">

								<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

								<article id="post-<?php the_ID(); ?>" <?php post_class( 'brick cf' ); ?> role="article">

									<?php if( has_post_thumbnail() ) : ?>
										<div class="brick-media">
											<a href="<?php the_permalink(); ?>">
												<?php the_post_thumbnail('thumb-medium'); ?>
											</a>
										</div>
									<?php endif; ?>

									<header class="article-header">

										<h1 class="h2 entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
										<?php lib_bones_entry_meta(false); ?>

									</header>

									<section class="entry-content cf">
										<?php the_excerpt(); ?>
									</section>

								</article>

								<?php endwhile; ?>

							</div>

								<?php bones_page_navi(); ?>

							<?php else : ?>

									<article id="post-not-found" class="entry cf">
											<header class="article-header">
												<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
										</header>
											<section class="entry-content">
												<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e( 'This is the error message in the index.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>


						</main>

					<?php if (!wp_is_mobile()) get_sidebar(); ?>

				</div>

			</div>


<?php get_footer(); ?>
