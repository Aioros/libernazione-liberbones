<?php get_header(); ?>
<?php $has_sidebar = false; ?>

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div id="content">

				<?php if (!has_post_thumbnail() || get_post_format() == "image") { ?>

					<div class="article-header entry-header image-header wrap">

						<h1 class="entry-title single-title" itemprop="headline" rel="bookmark"><?php the_title(); ?></h1>

						<?php lib_bones_entry_meta(); ?>

					</div> <!-- .entry-header -->

				<?php } else { ?>

					<?php
					/*
					 * Get cover image
					 * It is done here, and this way, in order to get image URL (for schema)
					 */
					if( has_post_thumbnail() ){
						// featured image is used
						$thumb_image = get_the_post_thumbnail( $post->ID, 'full' );
						$thumb       = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
					}

					?>
					<div id="single-header" class="cover-wrap">

						<div class="cover-media" itemprop="image" content="<?php echo esc_attr($thumb[0]); ?>"><?php echo $thumb_image; ?></div>

						<div class="overlay single-overlay">

							<div class="article-header entry-header overlay-content wrap">

								<h1 class="entry-title single-title" itemprop="headline" rel="bookmark"><?php the_title(); ?></h1>

								<?php lib_bones_entry_meta(); ?>

							</div> <!-- .entry-header -->

						</div> <!-- .single-overlay -->

					</div> <!-- .cover-wrap -->

				<?php } ?>

				<div id="inner-content" class="cf">

					<main id="main" class="m-all <?php if ($has_sidebar) echo 't-2of3 d-5of7 '; ?>cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/WebPageElement">

						<?php
							/*
							 * Ah, post formats. Nature's greatest mystery (aside from the sloth).
							 *
							 * So this function will bring in the needed template file depending on what the post
							 * format is. The different post formats are located in the post-formats folder.
							 *
							 *
							 * REMEMBER TO ALWAYS HAVE A DEFAULT ONE NAMED "format.php" FOR POSTS THAT AREN'T
							 * A SPECIFIC POST FORMAT.
							 *
							 * If you want to remove post formats, just delete the post-formats folder and
							 * replace the function below with the contents of the "format.php" file.
							*/
							get_template_part( 'post-formats/format', get_post_format() );
						?>

					</main>

					<?php if ($has_sidebar) get_sidebar(); ?>

				</div>

			</div>

		<?php endwhile; ?>

		<?php endif; ?>

<?php get_footer(); ?>
