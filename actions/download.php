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
if(!$content) {
  $error = TRUE;
}


if($error) {
  register_error(__('invalid_link'));
  forward();
}

$file_name = end(explode('/', $product->file_path));
$size = strlen($content);

//header("Cache-Control: no-cache, must-revalidate");
//header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
//header("Content-Transfer-Encoding: binary");
//header("Content-Length: $size;\n");
//header("Content-Disposition: attachment; filename=\"$file_name\";\n\n");
//
//echo $content;


header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $file_name);
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . $size );
ob_clean();
flush();
echo $content;
exit;