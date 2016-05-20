<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

use Facebook\InstantArticles\Client\Client;
use Facebook\Facebook;

/**
 * Class responsible for drawing the meta box on the post edit page
 *
 * @since 0.1
 */
class Lib_Instant_Articles_Publisher {

	/**
	 * Inits publisher.
	 */
	public static function init() {
		add_action( 'save_post', array( 'Lib_Instant_Articles_Publisher', 'submit_article' ), 10, 2 );
	}

	/**
	 * Submits article to Instant Articles.
	 *
	 * @param string $post_id The identifier of post.
	 * @param Post   $post_obj The WP Post.
	 */
	public static function submit_article( $post_id, $post_obj ) {

		// Don't process if this is just a revision or an autosave.
		if ( wp_is_post_revision( $post_obj ) || wp_is_post_autosave( $post_obj ) ) {
			return;
		}

		// Don't process if this post is not published
		if ('publish' !== $post_obj->post_status) {
			return;
		}

		// Transform the post to an Instant Article.
		$adapter = new Instant_Articles_Post( $post_obj );

		// Fix per problema post schedulati:
		// cfr. https://github.com/Automattic/facebook-instant-articles-wp/issues/198#issuecomment-217825766
		global $post;
		$reset_postdata = false;
		if ( $post_obj->ID !== $post->ID ) {
			$post = get_post( $post_obj->ID );
			setup_postdata( $post );
			$reset_postdata = true;
		}

		$article = $adapter->to_instant_article();

		if ( $reset_postdata ) {
			wp_reset_postdata();
		}

		// Instantiate an API client.
		try {
			$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();
			$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();
			$publishing_settings = Instant_Articles_Option_Publishing::get_option_decoded();

			$dev_mode = isset( $publishing_settings['dev_mode'] )
				? ( $publishing_settings['dev_mode'] ? true : false )
				: false;

			if ( isset( $fb_app_settings['app_id'] )
				&& isset( $fb_app_settings['app_secret'] )
				&& isset( $fb_page_settings['page_access_token'] )
				&& isset( $fb_page_settings['page_id'] ) ) {

				$client = Client::create(
					$fb_app_settings['app_id'],
					$fb_app_settings['app_secret'],
					$fb_page_settings['page_access_token'],
					$fb_page_settings['page_id'],
					$dev_mode
				);

				// take_live dipende dalla nostra opzione custom
				$take_live = isset( $publishing_settings['auto_publish'] )
					? ( $publishing_settings['auto_publish'] ? true : false )
					: false;

				// Per un problema con il render degli script (in particolare embed facebook)
				// usiamo direttamente un oggetto Facebook per poter parsare l'html
				// risultante prima dell'invio
				/*try {
					// Import the article.
					$client->importArticle( $article, $take_live );
				} catch ( Exception $e ) {
					// Try without taking live for pages not yet reviewed.
					$client->importArticle( $article, false );
				}*/
				$html = $article->render();
				$html = preg_replace("#<script>\s*(//)?\s*<!\[CDATA\[#", "<script>", $html);
				$html = preg_replace("#\s*(//)?\s*\]\]>\s*</script>#", "</script>", $html);

				$bz_facebook = new Facebook([
		            'app_id' => $fb_app_settings['app_id'],
		            'app_secret' => $fb_app_settings['app_secret'],
		            'default_access_token' => $fb_page_settings['page_access_token'],
		            'default_graph_version' => 'v2.5'
		        ]);
				try {
					// Import the article.
			        $bz_facebook->post($fb_page_settings['page_id'] . Client::EDGE_NAME, [
			          'html_source' => $html,
			          'take_live' => $take_live,
			          'development_mode' => $dev_mode,
			        ]);
				} catch ( Exception $e ) {
					// Try without taking live for pages not yet reviewed.
			        $bz_facebook->post($fb_page_settings['page_id'] . Client::EDGE_NAME, [
			          'html_source' => $html,
			          'take_live' => false,
			          'development_mode' => $dev_mode,
			        ]);
				}

			}
		} catch ( Exception $e ) {
			Logger::getLogger( 'instantarticles-wp-plugin' )->error(
				'Unable to submit article.',
				$e->getTraceAsString()
			);
		}
	}
}
