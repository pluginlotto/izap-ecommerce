<?php
/**************************************************
* iZAP Web Solutions                              *
* Copyrights (c) 2005-2009. iZAP Web Solutions.   *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Kumar<tarun@izap.in>"
 */

global $IZAP_ECOMMERCE;
$guid = izap_get_params(1);
$izap_product = get_product_izap_ecommerce($guid);
$size = izap_get_params(2, '100x100');
$thumb_name = 'izap-ecommerce/'.$guid.'/icon_' . str_replace('x', '_', $size);

$size = explode('x', $size);
if($izap_product->image_path != '') {
  $izap_product->setFilename($izap_product->image_path);
  $izap_product->open('read');
  $file_name = $izap_product->getFilenameOnFilestore();
}

if(!file_exists($file_name)) {
  $file_name = $IZAP_ECOMMERCE->default_image;
}
if($size[0] != 'na' && $size[1] != 'na') {
  // check for the thumb first
  $izap_product->setFilename($thumb_name . '.' . get_extension_izap_ecommerce($file_name));
  $izap_product->open('read');
  $thumb_file = $izap_product->getFilenameOnFilestore();
  if(file_exists($thumb_file)) {
    $izap_product->setFilename($thumb_name . '.' . get_extension_izap_ecommerce($file_name));
    $izap_product->open('read');
    $contents = $izap_product->grabFile();
  }else {
    $contents = get_resized_image_from_existing_file($file_name, $size[0], $size[1], TRUE);
    $izap_product->setFilename($thumb_name . '.' . get_extension_izap_ecommerce($file_name));
    $izap_product->open('write');
    $izap_product->write($contents);
    $izap_product->close();
  }
}else {
  $contents = $izap_product->grabFile();
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