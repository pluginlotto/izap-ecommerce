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

$error = FALSE;
$order_id = get_input('o');
$product_guid = get_input('p');
$time_stamp = get_input('t');

$hash = create_hash_izap_ecommerce($order_id, $product_guid, $time_stamp, get_loggedin_userid());
if($hash != get_input('h')) {
  $error = TRUE;
}
$product = get_product_izap_ecommerce($product_guid);
if(!$product) {
  $error = TRUE;
}
$content = $product->getFile();
if($content == '') {
  $error = TRUE;
}


if($error) {
  register_error(elgg_echo('izap-ecommerce:invalid_link'));
  forward();
}else {

  // update the  total download count
  IzapBase::getAllAccess();
  $product->total_downloads = (int) ($product->total_downloads) +1;
  IzapBase::removeAccess();
  
  $file_name = basename($product->file_path);
  $size = strlen($content);

  header('Content-Description: File Transfer');
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Type: application/octet-stream");
  header("Content-Type: " . $product->getMimeType());
  header("Content-Disposition: attachment; filename=".$file_name.";");
  header("Content-Transfer-Encoding: binary");
  header("Content-Length: ".$size);

  echo $content;
}
exit;