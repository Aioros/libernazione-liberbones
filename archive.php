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
							    echo lib_author_box(get_query_var('author'));
							}
							?>

							<div id="posts-container">

								<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

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

												//the_post_thumbnail('thumb-medium');

												$attachment_id = get_post_thumbnail_id();
												list($img_src, $img_width, $img_height) = wp_get_attachment_image_src( $attachment_id, array(600, 600) );
												$img_srcset = wp_get_attachment_image_srcset( $attachment_id, array(600, 600) );
												?>
												<img src="<?php echo esc_url( $img_src ); ?>"
												     srcset="<?php echo esc_attr( $img_srcset ); ?>"
												     sizes="(max-width: 768px) 100vw, (max-width: 1030px) 50vw, 33vw"
												     data-width="<?php echo $img_width; ?>" 
												     data-height="<?php echo $img_height; ?>">
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
