<?php

/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */
$product = elgg_extract('entity', $vars);
$add_wishlist_link = elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_ecommerce/add_to_wishlist?guid=' . $product->guid);
if (IzapEcommerce::isInWishlist($product->guid)) {
  echo '<a href="' . elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_ecommerce/' .
          'remove_from_wishlist?guid=' . $product->guid) . '">' . elgg_echo('izap-ecommerce:remove_from_wishlist') . '</a>';
} elseif (isloggedin() && !$product->isArchived()) {
  echo '<a href="' . $add_wishlist_link . '">' . elgg_echo('izap-ecommerce:add_to_wishlist') . '</a>';
}

// check if the product is archived and can be download
if($product->isArchived() && $product->canDownload()) {
    $donwload_link = create_product_download_link_izap_ecommerce(rand(0, 1000), $product->guid);
    echo '<br /><a href="' . $donwload_link . '">' . elgg_echo('izap-ecommerce:download') . '</a>';
}