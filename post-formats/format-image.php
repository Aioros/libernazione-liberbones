
              <article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article">

                <div class="article-header entry-header image-header wrap">

                  <h1 class="entry-title single-title" itemprop="headline" rel="bookmark"><?php the_title(); ?></h1>

                  <?php lib_bones_entry_meta(); ?>

                </div> <!-- .entry-header -->

                <section class="entry-content wrap cf" itemprop="articleBody">
                  <?php
                    if (function_exists("lib_social_buttons"))
                      echo lib_social_buttons();
                    // the content (pretty self explanatory huh)
                    the_content();
                    if (function_exists("lib_social_buttons"))
                      echo lib_social_buttons($placeholder_only = true);
                  ?>
                </section> <?php // end article section ?>

                <?php if (wp_is_mobile()) {
                  $footer = false;
                  $author_posts = 0;
                  $related_posts = 0;
                  $author_description = false;
                  $prevnext = false;
                } else {
                  $footer = true;
                  $author_posts = 2;
                  $related_posts = 2;
                  $author_description = true;
                  $prevnext = true;
                } ?>

                <?php if ($footer) : ?>
                <footer class="article-footer">

                  <?php //printf( __( 'filed under', 'bonestheme' ).': %1$s', get_the_category_list(', ') ); ?>

                  <?php the_tags( '<p class="tags"><span class="tags-title">' . __( 'Tags:', 'bonestheme' ) . '</span> ', ', ', '</p>' ); ?>

                </footer> <?php // end article footer ?>
                <?php endif; ?>

              </article> <?php // end article ?>

              <?php if ($prevnext) { ?>
              <nav class="post-navigation wp-prev-next clearfix">
              <div class="prev-link"><?php previous_post_link('<span class="adj-arrow">&#x2190</span> %link'); ?></div>
              <div class="next-link"><?php next_post_link('%link <span class="adj-arrow">&#x2192</span>'); ?></div>
              </nav>
              <?php } ?>

              <?php if (function_exists("lib_author_box")) {
                echo lib_author_box(NULL, array("num_posts" => $author_posts, "description" => $author_description));
              } ?>

              <?php bones_related_posts($related_posts); ?>

              <div class="comments">
              <?php comments_template(); ?>
              </div>
