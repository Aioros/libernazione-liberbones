<?php get_header();?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<main id="main" class="m-all t-all d-5of7 cf" role="main">

							<div id="posts-container">

								<?php
								$counter = 0;
								$img_counter = 0;

								if (have_posts()) : while (have_posts()) : the_post();

								if ($counter == 4) { ?>
									<article class="brick cf">
										<?php print_adv("home-1"); ?>
									</article>
								<?php }
								if ($counter == 12) { ?>
									<article class="brick cf">
										<?php print_adv("home-2"); ?>
									</article>
								<?php } ?>

								<article id="post-<?php the_ID(); ?>" <?php post_class( 'brick cf' ); ?> role="article">

									<?php if( has_post_thumbnail() ) : ?>
										<div class="brick-media">
											<a href="<?php the_permalink(); ?>">
												<?php
												if (class_exists("A3_Lazy_Load")) {
													$A3_Lazy_Load = A3_Lazy_Load::_instance();
													if (!wp_is_mobile() && $img_counter < 4 || wp_is_mobile() && $img_counter < 1) {
														add_filter('wp_get_attachment_image_attributes', 'add_nolazy_class');
														remove_filter( 'wp_get_attachment_image_attributes', array( $A3_Lazy_Load, 'get_attachment_image_attributes' ), 200 );
													}
												}
												$img_counter++;
												the_post_thumbnail('thumb-medium'); ?>
											</a>
											<span class="entry-overlay entry-meta-category"><?php echo ucwords(get_the_category_list(",")); ?></span>
											<?php
											$numComments = get_comments_number();
											if ($numComments > 0) { 
												$text = sprintf( _n('%s commento', '%s commenti', $numComments), $numComments );
												?>
												<span class="entry-overlay comments-count"><a href="<?php the_permalink(); ?>#comments"><?php echo $text; ?></a></span>
											<?php } ?>
										</div>
									<?php endif; ?>

									<header class="article-header">

										<h1 class="h2 entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
										<?php lib_bones_entry_meta(false); ?>

									</header>

									<section class="entry-content cf">
										<?php 
										if (get_post_type() == "micropost")
											the_content();
										else
											the_excerpt();
										?>
									</section>

								</article>

								<?php $counter++; endwhile; ?>

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
