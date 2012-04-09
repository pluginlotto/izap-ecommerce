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
if ($product->canDownload()) {
  $donwload_link = create_product_download_link_izap_ecommerce(
          array('product_guid' => $product->guid,
            'class' => 'elgg-button elgg-button-action'
      ));

  echo $donwload_link;
}