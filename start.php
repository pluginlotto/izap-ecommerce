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

  if(!include_once func_get_pages_path_byizap() . $mode . $page[0] . '.php') {
    if(!include_once func_get_pages_path_byizap() . $page[0] . '.php') {
      include_once func_get_pages_path_byizap() . $mode .'index.php';
    }
  }
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