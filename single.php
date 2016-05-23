<?php get_header(); ?>
<?php $has_sidebar = false; ?>

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div id="content">

				<div id="inner-content" class="cf">

					<main id="main" class="m-all <?php if ($has_sidebar) echo 't-2of3 d-5of7 '; ?>cf" role="main">

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
