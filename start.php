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

define('GLOBAL_IZAP_ECOMMERCE_PLUGIN', 'izap-ecommerce');
define('GLOBAL_IZAP_ECOMMERCE_PAGEHANDLER', 'store');
define('GLOBAL_IZAP_ECOMMERCE_SUBTYPE', 'izap_ecommerce');

function izap_ecommerce_init() {
  if(is_plugin_enabled('izap-elgg-bridge')) {
    func_init_plugin_byizap(array('plugin'=>array('name'=>GLOBAL_IZAP_ECOMMERCE_PLUGIN)));
  }else {
    register_error(GLOBAL_IZAP_ECOMMERCE_PLUGIN . ' plugin, needs izap-elgg-bridge');
    disable_plugin(GLOBAL_IZAP_ECOMMERCE_PLUGIN);
  }
}

function izap_ecommerce_page_handler($page) {
  global $CONFIG;
  if(get_user_by_username($page[1])) {
    set_input('username', $page[1]);
  }else {
    set_input('username', get_loggedin_user()->username);
  }
  izap_set_params($page);
  $version = (float) get_version(TRUE);
  if($version < 1.7) {
    $mode = 'elgg16/';
  }

  $intial_path = func_get_pages_path_byizap();
  $filename = $intial_path . $mode . $page[0] . '.php';
  if(!file_exists($filename)) {
    $filename = $intial_path . $page[0] . '.php';
    if(!file_exists($filename)) {
      $filename = $intial_path . 'index.php';
    }
  }
  izap_load_file($filename, array(
    'plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN
  ));
}

function izap_ecommerce_getUrl($entity) {

  return func_set_href_byizap(array(
          'plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
          'page_owner' => get_user($entity->container_guid)->username,
          'page' => 'product',
          'vars' => array($entity->guid, $entity->slug)
  ));
}


function izap_order_getUrl($entity) {

  return func_set_href_byizap(array(
          'plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
          'page_owner' => FALSE,
          'page' => 'order_detail',
          'vars' => array($entity->guid)
  ));
}

register_elgg_event_handler('init', 'system', 'izap_ecommerce_init');

// HELPER FUNCTIONS
function izap_set_params($array) {
  foreach($array as $key => $value) {
    set_input('izap_param_' . $key, $value);
  }
}

function izap_get_params($key, $default = '') {
  return get_input('izap_param_' . $key, $default);
}

function izap_alertpay_process_order($hook, $entity_type, $returnvalue, $params) {
  global $IZAP_ECOMMERCE;

  $reference_num = $params['transactionReferenceNumber'];
  $order_id = $params['myCustomField_3'];
  $order = get_entity($order_id);

  $main_array['confirmed'] = 'yes';
  $main_array['payment_transaction_id'] = $reference_num;

  $provided['entity'] = $order;
  $provided['metadata'] = $main_array;
  func_izap_update_metadata($provided);

  // save purchased product info with user
  save_order_with_user_izap_ecommerce($order);

  IzapEcommerce::sendOrderNotification($order);
}

function izap_alertpay_fail($hook, $entity_type, $returnvalue, $params) {
  global $IZAP_ECOMMERCE;

  $order_id = $params['myCustomField_3'];
  $order = get_entity($order_id);

  $main_array['confirmed'] = 'no';
  $main_array['error_status'] = 'Error while Payment';
  $main_array['error_time'] = time();
  $main_array['return_response'] = serialize($params);

  $provided['entity'] = $order;
  $provided['metadata'] = $main_array;
  func_izap_update_metadata($provided);

  notify_user(
          $order->owner_guid,
          $CONFIG->site->guid,
          elgg_echo('izap-ecommerce:order_processe_error'),
          elgg_echo('izap-ecommerce:order_processe_error_message') . $IZAP_ECOMMERCE->link . 'order_detail/' . $order->guid
  );
}
