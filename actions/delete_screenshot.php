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

$product = get_product_izap_ecommerce(get_input('product_guid'));
if($product) {
  global $IZAP_ECOMMERCE;
  $screenshots_array = unserialize($product->screenshots);
  $screenshot_thumbs_array = unserialize($product->screenshot_thumbs);

  $file_name = get_input('thumb');
  $key = array_search($file_name, $screenshots_array);

  if($key !== false) {
    $file_handler = new ElggFile();
    $file_handler->setFilename($IZAP_ECOMMERCE->plugin_name . '/' . $product->guid . '/' . $file_name);
    if(file_exists($file_handler->getFilenameOnFilestore())) {
      if(unlink($file_handler->getFilenameOnFilestore())) {
        unset($screenshots_array[$key]);
      }
    }else{
      // remove if file doesn't exist
      unset($screenshots_array[$key]);
    }
  }

  $key = array_search('thumb_' . $file_name, $screenshot_thumbs_array);
  if($key !== false) {
    $file_handler->setFilename($IZAP_ECOMMERCE->plugin_name . '/' . $product->guid . '/thumb_' . $file_name);
    if(file_exists($file_handler->getFilenameOnFilestore())) {
      if(unlink($file_handler->getFilenameOnFilestore())) {
        unset($screenshot_thumbs_array[$key]);
      }
    }else {
      // remove if file doesn't exist
      unset($screenshot_thumbs_array[$key]);
    }
  }
  // save again
  $product->screenshots = serialize($screenshots_array);
  $product->screenshot_thumbs = serialize($screenshot_thumbs_array);
}
forward($_SERVER['HTTP_REFERER']);
exit;