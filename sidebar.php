				<div id="sidebar" class="sidebar bones-sidebar m-none t-none d-2of7 last-col cf" role="complementary">

					<?php print_adv("sidebar-top"); ?>

					<?php if ( is_active_sidebar( 'sidebar' ) ) : ?>

						<?php dynamic_sidebar( 'sidebar' ); ?>

					<?php else : ?>

						<?php
							/*
							 * This content shows up if there are no widgets defined in the backend.
							*/
						?>

						<div class="no-widgets">
							<p><?php _e( 'This is a widget ready area. Add some and they will appear here.', 'bonestheme' );  ?></p>
						</div>

					<?php endif; ?>

					<?php print_adv("sidebar-bottom"); ?>

				</div>
