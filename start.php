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

function izap_ecommerce_init() {
  if(is_plugin_enabled('izap-elgg-bridge')) {
    func_init_plugin_byizap(array('plugin'=>array('name'=>'izap-ecommerce')));
  }else{
    register_error('This plugin needs izap-elgg-bridge');
    disable_plugin('izap-ecommerce');
  }
}

function izap_ecommerce_page_handler($page) {
  global $CONFIG;
  set_input('username', get_loggedin_user()->username);
  izap_set_params($page);
  $version = (float) get_version(TRUE);
  if($version < 1.7) {
    $mode = 'elgg16/';
  }
  
  if(!include_once func_get_pages_path_byizap() . $mode . $page[0] . '.php') {
    include_once func_get_pages_path_byizap() . $mode .'index.php';
  }
}

function izap_ecommerce_getUrl($entity) {
  global $IZAP_ECOMMERCE, $CONFIG;
  return $IZAP_ECOMMERCE->link . 'product/' . $entity->guid . '/' . $entity->slug . '/';
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