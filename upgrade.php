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

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
admin_gatekeeper();
ob_start();
?>
<h1>
  Upgrading old products
</h1>
<?php
ob_flush();
if(is_callable('elgg_get_entities')) {
  $all_products = elgg_get_entities(array('type' => 'object', 'subtype' => GLOBAL_IZAP_ECOMMERCE_SUBTYPE));
}else{
  $all_products = get_entities('object', GLOBAL_IZAP_ECOMMERCE_SUBTYPE);
}

if($all_products) {
  foreach($all_products as $product) {
    $product->archived = 'no';
  }
}
forward(func_set_href_byizap(array(
  'pluign' => GLOBAL_IZAP_ECOMMERCE_PLUGIN
)));
exit;