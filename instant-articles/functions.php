<?php

use Facebook\InstantArticles\Elements\Ad;
use Facebook\InstantArticles\Elements\Analytics;

function is_instant_articles() {
    global $wp_query;
    return $wp_query->is_main_query() && $wp_query->is_feed( INSTANT_ARTICLES_SLUG );
}

add_filter('instant_articles_transformer_rules_loaded', 'add_transformer_custom_rules', 10, 1);
function add_transformer_custom_rules($transformer) {
    $file_path = dirname( __FILE__ ) . '/custom-rules-configuration.json';
    $configuration = file_get_contents( $file_path );
    $transformer->loadRules( $configuration );
    return $transformer;
}

// Rimuoviamo il meta box (probabile causa del problema di cache di Facebook)
remove_action( 'add_meta_boxes', array( 'Instant_Articles_Meta_Box', 'register_meta_box' ) );
remove_action( 'wp_ajax_instant_articles_meta_box', array( 'Instant_Articles_Meta_Box', 'render_meta_box' ) );

// Aggiungiamo l'opzione auto publish (separata dal development mode)
Instant_Articles_Option_Publishing::$fields["auto_publish"] = array(
    'label' => 'Auto Publish',
    'render' => 'checkbox',
    'default' => false,
    'checkbox_label' => 'Enable auto publish',
);

// Usiamo un metodo custom per inviare l'articolo a FB per poterlo mandare
// negli IA di produzione ma non automaticamente pubblicato
require_once( dirname( __FILE__ ) . '/class-lib-instant-articles-publisher.php' );
remove_action( 'save_post', array( 'Instant_Articles_Publisher', 'submit_article' ), 10 );
add_action( 'save_post', array( 'Lib_Instant_Articles_Publisher', 'submit_article' ), 10, 2 );

// Parti custom
add_filter( 'instant_articles_transformed_element', 'instant_articles_add_custom_parts', 10, 1);
function instant_articles_add_custom_parts($instant_article) {
    global $post;
    $header = $instant_article->getHeader();

    // Subtitle
    if ($post->post_excerpt != "")
        $header->withSubTitle(get_the_excerpt($post->ID));

    // Analytics
    $document = new DOMDocument();
    $fragment = $document->createDocumentFragment();
    ob_start();
    include dirname(__FILE__) . "/template-analytics.php";
    $ga_html = ob_get_contents();
    ob_end_clean();
    $valid_html = @$fragment->appendXML( $ga_html );

    if ( $valid_html ) {
        $instant_article
            ->addChild(
                Analytics::create()
                    ->withHTML(
                        $fragment
                    )
            );
    }

    return $instant_article;
}

add_filter('instant_articles_cover_kicker', 'lib_category_kicker', 10, 2);
function lib_category_kicker($category, $post_id) {
    if (strtolower($category) == "uncategorized" || strtolower($category) == "senza categoria") {
        $category = "";
    } else {
        $category = ucwords($category);
    }
    return $category;
}

// Fix per evitare wpautop su shortcode gallery (WP introduce <p> intorno a <figcaption>, forse risolto recentemente)
add_filter("instant_articles_content", "undo_figcaption_autop", 10, 1);
function undo_figcaption_autop($content) {
    $content = str_replace(array("<p><figcaption>", "</figcaption></p>"), array("<figcaption>", "</figcaption>"), $content);
    return $content;
}

function rebuild_facebook_embed($matches) {
    $src = parse_url($matches[1], PHP_URL_QUERY);
    parse_str($src);
    $type = $matches[2];
    
    $result = <<<EOT
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&amp;version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-$type" data-href="$href"  
  data-allowfullscreen="true" data-width="500"></div>
EOT;
    
    return $result;
}

function enclose_safe_embed($matches) {
    $html = str_replace(array("// <![CDATA[", "// ]]>", "<p>", "</p>", "\n", "\r"), "", $matches[0]);
    $html = str_replace("&#038;version", "&version", $html);
    return '<iframe>'.$html.'</iframe>';
}

// Per embed non racchiusi in un iframe
add_filter( 'instant_articles_content', 'lib_sanitize_embeds', 10, 1 );
function lib_sanitize_embeds($content) {
    
    // Twitter blockquotes
    // e.g:
    //<blockquote class="twitter-tweet" data-lang="it"><p dir="ltr" lang="it">MA IL GOAL DI TURONE ERA REGOLARE? <a href="https://twitter.com/hashtag/matteorisponde?src=hash">#matteorisponde</a></p>
    //— daniele onori (@danieleonori1) <a href="https://twitter.com/danieleonori1/status/717387368882446337">5 aprile 2016</a></blockquote>
    //<script src="//platform.twitter.com/widgets.js" async="" charset="utf-8"></script>
    $content = preg_replace(
        '#(<p>)?(<blockquote\s+.*?\bclass="twitter-[^"]*".*?>.*?</blockquote>.*?<script\s+.*?\bsrc=".*?\.twitter\.com.*?".*?></script>)(</p>)?#s',
        '<div class="embed">$2</div>',
        $content
    );

    // Fix per embed video e post di Facebook: l'embed iframe non funziona su mobile, quello con sdk.js sì
    // (l'iframe intorno viene messo dal filtro successivo)
    $content = preg_replace_callback('#<iframe src="(https://www\.facebook\.com/plugins/(\w+)\.php[^"]*)\"[^>]*></iframe>*#',
        "rebuild_facebook_embed",
        $content
    );

    // Facebook quotes
    // e.g:
    //<div id="fb-root"></div>
    //<script>// <![CDATA[
    //(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/it_IT/sdk.js#xfbml=1&#038;version=v2.3";  fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));
    // ]]></script>
    //<div class="fb-post" data-href="https://www.facebook.com/laradiazza/videos/1020788904636055/" data-width="500">
    //<div class="fb-xfbml-parse-ignore">
    //<blockquote cite="https://www.facebook.com/laradiazza/videos/1020788904636055/">#raimondoubini (Simioli) chiama #gianpaolotosel : Caso Gonzalo Higuaín , Juventus e gli arbitri, la "simpatia" del...
    //Pubblicato da <a href="https://www.facebook.com/laradiazza/">LA RADIAZZA - PAGINA UFFICIALE</a> su <a href="https://www.facebook.com/laradiazza/videos/1020788904636055/">Martedì 5 aprile 2016</a></blockquote>
    //</div>
    //</div>
    $content = preg_replace_callback(
        '#(<p>)?(<div id="fb-root"></div>)?(\s|<p>)*?<script>.*?</script>(\s|</p>)*?<div class="fb-[^"]*".*?</div>\s*(</div>)?(</p>)?#s',
        "enclose_safe_embed",
        $content
    );

    return $content;
}