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

gatekeeper();
global $IZAP_ECOMMERCE;

$product = get_product_izap_ecommerce(get_input('guid'));
$product->file_prefix = $IZAP_ECOMMERCE->plugin_name . '/' . $product->guid . '/';
if(!$product) {
  register_error(__('invalid_product'));
  forward($_SERVER['HTTP_REFERER']);
}

for($i=0; $i<get_input('total_images'); $i++) {
  if($_FILES['screenshots']['error'][$i] == 0 && $_FILES['screenshots']['size'][$i] > 0 && in_array(get_extension_izap_ecommerce($_FILES['screenshots']['name'][$i]), $product->allowed_image_types)) {
    $upload_array[] = array(
    'name' => $_FILES['screenshots']['name'][$i],
    'tmp_name' => $_FILES['screenshots']['tmp_name'][$i],
    );
  }
}

$screenshots_array = unserialize($product->screenshots);
$screenshot_thumbs_array = unserialize($product->screenshot_thumbs);

foreach($upload_array as $upload) {

  $extension = get_extension_izap_ecommerce($upload['name']);
  $screenshot_name = 'screenshot_' . md5($upload['name']) . '.' . $extension;
  $screenshot_thumb_name = 'thumb_screenshot_' . md5($upload['name']) . '.' . $extension;
  $content = file_get_contents($upload['tmp_name']);
  
  // start writing file on disk
  $file_handler = new ElggFile();
  $file_handler->setFilename($product->file_prefix . $screenshot_name);
  $file_handler->open('write');
  if($file_handler->write($content)) {
    $screenshots_array[] = $screenshot_name;
  }
  $file_handler->close();

  // start writing file
  $thumb = get_resized_image_from_existing_file($file_handler->getFilenameOnFilestore(), 100, 100, TRUE);
  $file_handler = new ElggFile();
  $file_handler->setFilename($product->file_prefix . $screenshot_thumb_name);
  $file_handler->open('write');
  if($file_handler->write($thumb)) {
    $screenshot_thumbs_array[] = $screenshot_thumb_name;
  }
  $file_handler->close();

}

$product->screenshots = serialize($screenshots_array);
$product->screenshot_thumbs = serialize($screenshot_thumbs_array);
forward($_SERVER['HTTP_REFERER']);
exit;