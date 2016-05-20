<?php

class BZ_AMP_DFP_Injection_Sanitizer extends AMP_Base_Sanitizer {
	public function sanitize() {
		$body = $this->get_body_node();

		$container_node = AMP_DOM_Utils::create_node( $this->dom, 'div', array(
			'class' => 'ad-container'
		));

		// Build our amp-ad tag
		$ad_node = AMP_DOM_Utils::create_node( $this->dom, 'amp-ad', array(
			// Taken from example at https://github.com/ampproject/amphtml/blob/master/builtins/amp-ad.md
			'width' => 300,
			'height' => 250,
			'type' => 'doubleclick',
			'data-slot' => Constants::$ADV_SITE . "news_network_giornalettismo_amp"
		) );

		// Add a placeholder to show while loading
		$fallback_node = AMP_DOM_Utils::create_node( $this->dom, 'amp-img', array(
			'placeholder' => '',
			'layout' => 'fill',
			'src' => 'https://placehold.it/300X250?text=%20',
		) );
		$ad_node->appendChild( $fallback_node );

		$container_node->appendChild($ad_node);

		// If we have a lot of paragraphs, insert before the 4th one.
		// Otherwise, add it to the end.
		$p_nodes = $body->getElementsByTagName( 'p' );
		if ( $p_nodes->length > 6 ) {
			$p_nodes->item( 4 )->parentNode->insertBefore( $container_node, $p_nodes->item( 4 ));
		} else {
			$body->appendChild( $container_node );
		}
	}
}