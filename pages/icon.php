<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* @version 1.0
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Jangra<tarun@izap.in>"
* For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
* Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
*/

global $IZAP_ECOMMERCE;
$guid = izap_get_params(1);
$izap_product = get_product_izap_ecommerce($guid);
$size = izap_get_params(2, '100x100');
$thumb_name = 'izap-ecommerce/'.$guid.'/icon_' . str_replace('x', '_', $size);

$file_handler = new ElggFile();
$file_handler->owner_guid = $izap_product->owner_guid;

$size = explode('x', $size);
if($izap_product->image_path != '') {
  $file_handler->setFilename($izap_product->image_path);
  $file_handler->open('read');
  $file_name = $file_handler->getFilenameOnFilestore();
}

if(!file_exists($file_name)) {
  $file_name = $IZAP_ECOMMERCE->default_image;
}
if($size[0] != 'na' && $size[1] != 'na') {
  // check for the thumb first
  $file_handler->setFilename($thumb_name . '.' . get_extension_izap_ecommerce($file_name));
  $file_handler->open('read');
  $thumb_file = $file_handler->getFilenameOnFilestore();
  if(file_exists($thumb_file)) {
    $file_handler->setFilename($thumb_name . '.' . get_extension_izap_ecommerce($file_name));
    $file_handler->open('read');
    $contents = $file_handler->grabFile();
  }else {
    $contents = get_resized_image_from_existing_file($file_name, $size[0], $size[1], TRUE);
    $file_handler->setFilename($thumb_name . '.' . get_extension_izap_ecommerce($file_name));
    $file_handler->open('write');
    $file_handler->write($contents);
    $file_handler->close();
  }
}else {
  $contents = $file_handler->grabFile();
}
if($contents) {
  header("Expires: Mon, 20 Dec 1998 01:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
  header("Content-type: {$izap_product->getMimeType()}");
  header("Content-Disposition: inline; filename=\"{$file_name}\"");
  echo $contents;
}
exit;