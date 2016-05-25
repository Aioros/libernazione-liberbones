
              <?php
                /*
                 * This is the default post format.
                 *
                 * So basically this is a regular post. if you don't want to use post formats,
                 * you can just copy ths stuff in here and replace the post format thing in
                 * single.php.
                 *
                 * The other formats are SUPER basic so you can style them as you like.
                 *
                 * Again, If you want to remove post formats, just delete the post-formats
                 * folder and replace the function below with the contents of the "format.php" file.
                */
              ?>

              <article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article">

                <?php if (!has_post_thumbnail()) { ?>

                  <div class="article-header entry-header image-header wrap">

                    <h1 class="entry-title single-title" rel="bookmark"><?php the_title(); ?></h1>

                    <?php lib_bones_entry_meta(); ?>

                  </div> <!-- .entry-header -->

                <?php } else { ?>

                  <?php
                  /*
                   * Get cover image
                   * It is done here, and this way, in order to get image URL (for schema)
                   */
                  // featured image is used
                  $A3_Lazy_Load = A3_Lazy_Load::_instance();
                  add_filter('wp_get_attachment_image_attributes', 'add_nolazy_class');
                  remove_filter( 'wp_get_attachment_image_attributes', array( $A3_Lazy_Load, 'get_attachment_image_attributes' ), 200 );
                  
                  $thumb       = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
                  $thumb_image = get_the_post_thumbnail( $post->ID, 'full' );

                  ?>
                  <div id="single-header" class="cover-wrap">

                    <div class="cover-media"><?php echo $thumb_image; ?></div>

                    <div class="overlay single-overlay">

                      <div class="article-header entry-header overlay-content wrap">

                        <h1 class="entry-title single-title" rel="bookmark"><?php the_title(); ?></h1>

                        <?php lib_bones_entry_meta(); ?>

                      </div> <!-- .entry-header -->

                    </div> <!-- .single-overlay -->

                  </div> <!-- .cover-wrap -->

                <?php } ?>

                <section class="entry-content wrap cf">

                  <?php
                    if (function_exists("lib_social_buttons"))
                      echo lib_social_buttons();

                    // the content (pretty self explanatory huh)
                    the_content();

                    /*
                     * Link Pages is used in case you have posts that are set to break into
                     * multiple pages. You can remove this if you don't plan on doing that.
                     *
                     * Also, breaking content up into multiple pages is a horrible experience,
                     * so don't do it. While there are SOME edge cases where this is useful, it's
                     * mostly used for people to get more ad views. It's up to you but if you want
                     * to do it, you're wrong and I hate you. (Ok, I still love you but just not as much)
                     *
                     * http://gizmodo.com/5841121/google-wants-to-help-you-avoid-stupid-annoying-multiple-page-articles
                     *
                    */
                    /*wp_link_pages( array(
                      'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'bonestheme' ) . '</span>',
                      'after'       => '</div>',
                      'link_before' => '<span>',
                      'link_after'  => '</span>',
                    ) );*/

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

                  <?php the_tags( '<p class="tags wrap"><span class="tags-title">' . __( 'Tags:', 'bonestheme' ) . '</span> ', ', ', '</p>' ); ?>

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
                $authors = array();
                if (function_exists("coauthors_posts_links")) {
                  $authors = get_coauthors($post->ID);
                } else {
                  $authors = array(get_userdata($post->post_author));
                }
                foreach ($authors as $author) {
                  echo lib_author_box($author->ID, array("num_posts" => $author_posts, "description" => $author_description));
                }
              } ?>

              <?php bones_related_posts($related_posts); ?>

              <div class="comments">
              <?php comments_template(); ?>
              </div>
