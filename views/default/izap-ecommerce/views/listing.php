<?php
/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * @version 1.0
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

global $IZAP_ECOMMERCE;
$product = $vars['entity'];


    $owner = $product->getOwnerEntity();
    $image = '<img src="'.$product->getIcon().'" alt="'.$product->title.'" align="left"/>';
    $product_image = elgg_view('output/url', array(
                'href' => $product->getUrl(),
                'text' => $image,
));
    $owner_link = elgg_view('output/url', array(
                'href' =>  IzapBase::setHref(array(
                'action' => 'owner',
                'page_owner' => $product->container_username,
                )),
	'text' => $owner->name,
));

$author_text = elgg_echo('byline', array($owner_link));
$tags = elgg_view('output/tags', array('tags' =>  $product->tags));
$date = elgg_view_friendly_time($product->time_created);


if ($product->comments_on) {

	$comments_count = $product->countComments();
	//only display if there are commments
	if ($comments_count != 0) {
		$text = elgg_echo("izap-ecommerce:comments") . " ($comments_count)";
		$comments_link = elgg_view('output/url', array(
			'href' => $product->getURL() . '#store-comments',
			'text' => $text,
		));
	} else {
		$comments_link = '';
	}
} else {
	$comments_link = '';
}


    $subtitle = "<p>$author_text $date $comments_link</p>";

    $description = strip_tags($product->description);
    $description = substr($description, 0, 200) . ((strlen($description) > 200) ? '...' : '' );

    $params = array(
                'entity' => $product,
                'metadata' => IzapBase::controlEntityMenu(array('entity' => $product, 'handler' => GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER)),
                'subtitle' => $subtitle,
                'tags' => $tags,
                'content' => $description,
                'is_available' => $product->isAvailable()
              );
	$params = $params + $vars;
 $list_body = elgg_view('izap-ecommerce/elements/summary', $params);
 	echo elgg_view_image_block($product_image, $list_body);
