<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* @version {version} $Revision: {revision}
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Jangra<tarun@izap.in>"
* For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
* Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
*/

global $IZAP_ECOMMERCE;
$page_owner = page_owner_entity();
$wishlist = $page_owner->izap_wishlist;
if(!is_array($wishlist) && (int) $wishlist) {
  $wishlist = array($wishlist);
}
$title = __('wishlist');
$body = elgg_view_title($title);

if(sizeof($wishlist)) {
  foreach($wishlist as $product_guid) {
    $product = get_entity($product_guid);
    $body .= elgg_view_entity($product, FALSE);
  }
}else {
  $body .= func_izap_bridge_view('views/no_data');
}
IzapEcommerce::draw_page($title, $body);