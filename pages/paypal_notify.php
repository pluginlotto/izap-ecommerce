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


/**
 * this function updates the metadata for any entity, it overrides the canEdit
 * permission for all entities mainly being used for the quantity deduction and
 * the view increment
 *
 * @global object $CONFIG global config object
 * @param integer $entity_guid guid of the entity
 * @param array $mainArray
 * @return TRUE | FALSE
 */
function izapUpdateObject($entity_guid, $mainArray = array()) {
  global $CONFIG;
  foreach ($mainArray as $name => $value) {
    $existing = get_data_row("SELECT * from {$CONFIG->dbprefix}metadata WHERE entity_guid = $entity_guid and name_id=" . add_metastring($name) . " limit 1");
    if (($existing)) {
      $nameId = add_metastring($name);
      if(!$nameId) return FALSE;

      $valueId = add_metastring($value);
      if(!$valueId) return FALSE;

      $id = $existing->id;
      $result = izapUpdateMetadata($id, $nameId, $valueId);

      if (!$result) {
        return false;
      }
    }else {
      return FALSE;
    }
  }
  return TRUE;
}

/**
 * this function actully updates the metadata in the database
 *
 * @global object $CONFIG global CONFIG object
 * @param integer $id
 * @param integer $nameId
 * @param integer $valueId
 * @return TRUE | FALSE
 */
function izapUpdateMetadata($id, $nameId, $valueId) {
  global $CONFIG;
  $result = update_data("UPDATE {$CONFIG->dbprefix}metadata set value_id='$valueId' where id=$id and name_id='$nameId'");
  if ($result!==false) {
    $obj = get_metadata($id);
    if (trigger_elgg_event('update', 'metadata', $obj)) {
      return true;
    }
  }
  return $result;
}

IzapEcommerce::get_access();
global $IZAP_ECOMMERCE;

$debug = FALSE;
if(get_plugin_setting('sandbox', $IZAP_ECOMMERCE->plugin_name) == 'yes') {
  $debug = TRUE;
}

$gateway = new gateway('paypal', '', $debug);
$variables = $gateway->gopaypal();

if($variables['status']) {
  global $IZAP_ECOMMERCE;
  
  $paypal_invoice_id = $variables['invoiceid'];
  $order_id = $variables['ipn_data']['custom'];
  $order = get_entity($order_id);
  $main_array['confirmed'] = 'yes';
  $main_array['paypal_invoice_id'] = $paypal_invoice_id;
  $result = izapUpdateObject($order_id, $main_array);
  notify_user(
          $order->owner_guid,
          $CONFIG->site->guid,
          'Order has been confirmed',
          'Your order has been confirmed. Please check here: ' . $IZAP_ECOMMERCE->link . 'order_detail/' . $order->guid
          );
}
IzapEcommerce::remove_access();