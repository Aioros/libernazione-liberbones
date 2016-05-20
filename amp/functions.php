<?php

/*add_filter( 'amp_content_sanitizers', 'bz_amp_add_sanitizers', 10, 2 );
function bz_amp_add_sanitizers( $sanitizer_classes, $post ) {
	require_once( dirname( __FILE__ ) . '/class-facebook-sanitizer.php' );
	$sanitizer_classes[ 'BZ_AMP_Facebook_Sanitizer' ] = array(); // the array can be used to pass args to your sanitizer and accessed within the class via `$this->args`

    require_once( dirname( __FILE__ ) . '/class-dfp-injection-sanitizer.php' );
    $sanitizer_classes[ 'BZ_AMP_DFP_Injection_Sanitizer' ] = array();
	return $sanitizer_classes;
}*/

/*add_filter( 'amp_content_embed_handlers', 'bz_amp_add_embeds', 10, 2 );
function bz_amp_add_embeds( $embed_handler_classes, $post ) {
    require_once( dirname( __FILE__ ) . '/class-amp-videonova-embed.php' );
    $embed_handler_classes[ 'BZ_AMP_Video_Nova_Embed' ] = array();
    
    return $embed_handler_classes;
}*/

add_filter( 'amp_post_template_analytics', 'amp_add_custom_analytics' );
function amp_add_custom_analytics( $analytics ) {
    if ( ! is_array( $analytics ) ) {
        $analytics = array();
    }

    // https://developers.google.com/analytics/devguides/collection/amp-analytics/
    $analytics['googleanalytics'] = array(
        'type' => 'googleanalytics',
        'attributes' => array(
            // 'data-credentials' => 'include',
        ),
        'config_data' => array(
            'vars' => array(
                'account' => 'UA-985687-8'
            ),
            'triggers' => array(
                'trackPageview' => array(
                    'on' => 'visible',
                    'request' => 'pageview',
                ),
            ),
        ),
    );

    return $analytics;
}

add_action( 'amp_post_template_css', 'lib_amp_my_additional_css_styles' );

function lib_amp_my_additional_css_styles( $amp_template ) {
    // only CSS here please...
    ?>
		nav.amp-wp-title-bar {
			background-color: #000;
		}
        nav.amp-wp-title-bar a {
            display: block;
            margin: 10px auto;
            max-width: 200px;
        }
		nav.amp-wp-title-bar div {
			overflow: hidden;
			height: 46px;
		}
		nav.amp-wp-title-bar .amp-wp-site-icon {
			margin-top: 0px;
			border-radius: 0px;
		}
		a, a:visited, a:hover, a:active, a:focus {
			color: #000;
		}
		.amp-wp-meta, .amp-wp-meta a {
            font-family: 'Roboto', arial;
            text-decoration: none;
            color: #ccc;
            font-size: 14px;
            line-height: 20px;
        }
        .amp-wp-meta > span:not(.co-author-first) {
            margin-right: 5px;
        }
        .amp-wp-meta > span:not(:first-child):not(.co-author):before {
            content: "·";
            padding-right: 5px;
            color: #999;
        }
        .amp-wp-tax-category {
            text-transform: capitalize;
        }
		body, .amp-wp-content, blockquote {
			color: #565656;
			font-family: 'Roboto', arial;
			font-weight: 300;
		}
        .amp-wp-header {
            position: relative;
            color: white;
        }
        .amp-header-overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
            z-index: 1;
            background-color: rgba(0, 0, 0, 0.55);
        }
        .amp-article-header {
            position: absolute;
            top: 50%;
            -moz-transform: translateY(-50%);
            -o-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            padding: 20px;
            text-align: center;
        }
		.amp-wp-title {
			font-family: "Libre Baskerville", "Georgia", Cambria, Times New Roman, Times, serif;
			font-weight: 400;
            font-size: 24px;
            margin: 0 0 15px;
            color: white;
		}
        .amp-wp-content {
            font-size: 16px;
        }
        .amp-wp-content a {
            /*font-weight: 700;*/
        }
		blockquote {
			border-left: 2px solid #000;
			font-style: italic;
			background: none;
		}
    <?php
}

add_filter( 'amp_post_template_data', 'lib_amp_set_site_icon_url' );

function lib_amp_set_site_icon_url( $data ) {
    // Ideally a 32x32 image
    $data[ 'site_icon_url' ] = 'http://libernazione.it/wp-content/uploads/2016/01/liblogowhite.png';
    return $data;
}

add_filter( 'amp_post_template_metadata', 'lib_amp_modify_json_metadata', 10, 2 );

function lib_amp_modify_json_metadata( $metadata, $post ) {
	$metadata['@type'] = 'Article';

	$metadata['publisher']['logo'] = array(
		'@type' => 'ImageObject',
		'url' => 'http://libernazione.it/wp-content/uploads/2014/12/logolibern.png',
		'height' => 58,
		'width' => 265,
	);
	return $metadata;
}

/**
 * Template tag to show featured image on AMP
 * @param string $size the post thumbnail size
 */
function lib_amp_featured_img( $size = 'medium' ) {
 
    global $post;
     
    if ( has_post_thumbnail( $post->ID ) ) {
 
        $thumb_id = get_post_thumbnail_id( $post->ID );
        $img = wp_get_attachment_image_src( $thumb_id, $size );
        $img_src = $img[0];
        $w = $img[1];
        $h = $img[2];
 
        $alt = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
         
        if(empty($alt)) {
            $attachment = get_post( $thumb_id );
            $alt = trim(strip_tags( $attachment->post_title ) ); 
        } ?>
        <amp-img class="feat-img" src="<?php echo esc_url( $img_src ); ?>" <?php
            if ( function_exists("wp_get_attachment_image_srcset") && $img_srcset = wp_get_attachment_image_srcset( $thumb_id, $size ) ) {
                ?> srcset="<?php echo esc_attr( $img_srcset ); ?>" <?php
            }
            ?>
            alt="<?php echo esc_attr( $alt ); ?>"
            layout="responsive"
            width="<?php echo $w; ?>"
            height="<?php echo $h; ?>">
        </amp-img>
        <?php
    }
}