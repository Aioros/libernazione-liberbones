<?php 

class BZ_AMP_Facebook_Sanitizer extends AMP_Base_Sanitizer {

	protected $DEFAULT_WIDTH = 600;
	protected $DEFAULT_HEIGHT = 400;

	private static $script_slug = 'amp-facebook';
	private static $script_src = 'https://cdn.ampproject.org/v0/amp-facebook-0.1.js';

	public function get_scripts() {
		if ( ! $this->did_convert_elements ) {
			return array();
		}

		return array( self::$script_slug => self::$script_src );
	}

	public function sanitize() {
		$body = $this->get_body_node();
		$this->replace_facebook_embed_recursive( $body );
		$this->did_convert_elements = true;
	}

	private function replace_facebook_embed_recursive( $node ) {
		if ( $node->nodeType !== XML_ELEMENT_NODE ) {
			return;
		}

		if ( $node->hasAttributes() ) {
			$node_name = $node->nodeName;
			if ( $node->nodeName === 'div' && $node->getAttribute("class") === "fb-post" && $node->hasAttribute("data-width")) {
				// Build our amp-facebook tag
				$fb_node = AMP_DOM_Utils::create_node( $this->dom, 'amp-facebook', array(
					'data-href' => $node->getAttribute("data-href"),
					'layout' => 'responsive',
					'width' => $node->getAttribute("data-width"),
					'height' => $this->DEFAULT_HEIGHT
				));
				$node->parentNode->replaceChild($fb_node, $node);
			}
		}

		foreach ( $node->childNodes as $child_node ) {
			$this->replace_facebook_embed_recursive( $child_node );
		}
	}
}