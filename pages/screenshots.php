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

$product = get_product_izap_ecommerce(izap_get_params(1));
if($product) {
  global $IZAP_ECOMMERCE;

  $file_name = izap_get_params(2);

  $file_handler = new ElggFile();
  $file_handler->owner_guid = $product->owner_guid;
  $file_handler->setFilename($IZAP_ECOMMERCE->plugin_name . '/' . $product->guid . '/' . $file_name);
  $file_handler->open('read');

  if(file_exists($file_handler->getFilenameOnFilestore())) {
    izap_cache_headers(array(
            'content_type' => image/jpeg,
            'file_name' => $file_name,
    ));
    echo $file_handler->grabFile();
  }
}
exit;
