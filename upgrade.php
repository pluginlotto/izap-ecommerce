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

$options['type'] = 'object';

// updating products
$options['subtype'] = GLOBAL_IZAP_ECOMMERCE_SUBTYPE;
$options['limit'] = 9999;

if(is_callable('elgg_get_entities')) {
  $all_products = elgg_get_entities($options);
}else {
  $all_products = get_entities($options['type'], $options['subtype'], 0, '', $options['limit']);
}

if($all_products) {
  foreach($all_products as $product) {
    // set default for the archive
    if(empty ($product->archived)) {
      $product->archived = 'no';
    }

    // set default for the code
    if(empty ($product->code)) {
      $product->code = func_generate_unique_id();
    }

    // set default for the rating
    if(empty ($izap_product->avg_rating)) {
      $izap_product->avg_rating = (int) 0;
    }
  }
}
// end of updating products


// updating orders
$options['subtype'] = 'izap_order';
$options['limit'] = 9999;
$options['metadata_name'] = 'confirmed';
$options['metadata_value'] = 'yes';

if(is_callable('elgg_get_entities_from_metadata')) {
  $all_orders = elgg_get_entities_from_metadata($options);
}else {
  $all_orders = get_entities_from_metadata($options['metadata_name'], $options['metadata_value'], $options['type'], $options['subtype'], 0, $options['limit']);
}

if($all_orders) {
  foreach($all_orders as $order) {
    $owner = $order->getOwnerEntity();
    for($i = 0; $i < $order->total_items; $i++) {
      $item_guid = 'item_guid_' . $i;
      $product = get_entity($order->$item_guid);
      if($product) {
        $purchased_guid = 'purchased_' . $product->guid;
        $purchased_code = 'purchased_' . $product->code;
        $owner->$purchased_guid = 'yes';
        $owner->$purchased_code = 'yes';
      }
    }
  }
}

// end of updating orders

echo func_set_href_byizap(array(
'context' => GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER,
'page' => 'index',
));

forward(func_set_href_byizap(array(
        'context' => GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER,
        'page' => 'index',
)));
exit;